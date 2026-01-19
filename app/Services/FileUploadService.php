<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{

    public function upload(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        $filename = $file->hashName();
        $path = $file->storeAs($folder, $filename, $disk);

        return $path;
    }

    public function delete(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
