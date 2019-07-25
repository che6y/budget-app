@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <form class="row" action="/account/settings" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group col-md-6">
                        <label for="title">User name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ $user->name }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="title">Email</label>
                        <input id="email" name="email" type="text" class="form-control" value="{{ $user->email }}">
                    </div>
                    <div class="form-group col-md-2">
                        <button class="btn btn-outline-primary" type="submit">Save changes</button>
                    </div>
                </form>
                <form class="row" action="/account/token" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group col-md-12">
                        <input id="name" name="api_token" type="text" class="form-control" value="{{ !empty($token) ? $token : '' }}">
                    </div>
                    <div class="form-group col-md-12">
                        <button class="btn btn-outline-primary" type="submit">Generate new token</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection