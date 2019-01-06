<?php

namespace App\Http\Controllers;

use App\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = Carbon::now('+7');
        $purchases = Purchase::where('created_at', '<', $date)->get();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchases.create' );
    }

    /**
     * Store a newly created purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate( [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'amount' =>'numeric',
            'cost' =>'required|numeric'
        ]);

        Purchase::create( request( ['title', 'description', 'cost', 'amount', 'user']) );
        return redirect('purchases' );
    }

    /**
     * Display the specified purchase.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified purchase.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        return view('purchases.edit', compact('purchase') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Purchase $purchase )
    {
        $purchase->update( request(['title', 'description', 'cost', 'amount']));
        return redirect('purchases' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect('purchases' );
    }
}
