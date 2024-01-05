<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GitHub;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        if (!session('github_access_token') || session('github_expirationDateTime') < now()) {
            return $this->redirectToGitHubAuthorizationUrl();
        }

        $url="https://github.com/login/oauth/authorize?client_id=".env('GITHUB_CLIENT_ID');
        redirect()->to($url);
        $client = new Client();

        $response = $client->post('https://github.com/login/oauth/access_token', [
            'form_params' => [
                'client_id' => env('GITHUB_CLIENT_ID'),
                'client_secret' => env('GITHUB_CLIENT_SECRET'),
                'code' => 'AUTHORIZATION_CODE',
            ],
        ]);

        $data = $response->getBody()->getContents();
        dd($data);

        dd($request->username);
        $client = new Client();

        $username = $request->username; // GitHubのユーザー名
        $apiToken = 'YOUR_GITHUB_API_TOKEN'; // GitHubのAPIキー

        $response = $client->get("https://api.github.com/users/{$username}", [
            'headers' => [
                'Authorization' => "Bearer $apiToken",
                'Accept' => 'application/json',
            ],
        ]);

        // レスポンスボディをJSONから配列に変換
        $userData = json_decode($response->getBody(), true);

        // 取得したユーザー情報を表示
        dd($userData);
    }

    /**
     * Googleドライブ認可URLにリダイレクトする
     *
     * @
     */
    private function redirectToGitHubAuthorizationUrl()
    {
        $authorizationUrl = GitHub::getAuthorizationUrl();
// dd($authorizationUrl);
        return redirect()->to($authorizationUrl);
    }

    public function handleGitHubCallback(Request $request)
    {
        //更新トークンを取得
        // $accessToken=Google::getGoogleDriveRefreshToken($request->query('code'));

        $accessToken = GitHub::getGoogleDriveAccessToken($request->query('code'));

        session(['access_token' => $accessToken]);

        return redirect()->action('GoogleDriveImageController@index');
    }
}
