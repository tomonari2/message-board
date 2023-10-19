<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitHubController extends Controller
{
    public function index()
    {
        $client = new Client();

        // GitHub APIからデータを取得
        $response = $client->get('https://api.github.com/user', [
            'headers' => [
                'Authorization' => 'token YourAccessToken', // アクセストークンを指定
            ],
        ]);

        $userData = json_decode($response->getBody(), true);

        return view('github.index', compact('userData'));
    }
}
