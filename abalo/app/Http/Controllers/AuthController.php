<?php

namespace App\Http\Controllers;

use App\Models\AbUser;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Write static login information to the session.
 * Use for test purposes.
 */
class AuthController extends Controller
{
    public function login(Request $request) {
        $user =  AbUser::where('ab_mail', $request->get("email"))->first();
        if(empty($user) === false) {
            $request->session()->put('abalo_user', $user->ab_name);
            $request->session()->put('abalo_mail',  $user->ab_mail);
            $request->session()->put('abalo_time', time());
        } else {
            $request->session()->put('abalo_user', 'visitor');
            $request->session()->put('abalo_mail', 'visitor@abalo.example.com');
            $request->session()->put('abalo_time', time());
        }


        return redirect()->route('haslogin');
    }

    public function logout(Request $request) {
        $request->session()->flush();
        return redirect()->route('haslogin');
    }


    public function isLoggedIn(Request $request) {
        if($request->session()->has('abalo_user')) {
            $r["user"] = $request->session()->get('abalo_user');
            $r["time"] = $request->session()->get('abalo_time');
            $r["mail"] = $request->session()->get('abalo_mail');
            $r["auth"] = "true";
        }
        else $r["auth"]="false";
        return response()->json($r);
    }
}
