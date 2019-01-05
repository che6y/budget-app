@extends('layouts.app')

@section('content')
    <form action="/purchases/{{ $purchase->id }}" method="POST">
        @method('PUT')
        @csrf
        <input type="text" name="title" placeholder="title" value="{{ $purchase->title }}" />
        <input type="text" name="description" placeholder="Description" value="{{ $purchase->description }}" />
        <input type="number" name="amount" value="1" min="1" max="100" step="1" value="{{ $purchase->amount }}" />
        <input type="number" name="cost" placeholder="10" value="{{ $purchase->cost }}" />
        <button type="submit">Submit</button>
    </form>
    <div><a href="javascript:history.back()" class="back-button">&#8592;Back</a></div>
@endsection