<?php

namespace App\UseCases\GoogleDriveImage;

use Google;
use Illuminate\Http\UploadedFile;

class StoreAction
{
    public function __invoke(UploadedFile $uploadedFile, string $description)
    {
        if ($uploadedFile) {
            $tempPath = $uploadedFile->store('temp'); // ファイルを一時的に保存
        }

        $imageId = Google::uploadImageToGoogleDrive($tempPath, $description);
        $imageUrl = 'https://drive.google.com/uc?id=' . $imageId;

        return $imageUrl;
    }
}
