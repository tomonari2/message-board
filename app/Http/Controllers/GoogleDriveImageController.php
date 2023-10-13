<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google;
use Log;

class GoogleDriveImageController extends Controller
{
    /**Googleドライブでユーザーに付与を依頼する権限 */
    protected string $scope = 'https://www.googleapis.com/auth/drive';

    /**
     * Googleドライブのmessage-board-picturesフォルダ内の画像を表示
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!session('access_token') || session('expirationDateTime') < now()) {
            return $this->redirectToGoogleAuthorizationUrl();
        }

        $imageList = Google::searchFiles();

        $user = $request->user();

        return view('google_drive_images.index', compact('user', 'imageList'));
    }

    /**
     * Googleドライブに画像をアップロード
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function store(Request $request)
    {
        if (!session('access_token') || session('expirationDateTime') < now()) {
            return $this->redirectToGoogleAuthorizationUrl();
        }

        $uploadedFile = $request->file('file');

        if ($uploadedFile) {
            $tempPath = $uploadedFile->store('temp'); // ファイルを一時的に保存
        }

        $imageId = Google::uploadImageToGoogleDrive($tempPath, $request->description);
        $imageUrl = 'https://drive.google.com/uc?id=' . $imageId;

        return response()->json(['imageUrl' => $imageUrl]);
    }

    public function destroy(string $imageId)
    {
        Google::deleteImage($imageId);

        return redirect()->action('GoogleDriveImageController@index');
    }

    /**
     * Googleドライブ認可URLにリダイレクトする
     *
     * @
     */
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
