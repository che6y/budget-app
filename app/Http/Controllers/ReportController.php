<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\Http\Resources\PurchaseCollection;
use App\Http\Resources\Purchase as PurchaseResource;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
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
        $totalCost = 0;
        $result    = array();
        $today     = new \DateTime('+1 day');
        $date_from = date(
            'Y-m-d',
            mktime(
                0,
                0,
                0,
                date('j') < 25 ? date('m') - 1 : date('m'),
                25,
                date('Y')
            )
        );
        $data = Purchase::select(DB::raw('categories.title, SUM(purchases.amount * purchases.cost) as cost'))
            ->whereBetween('purchases.created_at', [$date_from, $today->format('Y-m-d')])
            ->leftJoin('categories', 'purchases.category_id', '=', 'categories.id')
            ->groupBy('purchases.category_id')
            ->orderBy('cost', 'desc')
            ->get();

        foreach( $data as $k => $v ){
            $result[$v->title] = $v->cost;
            $totalCost        += $v->cost;
        }
        return array(
            'pieData' => $result,
            'dateFrom' => $date_from,
            'totalCost' => $totalCost
        );
    }

    /**
     * Display a listing of the purchases from specific period of time
     *
     */
    public function find( Request $request )
    {
        $totalCost    = 0;
        $dataResult   = array();
        $where_params = array();
        $data = $request->validate([
            'title'       => 'max:255',
            'date_from'   => 'required|max:10',
            'date_to'     => 'required|max:10',
            'category_id' => 'numeric',
        ]);

        if ( !empty($data['category_id']) )
            $where_params[] = ['category_id', '=', $data['category_id']];
        if ( !empty($data['title']) )
            $where_params[] = ['title', '=', $data['title']];

        $pieData = Purchase::select(DB::raw('categories.title, SUM(purchases.amount * purchases.cost) as cost'))
            ->where( $where_params )
            ->whereBetween('purchases.created_at', [$data['date_from'], $data['date_to']])
            ->leftJoin('categories', 'purchases.category_id', '=', 'categories.id')
            ->groupBy('purchases.category_id')
            ->orderBy('cost', 'desc')
            ->get();

        $purchases = Purchase::select('id', 'title', 'amount', 'cost', 'created_at', 'category_id')
            ->where( $where_params )
            ->whereBetween('created_at', [$data['date_from'], $data['date_to']])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach( $pieData as $k => $v ){
            $dataResult[$v->title] = $v->cost;
            $totalCost            += $v->cost;
        }

        $returnData = array(
            'purchases' => new PurchaseCollection( $purchases ),
            'totalCost' => $totalCost,
            'pieData'   => $dataResult
        );

        return $returnData;
    }
}
