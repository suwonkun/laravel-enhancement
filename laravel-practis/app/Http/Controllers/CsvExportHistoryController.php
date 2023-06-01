<?php

namespace App\Http\Controllers;

use App\Models\CsvExportHistory;
use Illuminate\Http\Request;

class CsvExportHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = CsvExportHistory::where('user_id', $request->user()->id)->get();

        return view('csvExportHistories.index', compact('histories'));
    }
}
