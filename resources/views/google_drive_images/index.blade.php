@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $user->name }}<a href="{{ route('logout') }}" class="btn btn-danger">ログアウト</a></h1>

        <h1>画像をアップロード</h1>
        <form action="{{ route('drive.store') }}" method="post" enctype="multipart/form-data" id="request_form">
            @csrf
            <label for="file">画像を選択：</label>
            <input type="file" name="fileInput" id="fileInput" accept="image/*" required><br><br>
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

@push('js')
    <script>
        $request_form.submit(function(event) {
            let $request_form = $('#request_form');
            event.preventDefault();

            const formData = new FormData();
            formData.append('username', 'john_doe');
            let descriptionTextArea = document.getElementById('description');
            formData.append('description', descriptionTextArea.value);
            console.log(descriptionTextArea);
            console.log(descriptionTextArea.value);
            console.log(formData);

            let fileInput = document.getElementById('fileInput');
            console.log(fileInput);
            console.log(fileInput.files);
            console.log(fileInput.files[0]);
            formData.append('file', fileInput.files[0]);
            console.log(formData.getAll('file'));

            $.ajax({
                type: $request_form.attr('method'),
                url: $request_form.attr('action'),
                data: formData,
                success: function(response) { //リクエストが成功した場合の処理
                    console.log(response);
                },
                error: function(error) { //リクエストが失敗した場合の処理
                    console.log(error);
                }
            });
        });
    </script>
@endpush
