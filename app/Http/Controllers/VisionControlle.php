<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class VisionController extends Controller
{
    public function analyzeImage(Request $request)
    {
        // 画像ファイルのパス（ローカルファイルまたはクラウドストレージのURLなど）
        $imagePath = 'path/to/your/image.jpg';

        // Google Cloud Vision APIのエンドポイント
        $apiEndpoint = 'https://vision.googleapis.com/v1/images:annotate?key=YOUR_API_KEY';

        // 画像ファイルをバイナリデータとして読み込む
        $imageData = file_get_contents($imagePath);

        // Guzzle HTTPクライアントを作成
        $client = new Client();

        // APIリクエストを送信
        $response = $client->post($apiEndpoint, [
            'json' => [
                'requests' => [
                    [
                        'image' => [
                            'content' => base64_encode($imageData), // 画像データをBase64エンコード
                        ],
                        'features' => [
                            [
                                'type' => 'TEXT_DETECTION', // 画像内のテキストを検出
                                'maxResults' => 10, // 最大結果数
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        // APIのレスポンスを取得
        $result = json_decode($response->getBody(), true);

        // レスポンスを処理し、必要な情報を取得
        // 例: テキスト検出の結果を取得
        $textAnnotations = $result['responses'][0]['textAnnotations'];

        // 取得した情報をビューに渡して表示するなどの処理を行う

        return view('result', ['textAnnotations' => $textAnnotations]);
    }
}
