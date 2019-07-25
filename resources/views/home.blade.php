@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if (Auth::check())
                <app :total="{{ $total }}" :last-total="{{ $lastTotal }}"></app>
           @else
                <h3>
                    Sorry, you don't have permissions to access this page!
                </h3>
            @endif
        </div>
    </div>
</div>
@endsection
