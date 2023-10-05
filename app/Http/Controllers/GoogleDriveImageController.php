<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google;

class GoogleDriveImageController extends Controller
{
    /**Googleドライブでユーザーに付与を依頼する権限 */
    protected string $scope = 'https://www.googleapis.com/auth/drive';

    public function index(Request $request)
    {
        if (!session('access_token') || session('expirationDateTime') < now()) {
            return $this->redirectToGoogleAuthorizationUrl();
        }

        $imageList = Google::searchFiles();

        $user = $request->user();

        return view('google_drive_images.index', compact('user', 'imageList'));
    }

    public function store(Request $request)
    {
        if (!session('access_token') || session('expirationDateTime') < now()) {
            return $this->redirectToGoogleAuthorizationUrl();
        }

        $uploadedFile = $request->file('file'); // リクエストからファイルを取得

        if ($uploadedFile) {
            $tempPath = $uploadedFile->store('temp'); // ファイルを一時的に保存
        }

        Google::uploadImageToGoogleDrive($tempPath, $request->description);
        return redirect()->action('GoogleDriveImageController@index');
    }

    private function redirectToGoogleAuthorizationUrl()
    {
        $authorizationUrl = Google::getAuthorizationUrl($this->scope);

        return redirect()->to($authorizationUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        //更新トークンを取得
        // $accessToken=Google::getGoogleDriveRefreshToken($request->query('code'));

        $accessToken = Google::getGoogleDriveAccessToken($request->query('code'));

        session(['access_token' => $accessToken]);

        return redirect()->action('GoogleDriveImageController@index');
    }
}
