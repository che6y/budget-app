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
}
