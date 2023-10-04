@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>ようこそ{{ $user->name }}さん <a href="{{ route('logout') }}" class="btn btn-danger">ログアウト</a></h1>
        
        <h1>画像をアップロード</h1>
        <form action="{{ route('drive.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <label for="file">画像を選択：</label>
            <input type="file" name="file" id="file" accept="image/*" required><br><br>
            <label for="description">ファイルの説明：</label>
            <textarea name="description" id="description" rows="4" cols="50"></textarea><br><br>
            <input type="submit" value="アップロード">
        </form>

        <h1 class="mt-4">投稿一覧</h1>

        @foreach ($imageList as $imageData)
            <img src="https://drive.google.com/uc?id={{ $imageData['id'] }}" alt="画像">
        @endforeach
    </div>
@endsection
