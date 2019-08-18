<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;
use Illuminate\Support\Facades\Log;


class OptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display all options.
     *
     */
    public function index()
    {
        $options = Option::orderBy('id', 'desc')->get();

        return json_encode( $options );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function find( Request $request )
    {
        $keys = explode(',', $request->keys);
        $options = Option::whereIn('key', $keys)
            ->get();

        return json_encode( [ 'data' => $options ] );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return view
     */
    public function update( Option $option, Request $request )
    {

        $data = $request->validate([
            'value'  => 'required|min:1|max:50'
        ]);
        $result = $option->update( $data );
        $message = !empty($result) ? 'Option updated' : 'Something went wrong';

        return json_encode( ['message' => $message ] );
    }
}
