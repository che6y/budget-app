@extends('layouts.app')

@section('content')
    <div>
        <a href="/purchases/create">Create</a>
    </div>
    <ul>
        @foreach($purchases as $purchase)
            <li class="purchase-item">
                <div class="purchase-item_text">
                    {{ $purchase->title }} {{ $purchase->cost * $purchase->amount}} baht
                </div>
                <a class="action-link" href="/purchases/{{ $purchase->id }}/edit">Edit</a>
                <form action="/purchases/{{ $purchase->id }}" method="POST" class="action-form">
                    @method('DELETE')
                    @csrf
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>

@endsection