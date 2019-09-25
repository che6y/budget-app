<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\MonthlySpending;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{

    public function index()
    {
        // Getting this month total - from 25th of this/last month till tomorrow (I'm using $tomorrow to include today's purchases)
        $tomorrow = new \DateTime('+1 day');
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

        // This month total
        $total = Purchase::whereBetween('created_at', [$date_from, $tomorrow->format('Y-m-d')])
                        ->sum(DB::raw('cost * amount'));

        // Each month spendings (this year)
        $monthlySpendings = MonthlySpending::select('month','amount')
                                            ->where('year', date('Y'))
                                            ->orderBy('month', 'asc')
                                            ->get();
        for ($i = 0; $i < count( $monthlySpendings ); ++$i) {
            $num       = $monthlySpendings[$i]->month;
            $dateObj   = \DateTime::createFromFormat('!m', $num);

            $monthlySpendings[$i]->month = $dateObj->format('F');
            $monthlySpendings[$i]->saved = 117000 - $monthlySpendings[$i]->amount;
        }

        return view('/home', [
            'total' => $total,
            'monthly_spendings' => $monthlySpendings
        ]);
    }
}
