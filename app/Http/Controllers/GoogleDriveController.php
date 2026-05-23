<?php

namespace App\Http\Controllers;

use App\Models\GoogleDriveFile;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GoogleDriveController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $files = $user->googleDriveFiles()->latest()->paginate(15);

        return view('google-drive.index', compact('files'));
    }

   public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:102400',
        'description' => 'nullable|string|max:255',
    ]);

    try {
        $user = Auth::user();
        $file = $request->file('file');

        // ✅ Simpan ke disk local dengan benar
        $path = $file->store('temp', 'local');

        // ✅ Ambil full path yang benar (handle Windows & Linux)
        $fullPath = Storage::disk('local')->path($path);

        $driveService = new GoogleDriveService($user);
        $driveFile = $driveService->uploadFile(
            $fullPath,
            $file->getClientOriginalName(),
            $request->input('description'),
            $file->getMimeType()
        );

        // ✅ Hapus file temp setelah upload
        Storage::disk('local')->delete($path);

        if ($driveFile) {
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'file' => $driveFile,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to upload file to Google Drive.',
        ], 500);

    } catch (\Exception $e) {
        Log::error('File upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(), // sementara untuk debug
        ], 500);
    }
}

    public function delete($id)
    {
        try {
            $user = Auth::user();
            $file = GoogleDriveFile::findOrFail($id);

            if ($file->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $driveService = new GoogleDriveService($user);
            if ($driveService->deleteFile($file->google_file_id)) {
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully!',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('File delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the file.',
            ], 500);
        }
    }

    public function list()
    {
        try {
            $user = Auth::user();

            if (!$user->googleToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google account not connected.',
                ], 403);
            }

            $driveService = new GoogleDriveService($user);
            $files = $driveService->listFiles(50);

            return response()->json([
                'success' => true,
                'files' => $files,
            ]);
        } catch (\Exception $e) {
            Log::error('List files error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve files.',
            ], 500);
        }
    }
}
