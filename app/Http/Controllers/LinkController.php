<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Http\Request;

class LinkController extends Controller
{
    public function getShortLink(Request $request)
    {
        dd($request);
    }
}
