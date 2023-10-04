<?php

namespace App\Infrastructures\Google;

use GuzzleHttp\Client;

class Google
{
    public function getAuthorizationUrl(string $scope)
    {
        $authorizationUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'scope' => $scope,
            'access_type' => 'offline',
            'include_granted_scope' => 'true',
            'response_type' => 'code',
            'redirect_uri' => route('google.callback'),
            'client_id' => '659912505482-nadr0n90ju001qgbbkk0775hfv3o9muk.apps.googleusercontent.com',
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
                'access_type' => 'offline',
            ],
        ]);

        // アクセストークンを取得
        $data = json_decode($response->getBody(), true);
        $accessToken = $data['access_token'];
        return $accessToken;
    }

    public function uploadImageToGoogleDrive()
    {
        $filePath = public_path('images/1695884174.webp');
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
              "description": "ファイルの説明",
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

        dd($response);
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
        dd($data);
        dd($response);
    }
}
