<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function index()
    {
        $total = Purchase::sum(DB::raw('cost * amount'));
//        $weeksTotal = DB::table('purchases')
//            ->whereBetween('created_at', [date('Y-m-d') , date('Y-m-d', strtotime('-1 week'))])
//            ->sum(DB::raw('cost * amount'));
        return view('/home', [
            'total' => $total,
//            'weeksTotal' => $weeksTotal
        ]);
    }

    public function getData()
    {
        $data = DB::table('purchases')
            ->select(DB::raw('categories.title, SUM(purchases.amount * purchases.cost) as cost'))
            ->leftJoin('categories', 'purchases.category_id', '=', 'categories.id')
            ->groupBy('purchases.category_id')
            ->orderBy('cost', 'desc')
            ->get();
        return json_encode($data);
    }
}
