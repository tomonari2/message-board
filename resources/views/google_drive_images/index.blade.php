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

    <div class="row">
        @foreach ($imageList as $imageData)
        <div class="col-md-4">
            <div id="image_list">
                <img src="https://drive.google.com/uc?id={{ $imageData['id'] }}" alt="画像">
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('js')
<script>
    const $request_form = $('#request_form');
    console.log($request_form);
    $request_form.submit(function(event) {
        const $request_form = $('#request_form');
        event.preventDefault();

        const formData = new FormData();

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
        console.log($request_form.attr('method'));
        console.log($request_form.attr('action'));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $.ajax({
            type: $request_form.attr('method'),
            url: $request_form.attr('action'),
            processData: false,
            contentType: false,
            data: formData,
            dataType: 'json',
            timeout: 60000, // 60s
        }).done(function(data) {
            console.log('done');
            console.log(data);
            // 新しい画像のURLを使って、新しい画像要素を作成し、リストに追加します
            let newImage = $('<img>').attr('src', data.imageUrl).attr('alt', '新しい画像');

            // 画像リストのコンテナに新しい画像を追加します
            $('#image_list').append(newImage);
        });
    });
</script>
@endpush
