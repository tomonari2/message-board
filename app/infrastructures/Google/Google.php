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
        try {
            // $imagePath = asset('images/1695884174.webp');
            // $mime = mime_content_type($imagePath);
            // $imageUrl = asset('images/1695884174.webp');
            // $mime = mime_content_type(public_path('images/1695884174.webp'));
            // $imageData = file_get_contents(public_path('images/1695884174.webp'));
            // $base64Image = base64_encode($imageData);

            // $headers = [
            //     'Authorization' => 'Bearer ' . session('access_token'),
            //     'Content-Type' => 'image/'.$mime,
            // ];

            // $client = new Client(['base_uri' => config('const.google_api_url')]);

            // $response = $client->request('POST', 'upload/drive/v3/files?uploadType=media', [
            //     'headers' => $headers,
            //     'body' => $base64Image,
            // ]);
            $filePath = public_path('images/1695884174.webp');
            dd(mime_content_type($filePath));

            $headers = [
                'Authorization' => 'Bearer ' . session('access_token'),
                'Content-Type' => mime_content_type($filePath),
            ];

            $client = new Client(['base_uri' => config('const.google_api_url')]);

            $response = $client->request('POST', 'upload/drive/v3/files?uploadType=media', [
                'headers' => $headers,
                'body' => fopen($filePath, 'r'), // ファイルをストリームとして送信
            ]);

            dd($response->getBody());

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['id'])) {
                $fileId = $responseData['id'];
                echo "File ID: $fileId\n";
                return $fileId;
            } else {
                echo "File upload failed.\n";
                return null;
            }
        } catch (Exception $e) {
            echo "Error Message: " . $e->getMessage();
            return null;
        }
    }
}
