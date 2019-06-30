@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <?php if (Auth::check()): ?>
                <app :total="{{ $total }}"></app>
           <?php endif; ?>
        </div>
    </div>
</div>
@endsection
