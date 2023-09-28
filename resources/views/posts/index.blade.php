@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ようこそ{{ $user->name }}さん <a href ="{{route('logout')}}" class="btn btn-danger">ログアウト</a></h1>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <textarea name="content" class="form-control" placeholder="投稿内容"></textarea>
        </div>
        <div class="form-group">
            <label for="image">画像を選択</label>
            <input type="file" name="image" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-primary">投稿</button>
    </form>

    <h1 class="mt-4">投稿一覧</h1>

    <ul class="list-group mt-3">
        @foreach ($posts as $post)
            <li class="list-group-item">
                <p>{{ $post->content }}</p>
                @if ($post->image_path)
                    <img src="{{ asset($post->image_path) }}" alt="Post Image" class="img-fluid rounded">
                @endif
                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">削除</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
