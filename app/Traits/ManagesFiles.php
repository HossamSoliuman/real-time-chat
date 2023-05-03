<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ManagesFiles
{
    /**
     * Upload a file to the specified directory in the public disk.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $directory
     * @return string|null
     */
    public function uploadFile($file, $directory)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalName . '_' . time() . '.' . $file->extension();
        $filePath = $file->move($directory, $fileName);
        return $filePath;
    }

    /**
     * Delete a file from the specified directory in the public disk.
     *
     * @param  string  $filePath
     * @return bool
     */
    public function deleteFile($filePath)
    {

        $file = public_path($filePath);
        $result = File::delete($file);
        return $result;
    }
}
