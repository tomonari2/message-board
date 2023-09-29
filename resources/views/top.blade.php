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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
