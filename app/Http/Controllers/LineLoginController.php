<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LineLoginController extends Controller
{
    public function lineLogin()
    {
        $state = bin2hex(random_bytes(32));
        session(['state' => $state]);
        $params = array(
            'response_type' => 'code',
            'client_id' => 2000890659,
            'redirect_uri' => route('auth.line_callback'),
            'state' => $state,
            'scope' => 'profile openid',
        );

        $authorizationUrl = 'https://access.line.me/oauth2/v2.1/authorize?' . http_build_query($params);

        return redirect()->to($authorizationUrl);
    }

    public function handleLineCallback(Request $request)
    {
        if ($request->query('state') !== session('state')) {
            dd('CSRF的に良くない');
        }

        $client = new Client();

        $response = $client->post('https://api.line.me/oauth2/v2.1/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $request->query('code'),
                'redirect_uri' => route('auth.line_callback'),
                'client_id' => 2000890659,
                'client_secret' => '7d4412fe20bf88f134c1b874aa2afd14',
            ],
        ]);

        // レスポンスボディを取得
        $responseData = $response->getBody()->getContents();

        // レスポンスデータを処理する（例: JSONデコードなど）
        $parsedResponse = json_decode($responseData, true);

        $client = new Client();

        $response = $client->post('https://api.line.me/oauth2/v2.1/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer '. $parsedResponse['access_token'],
            ],
        ]);

         // レスポンスボディを取得
         $responseData = $response->getBody()->getContents();

        // レスポンスデータを処理する（例: JSONデコードなど）
        $parsedResponse = json_decode($responseData, true);

        $user = User::firstOrCreate(['sub' => $parsedResponse['sub'], 'name' => $parsedResponse['name'], 'picture' => $parsedResponse['picture']]);

        Auth::login($user);

        return redirect()->to(route('posts.index'));
    }
}
