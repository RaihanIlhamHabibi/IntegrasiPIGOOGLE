<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleDriveFile extends Model
{
    protected $fillable = [
        'user_id',
        'google_file_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'web_view_link',
        'google_drive_folder_id',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
