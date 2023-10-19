<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- CSSファイルを読み込む -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- CSSファイルを読み込む -->
    {{-- {{dd(asset('css/style.css'))}} --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .button-container {
            display: flex;
            justify-content: space-around;
            background-color: #007BFF;
            padding: 10px;
        }

        .button-container a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            border: 2px solid #fff;
            border-radius: 25px;
            transition: background-color 0.3s ease-in-out;
        }

        .button-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    @if (Auth::check())
        <div class="button-container">
            <a href="{{ route('drive.index') }}">Googleドライブ</a>
            <a href="/news">ニュース</a>
            <a href="{{ route('posts.index') }}">掲示板</a>
            <a href="{{ route('github.index') }}">Github</a>
        </div>
    @endif
    @yield('content')
    @stack('js')
</body>

</html>
