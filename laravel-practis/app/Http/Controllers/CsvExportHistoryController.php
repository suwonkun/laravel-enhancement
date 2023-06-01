<?php

namespace App\Http\Controllers;

use App\Models\CsvExportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CsvExportHistoryController extends Controller
{
    public function index(Request $request)
    {
        $csvExportHistories = CsvExportHistory::where('user_id', $request->user()->id)->get();

        return view('csvExportHistories.index', compact('csvExportHistories'));
    }

    public function download(CsvExportHistory $csvExportHistory)
    {

        $filePath = $csvExportHistory->file_path;
        if (file_exists($filePath)) {
            return response()->download($filePath, $csvExportHistory->file_name);
        }

        throw new \Exception('ファイルが存在しません。');
    }
}
