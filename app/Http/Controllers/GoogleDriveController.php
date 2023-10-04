<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google;
use Log;

class GoogleDriveImageController extends Controller
{
    /**Googleドライブでユーザーに付与を依頼する権限 */
    protected string $scope = 'https://www.googleapis.com/auth/drive';

    public function handleGoogleCallback(Request $request)
    {
        $accessToken = Google::getGoogleDriveAccessToken($request->query('code'));

        session(['access_token' => $accessToken]);

        return redirect()->action('GoogleDriveImageController@store');
    }

    public function store(Request $request)
    {
        if (!session('access_token')) {
            return $this->redirectToGoogleAuthorizationUrl();
        }
        // dd('b');

        $uploadedFile = $request->file('file'); // リクエストからファイルを取得

        if ($uploadedFile) {
            $tempPath = $uploadedFile->store('temp'); // ファイルを一時的に保存
        }

        Google::uploadImageToGoogleDrive($tempPath, $request->description);
    }

    private function redirectToGoogleAuthorizationUrl()
    {
        $authorizationUrl = Google::getAuthorizationUrl($this->scope);

        Log::info('LINE Login authorization request', [$authorizationUrl]);

        return redirect()->to($authorizationUrl);
    }

    public function index(Request $request)
    {
        // session()->forget('access_token');
        $imageList = Google::searchFiles();

        $user = $request->user();

        return view('posts.index', compact('user', 'posts', 'imageList'));
    }
}
