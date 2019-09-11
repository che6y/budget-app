<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseCollection;
use App\Http\Resources\Purchase as PurchaseResource;
use App\ScheduledPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduledPurchaseController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth:api');
//    }

    /**
     * Display a listing of the purchases.
     *
     */
    public function index()
    {
        $purchases = ScheduledPurchase::orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return new PurchaseCollection($purchases);
    }

    /**
     * Store a newly created scheduled purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PurchaseResource
     */
    public function store( Request $request )
    {
        $data = $request->validate([
            'user_id'     => 'required|numeric',
            'title'       => 'required|min:3|max:255',
            'cost'        => 'required|numeric',
            'category_id' => 'required|numeric',
            'is_income'   => 'numeric',
        ]);

        $purchase = ScheduledPurchase::create( $data );
        $category = $purchase->category;
        $usage    = $category->usage;
        $category->update( [ 'usage' => ++$usage ] );

        return new PurchaseResource($purchase);
    }

    /**
     * Update the specified scheduled purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return PurchaseResource
     */
    public function update( ScheduledPurchase $purchase, Request $request )
    {
        $data = $request->validate([
            'title'       => 'required|min:3|max:255',
            'cost'        => 'required|numeric',
            'category_id' => 'required|numeric',
            'created_at'  => 'required|date',
            'is_income'   => 'numeric'
        ]);

        $purchase->update($data);

        return new PurchaseResource($purchase);
    }

    /**
     * Remove the specified scheduled purchase from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( ScheduledPurchase $purchase )
    {
        $purchase->delete();

        return response(null, 204);
    }
}

