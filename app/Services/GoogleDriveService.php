<?php

namespace App\Services;

use App\Models\GoogleDriveFile;
use App\Models\User;
use App\Services\Concerns\ManagesGoogleClient;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    use ManagesGoogleClient;

    protected Client $client;
    protected User $user;

    public function __construct(User $user)
    {
        $this->user   = $user;
        $this->client = $this->makeGoogleClient($user, [
            'https://www.googleapis.com/auth/drive',
            'https://www.googleapis.com/auth/drive.file',
        ]);
    }

    public function uploadFile(
        string $filePath,
        ?string $fileName = null,
        ?string $description = null,
        ?string $mimeType = null
    ): ?GoogleDriveFile {
        try {
            if (!$this->user->googleToken) {
                Log::warning("User {$this->user->id} has no Google token. Skipping uploadFile.");
                return null;
            }

            // ── 1. Upload file ke Drive User A (uploader) ──
            $savedFile = $this->uploadFileForUser(
                $this->user,
                $this->client,
                $filePath,
                $fileName,
                $description,
                $mimeType
            );

            // ── 2. Broadcast file ke semua user lain yang sudah connect Google ──
            $otherUsers = User::where('id', '!=', $this->user->id)
                ->whereHas('googleToken')
                ->get();

            foreach ($otherUsers as $otherUser) {
                try {
                    $targetClient = $this->makeGoogleClient($otherUser, [
                        'https://www.googleapis.com/auth/drive',
                        'https://www.googleapis.com/auth/drive.file',
                    ]);

                    // Kalau token tidak valid → skip
                    if (!$targetClient->getAccessToken()) {
                        Log::warning("Skipping user {$otherUser->id}: no valid access token.");
                        continue;
                    }

                    $this->uploadFileForUser(
                        $otherUser,
                        $targetClient,
                        $filePath,
                        $fileName,
                        $description,
                        $mimeType
                    );

                    Log::info("File uploaded for user {$otherUser->id} ({$otherUser->email})");

                } catch (\Exception $e) {
                    // Satu user gagal → skip, lanjut ke user berikutnya
                    Log::warning("Failed to upload file for user {$otherUser->id}: " . $e->getMessage());
                }
            }

            return $savedFile;

        } catch (\Exception $e) {
            Log::error('Google Drive upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload file ke Google Drive milik $targetUser
     * menggunakan OAuth token mereka sendiri yang tersimpan di DB.
     *
     * makeGoogleClient($targetUser) → ambil token User B dari DB
     *                               → auto-refresh kalau expired
     *                               → Google Client bertindak sebagai User B
     */
    protected function uploadFileForUser(
        User $targetUser,
        Client $targetClient,
        string $filePath,
        ?string $fileName,
        ?string $description,
        ?string $mimeType
    ): ?GoogleDriveFile {
        $service     = new Drive($targetClient);
        $fileContent = file_get_contents($filePath);
        $fileName    = $fileName ?? basename($filePath);
        $mimeType    = $mimeType ?? mime_content_type($filePath) ?: 'application/octet-stream';
        $folderId    = config('services.google.drive_folder_id');

        $driveFile = new DriveFile();
        $driveFile->setName($fileName);
        $driveFile->setDescription($description);

        if ($folderId) {
            $driveFile->setParents([$folderId]);
        }

        $response = $service->files->create($driveFile, [
            'data'       => $fileContent,
            'mimeType'   => $mimeType,
            'uploadType' => 'multipart',
            'fields'     => 'id, name, mimeType, size, webViewLink, parents',
        ]);

        return GoogleDriveFile::create([
            'user_id'                => $targetUser->id,
            'google_file_id'         => $response->getId(),
            'file_name'              => $response->getName(),
            'file_path'              => $filePath,
            'mime_type'              => $response->getMimeType(),
            'file_size'              => $response->getSize(),
            'web_view_link'          => $response->getWebViewLink(),
            'google_drive_folder_id' => $folderId,
            'description'            => $description,
        ]);
    }

    public function listFiles(int $pageSize = 10): array
    {
        try {
            if (!$this->user->googleToken) {
                return [];
            }

            $service = new Drive($this->client);
            $results = $service->files->listFiles([
                'pageSize' => $pageSize,
                'fields'   => 'files(id, name, mimeType, size, createdTime, webViewLink)',
                'q'        => 'trashed=false',
            ]);

            return $results->getFiles() ?? [];
        } catch (\Exception $e) {
            Log::error('Google Drive list failed: ' . $e->getMessage());
            return [];
        }
    }

    public function deleteFile(string $googleFileId): bool
    {
        try {
            if (!$this->user->googleToken) {
                return false;
            }

            $service = new Drive($this->client);
            $service->files->delete($googleFileId);

            GoogleDriveFile::where('google_file_id', $googleFileId)
                ->where('user_id', $this->user->id)
                ->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Google Drive delete failed: ' . $e->getMessage());
            return false;
        }
    }
}