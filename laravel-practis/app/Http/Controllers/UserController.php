<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, $request->user()->company]);

        $users = User::query()
            ->with(['company', 'sections'])
            ->whenIsNotAdmin($request)
            ->when($request->has('search_company'), function ($query) use ($request) {
                return $query->searchCompany($request);
            })
            ->when($request->has('search_section'), function ($query) use ($request) {
                return $query->searchSection($request);
            })
            ->when($request->has('search_user'), function ($query) use ($request) {
                return $query->searchUser($request);
            })
            ->simplePaginate()
            ->withQueryString();

        return view('users.index', compact('users'));
    }
}
