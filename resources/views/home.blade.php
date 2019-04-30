@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if (Auth::check()): ?>
                <app></app>
           <?php endif; ?>
        </div>
    </div>
</div>
@endsection
