<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if($request->user()->role === 'admin'){
            $users = User::query()
                ->with(['company', 'sections'])
                ->seachCompany($request)
                ->searchSection($request)
                ->simplePaginate()
                ->withQueryString();

        return view('users.index', compact('users'));
        }else{

        }
    }
}
