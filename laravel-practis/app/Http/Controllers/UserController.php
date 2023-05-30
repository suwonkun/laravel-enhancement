<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny',User::class);

        $users = User::query()
            ->with(['company', 'sections'])
            ->whenIsNotAdmin($request)
            ->searchCompany($request)
            ->simplePaginate()
            ->withQueryString();

        return view('users.index', compact('users'));
    }
}
