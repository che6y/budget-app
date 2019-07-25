<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function index()
    {
        $today = new \DateTime('+1 day');
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
//        $date_from2 = date(
//            'Y-m-d',
//            mktime(
//                0,
//                0,
//                0,
//                date('j') < 25 ? date('m') - 2 : date('m')-1,
//                25,
//                date('Y')
//            )
//        );

        $total = Purchase::whereBetween('created_at', [$date_from, $today->format('Y-m-d')])
                        ->sum(DB::raw('cost * amount'));
//        $lastMonth = Purchase::whereBetween('created_at', [$date_from2, $date_from])
//                            ->sum(DB::raw('cost * amount'));
        return view('/home', [
            'total' => $total,
            'lastTotal' => '82702'
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
