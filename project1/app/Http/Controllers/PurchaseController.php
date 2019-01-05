<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use \App\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        $date = Carbon::now('+7');
        $purchases = Purchase::where('created_at', '<', $date)->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        return view('purchases.create' );
    }

    public function edit( $id )
    {
        $purchase = Purchase::find( $id );

        return view('purchases.edit', compact('purchase') );
    }

    public function store(Request $request)
    {
        $purchase = new Purchase;
        $purchase->title = $request->input('title');
        $purchase->description = $request->input('description');
        $purchase->amount = intval($request->input('amount'));
        $purchase->cost = intval($request->input('cost'));
        $purchase->user = 1;
        $purchase->save();

        return redirect('purchases' );
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::find( $id );
        $purchase->title = $request->input('title');
        $purchase->description = $request->input('description');
        $purchase->amount = intval($request->input('amount'));
        $purchase->cost = intval($request->input('cost'));
        $purchase->user = 1;
        $purchase->save();

        return redirect('purchases' );
    }
    public function destroy( $id )
    {
        $purchase = Purchase::find( $id );

        $purchase->delete();

        return redirect('purchases' );
    }
}
