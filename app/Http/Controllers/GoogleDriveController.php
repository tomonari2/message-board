<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google;
use GuzzleHttp\Client;

class GoogleDriveController extends Controller
{
    /**Googleドライブでユーザーに付与を依頼する権限 */
    protected string $scope = 'https://www.googleapis.com/auth/drive';

    public function handleGoogleCallback(Request $request)
    {
        $accessToken = Google::getGoogleDriveAccessToken($request->query('code'));

        session(['access_token' => $accessToken]);

        return redirect()->action('GoogleDriveController@upload');
    }

    public function upload(Request $request)
    {
        if (!session('access_token')) {
            return $this->redirectToGoogleAuthorizationUrl();
        }

        Google::uploadImageToGoogleDrive();
    }

    private function redirectToGoogleAuthorizationUrl()
    {
        $authorizationUrl = Google::getAuthorizationUrl($this->scope);

        // Log::info('LINE Login authorization request', [$authorizationUrl]);

        return redirect()->to($authorizationUrl);
    }

    public function download($fileId)
    {
        // ダウンロードAPIエンドポイント
        $downloadUrl = "https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media";

        // アクセストークン
        $accessToken = 'YOUR_ACCESS_TOKEN'; // 本来はアクセストークンを取得する必要があります

        // Guzzleを使用してファイルをダウンロード
        $client = new Client();
        $response = $client->get($downloadUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        // ファイルをブラウザに送信
        $headers = [
            'Content-Type' => $response->getHeaderLine('Content-Type'),
            'Content-Disposition' => 'inline; filename="downloaded_image.jpg"',
        ];

        return response($response->getBody(), 200, $headers);
    }
}
