<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseCollection;
use App\Http\Resources\Purchase as PurchaseResource;
use App\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     */
    public function index()
    {
        $purchases = Purchase::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return new PurchaseCollection($purchases);
    }

    /**
     * Store a newly created purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PurchaseResource
     */
    public function store( Request $request )
    {
        $data = $request->validate([
            'user_id'     => 'required|numeric',
            'title'       => 'required|min:3|max:255',
            'amount'      => 'required|numeric',
            'cost'        => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        $purchase = Purchase::create( $data );
        return new PurchaseResource($purchase);
    }

    /**
     * Display the specified purchase.
     *
     * @param  Purchase $purchase
     * @return PurchaseResource
     */
    public function show(Purchase $purchase)
    {
        return new PurchaseResource($purchase);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return PurchaseResource
     */
    public function update( Purchase $purchase, Request $request )
    {
        $data = $request->validate([
            'title'       => 'required|min:3|max:255',
            'amount'      => 'numeric',
            'cost'        => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        $purchase->update($data);

        return new PurchaseResource($purchase);

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

        return response(null, 204);
    }
}
