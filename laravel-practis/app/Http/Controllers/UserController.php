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
            ->when($request->has('search_company'), function ($query) use ($request) {
                // searchCompanyメソッドを適用
                return $query->searchCompany($request);
            })
            ->when($request->has('search_section'), function ($query) use ($request) {
                // searchSectionメソッドを適用
                return $query->searchSection($request);
            })
            ->simplePaginate()
            ->withQueryString();

        return view('users.index', compact('users'));
    }
}
