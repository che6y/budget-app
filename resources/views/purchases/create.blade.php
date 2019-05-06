@extends('layouts.app')

@section('content')
    <form action="/purchases" method="POST">
        @csrf
        <input type="text" name="title" placeholder="title">
        <input type="text" name="description" placeholder="Description">
        <input type="number" name="amount" value="1" min="1" max="100" step="1">
        <input type="number" name="cost" placeholder="10">
        <input type="hidden" name="user" value="1" >
        <button type="submit">Submit</button>
    </form>
    <div><a href="javascript:history.back()" class="back-button">&#8592;Back</a></div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection