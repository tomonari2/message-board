<?php

namespace App\Infrastructures\Google;

use Carbon\Carbon;
use GuzzleHttp\Client;

class Google
{
    public function getAuthorizationUrl(string $scope)
    {
        $authorizationUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => '659912505482-nadr0n90ju001qgbbkk0775hfv3o9muk.apps.googleusercontent.com',
            'redirect_uri' => route('google.callback'),
            'response_type' => 'code',
            'scope' => $scope,
            // 'access_type' => 'offline',//更新トークンが必要な場合
            // 'prompt' => 'consent',//同意画面を毎回表示
        ]);

        return $authorizationUrl;
    }

    public function getGoogleDriveAccessToken(string $code)
    {
        $client = new Client(['base_uri' => config('const.google_api_url')]);

        $response = $client->request('POST', 'oauth2/v4/token', [
            'form_params' => [
                'code' => $code,
                'client_id' => '659912505482-nadr0n90ju001qgbbkk0775hfv3o9muk.apps.googleusercontent.com',
                'client_secret' => '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',
                'redirect_uri' => route('google.callback'),
                'grant_type' => 'authorization_code',
            ],
        ]);

        // アクセストークンを取得
        $data = json_decode($response->getBody(), true);
        $carbon = new Carbon(now());
        $carbon->addSeconds($data['expires_in']);
        $expirationDateTime = $carbon->toDateTimeString();
        session(['expirationDateTime' => $expirationDateTime]);

        $accessToken = $data['access_token'];
        return $accessToken;
    }

    // public function getGoogleDriveRefreshToken(string $code)
    // {
    //     $client = new Client(['base_uri' => 'https://oauth2.googleapis.com']);

    //     $response = $client->request('POST', 'token', [
    //         'form_params' => [
    //             'code' => $code,
    //             'client_id' => '659912505482-nadr0n90ju001qgbbkk0775hfv3o9muk.apps.googleusercontent.com',
    //             'client_secret' => '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',
    //             'redirect_uri' => route('google.callback'),
    //             'grant_type' => 'authorization_code',
    //         ],
    //     ]);

    //     // アクセストークンを取得
    //     $data = json_decode($response->getBody(), true);

    //     $accessToken = $data['access_token'];
    //     return $accessToken;
    // }

    public function uploadImageToGoogleDrive(string $tempPath, string $description)
    {
        $filePath = storage_path('app/' . $tempPath);
        $mimetype = mime_content_type($filePath);

        $headers = [
            'Authorization' => 'Bearer ' . session('access_token'),
            'Content-Type' => 'multipart/related; boundary=foo_bar_baz',
        ];

        $fileContents = file_get_contents($filePath);

        $body = <<<EOF
            --foo_bar_baz
            Content-Type: application/json; charset=UTF-8

            {
              "name": "画像の説明文を入れるとこ",
              "description": "{$description}",
              "parents": ["1ZKzyMAiEfx7Ispwpd8lYqwhe83jmOi3e"]
            }

            --foo_bar_baz
            Content-Type: {$mimetype}

            {$fileContents}
            --foo_bar_baz--
            EOF;

        $client = new Client(['base_uri' => config('const.google_api_url')]);

        $response = $client->request('POST', 'upload/drive/v3/files?uploadType=multipart', [
            'headers' => $headers,
            'body' => $body,
        ]);
        $data = json_decode($response->getBody(), true);

        return $data['id'];
    }

    public function searchFiles()
    {
        $client = new Client(['base_uri' => config('const.google_api_url')]);

        $folderId = '1ZKzyMAiEfx7Ispwpd8lYqwhe83jmOi3e';

        $headers = [
            'Authorization' => 'Bearer ' . session('access_token'),
        ];

        $response = $client->request('GET', 'drive/v3/files?q=\'' . $folderId . '\' in parents', [
            'headers' => $headers,
        ]);
        $data = json_decode($response->getBody(), true);
        return $data['files'];
    }

    public function deleteImage(string $imageId){
        // Google Drive APIの認証情報
        $headers = [
            'Authorization' => 'Bearer ' . session('access_token'),
        ];

        $client = new Client(['base_uri' => config('const.google_api_url')]);

        $response = $client->request('DELETE', 'drive/v3/files/'.$imageId, [
            'headers' => $headers,
        ]);

        return;
    }
}
