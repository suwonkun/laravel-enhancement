<?php

namespace App\Http\Controllers;

use App\Models\CsvExportHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function postCSV(Request $request)
    {
        $fileName = Carbon::now()->format('YmdHis') . '_userList.csv';
        $filePath = storage_path('app/csv/' . $fileName);

        $stream = fopen($filePath, 'w');
        $head = ['ユーザー名', '会社名', '部署名'];
        fputcsv($stream, $head);

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
            ->get();

        foreach ($users as $user) {
            $data = [
                $user->name,
                $user->company->name,
                $user->sections->implode('name', ',')
            ];
            fputcsv($stream, $data);
        }

        fclose($stream);

        CsvExportHistory::create([
            'user_id' => $request->user()->id,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'user',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return response()->download($filePath, $fileName, ['Content-Type' => 'text/csv']);
    }
}
