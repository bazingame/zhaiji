<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function addUser(Request $request)
    {
        if (!$request->has("open_id") || !$request->has("phone") ||!$request->has("headimg_url")) {
            self::setResponse(null,400,-4001);
        }


    }

    public function reviseUser(Request $request){

    }

    public function deleteUSer(Request $request){

    }
}
