<?php

namespace App\UseCases\GoogleDriveImage;

use App\UseCases\ViewParams;
use App\User;
use Google;

class IndexAction
{
    public function __invoke(User $user)
    {
        $imageList = Google::searchFiles();

        return new ViewParams('google_drive_images.index', compact('user', 'imageList'));
    }
}
