<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class VisionController extends Controller
{
    public function vision2(Request $request)
    {
        // dd($request->code);
        // dd($request->scope);
        // Guzzleクライアントを作成
        $client = new Client();

        // パラメーターを設定
        $params = [
            'form_params' => [
                'code' => $request->code,
                'client_id' => '659912505482-nadr0n90ju001qgbbkk0775hfv3o9muk.apps.googleusercontent.com',
                'client_secret' => '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',
                'redirect_uri' => 'https://mb.test.com/vision2',
                'grant_type' => 'authorization_code',
                'access_type' => 'offline',
            ],
        ];

        // POSTリクエストを送信
        $response = $client->request('POST', 'https://www.googleapis.com/oauth2/v4/token', $params);
        // レスポンスを取得
        $responseBody = $response->getBody()->getContents();
        $dataArray = json_decode($responseBody, true);

        $client = new Client();
        // 画像ファイルのパス
        $imagePath = 'public/images/1695969646.png';

        // 画像ファイルをBase64エンコード
        $imageData = base64_encode(file_get_contents(public_path('images/1695969646.png')));
        // ヘッダーを設定
        $headers = [
            'Authorization' => 'Bearer ' . $dataArray['access_token'],
            'x-goog-user-project' => 'messa-397800',
            'Content-Type' => 'application/json; charset=utf-8',
        ];

        // リクエストボディを設定
        $requestBody = json_encode([
            'requests' => [
                [
                    'image' => [
                        'content' => $imageData,
                    ],
                    'features' => [
                        [
                            'type' => 'TEXT_DETECTION',
                        ],
                    ],
                ],
            ],
        ]);

        // リクエストを送信
        $response = $client->post('https://vision.googleapis.com/v1/images:annotate', [
            'headers' => $headers,
            'body' => $requestBody,
        ]);

        // レスポンスを取得
        $responseBody = $response->getBody()->getContents();
        // JSONデータを連想配列に変換
        $data = json_decode($responseBody, true);

        // 変換された連想配列を確認
        var_dump($data);
    }

    public function analyzeImage(Request $request)
    {

        // リダイレクト先URLのパラメーターを設定
        $redirectUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'scope' => 'https://www.googleapis.com/auth/cloud-vision',
            'access_type' => 'offline',
            'include_granted_scope' => 'true',
            'response_type' => 'code',
            'redirect_uri' => 'https://mb.test.com/vision2',
            'client_id' => '659912505482-nadr0n90ju001qgbbkk0775hfv3o9muk.apps.googleusercontent.com',
        ]);

        // リダイレクト
        return redirect()->to($redirectUrl);
    }
}
