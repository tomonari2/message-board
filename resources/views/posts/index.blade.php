{{-- <img src="{{ asset('about_receipt_1.png') }}" alt="">
<img src="{{ asset('images/1695821055.png') }}" alt=""> --}}
ようこそ{{ $user->name }}さん

<form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
    @csrf
    <textarea name="content" placeholder="投稿内容"></textarea>
    <input type="file" name="image">
    <button type="submit">投稿</button>
</form>

<h1>投稿一覧</h1>

<ul>
    @foreach ($posts as $post)
        <li class="post">
            <p>{{ $post->content }}</p>
            @if ($post->image_path)
                {{-- <img src="{{ asset( $post->image_path) }}" alt="Post Image"> --}}
                <img src="{{ asset( $post->image_path) }}" alt="Post Image">
                <img src="{{ asset( 'storage/'.$post->image_path) }}" alt="Post Image">
            @endif
        </li>
    @endforeach
</ul>
{{-- {{dd(asset('public/assets/camera.png'))}} --}}
<img src="{{ asset('public/assets/camera.png') }}" alt="Camera Image">
<img src="{{ asset('storage/images/1695821055.png') }}" alt="Image Description">
<img src ="http://mb.test.com/public/storage/images/695821055.png">
<img src="{{ asset('storage/images/1695864099.png') }}" alt="Image">
