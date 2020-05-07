<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Pool;


class PoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function list(Request $request)
    {
        return Pool::getMyList()->toArray();
    }
}
