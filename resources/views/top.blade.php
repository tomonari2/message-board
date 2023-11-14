@extends('layouts.app')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">掲示板ログイン</div>
                <div class="card-body">
                    <a href="{{ route('line.login') }}" class="btn btn-primary btn-lg btn-block mb-3">LINEログイン</a>
                    <a href="{{ route('google.login') }}" class="btn btn-danger btn-lg btn-block">Googleログイン</a>
                    <a href="{{ route('github.login') }}" class="btn btn-danger btn-lg btn-block">GitHubログイン</a>
                </div>
            </div>
        </div>
    </div>
</div>

<a data-btn="login" href="/">年齢に答えるだけでログイン</a>
<div style="display:none" data-modal="age">
    <div data-age="form">
        <select>
            <option value="">年齢</option>
            @foreach(range(0,70) as $age)
            <option value="{{$age}}">{{$age}}</option>
            @endforeach
        </select>
        <button type="submit" disabled>OK</button>
    </div>
</div>
@endsection

@push('js')
<script>
    $(function() {
        // 年齢確認モーダル
        const key = 'is-age-verified';
        const isAgeVerified = JSON.parse(localStorage.getItem(key) || sessionStorage.getItem(key));
        const $ageModal = $('[data-modal="age"]');
        const $loginBtn = $('[data-btn="login"]');
        const $ageForm = $('[data-modal="age"]');
        const $ageSelect = $ageForm.find('select');
        const $ageSubmit = $ageForm.find('button[type="submit"]');


        $loginBtn.on('click', function() {
            if (isAgeVerified === null) {
                console.log('a');
                $ageModal.fadeIn();
                return false;
            }

        });
    });

    $ageSubmit.on('click',function(){
        const isAgeVerified = $ageSelect.val()>= 13;

        registerAgeVerificationResult(isAgeVerified).done(function(){
            if (isAgeVerified){
                
            }
        })

    });

    function registerAgeVerificationResult(isAgeVerified){
        return $.post('/verify', {is_age_verified:Number(isAgeVerified)});
    }
</script>
@endpush