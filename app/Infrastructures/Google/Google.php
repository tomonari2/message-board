<?php

namespace App\Infrastructures\Google;

use App\Infrastructures\exceptions\ApiRequestFailedException;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Log;

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
        $data = [
            'form_params' => [
                'code' => $code,
                'client_id' => '659912505482-nadr0n90ju001qgbbkk0775hfv3o9muk.apps.googleusercontent.com',
                'client_secret' => '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',
                'redirect_uri' => route('google.callback'),
                'grant_type' => 'authorization_code',
            ]
        ];

        $data = $this->requestApi('POST', 'oauth2/v4/token', $data);

        $carbon = new Carbon(now());
        $carbon->addSeconds($data['expires_in']);
        $expirationDateTime = $carbon->toDateTimeString();
        session(['expirationDateTime' => $expirationDateTime]);

        $accessToken = $data['access_token'];
        return $accessToken;
    }

    /**
     * Googleドライブ・マルチアップロードAPIのリクエスト
     * 
     * @param string $tempPath
     * @param string $description
     * @return 
     */
    public function uploadImageToGoogleDrive(string $tempPath, string $description)
    {
        $filePath = storage_path('app/' . $tempPath);
        $mimetype = mime_content_type($filePath);

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

        $data = [
            'headers' => [
                'Authorization' => 'Bearer ' . session('access_token'),
                'Content-Type' => 'multipart/related; boundary=foo_bar_baz',
            ],
            'body' => $body,
        ];

        $data = $this->requestApi('POST', 'upload/drive/v3/files?uploadType=multipart', $data);

        return $data['id'];
    }

    public function searchFiles()
    {
        $folderId = config('const.folder_id');

        $data = [
            'headers' => [
                'Authorization' => 'Bearer ' . session('access_token'),
            ]
        ];

        $data = $this->requestApi('GET', 'drive/v3/files?q=\'' . $folderId . '\' in parents', $data);

        return $data['files'];
    }


    public function deleteImage(string $imageId)
    {
        // Google Drive APIの認証情報
        $data = [
            'headers' => [
                'Authorization' => 'Bearer ' . session('access_token'),
            ]
        ];

        $response = $this->requestApi('DELETE',  'drive/v3/files/' . $imageId, $data);

        return;
    }

    private function requestApi(
        string $method,
        string $endPoint,
        array $data
    ) {
        $client = new Client(['base_uri' => config('const.google_api_url')]);

        try {
            $response = $client->request($method, $endPoint, $data);
            $responseBody = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\GuzzleHttp\Exception\GuzzleException | \JsonException $e) {
            Log::info($e->getMessage());
            // report($e);
            throw new ApiRequestFailedException('GoogleドライブAPIのリクエストでエラーが発生しました。');
        }

        return $responseBody;
    }
}
