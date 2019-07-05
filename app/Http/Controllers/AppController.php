<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function index()
    {
        $total = Purchase::sum(DB::raw('cost * amount'));
        return view('/home', [ 'total' => $total ]);
    }

    public function getData()
    {
        $data = DB::table('purchases')
            ->select(DB::raw('categories.title, SUM(purchases.amount * purchases.cost) as cost'))
            ->leftJoin('categories', 'purchases.category_id', '=', 'categories.id')
            ->groupBy('purchases.category_id')
            ->get();
        return json_encode($data);
    }
}
