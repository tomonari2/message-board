<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <title>掲示板</title>
</head>

<body>
    TOPページ
    <!-- LINEログインボタン -->
    <a href="{{ route('line.login') }}">
        LINEログイン
    </a>
    <a href="{{ route('google.login') }}">
        Googleログイン
    </a>
</body>

</html>
