<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\CsvExportHistory;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $companies = Company::query()
            ->paginate()
            ->withQueryString();

        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $company = new Company();
        $company->fill($request->validated())
            ->save();

        return redirect()->route('companies.show', compact('company'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): View
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company): View
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $company->fill($request->validated())
            ->save();

        return redirect()->route('companies.show', compact('company'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('companies.index');
    }

    public function export(Request $request)
    {
        $fileName = Carbon::now()->format('YmdHis') . '_companyList.csv';
        $filePath = storage_path('app/csv/' . $fileName);

        $stream = fopen($filePath, 'w');
        $head = ['ユーザー名', '会社名', '部署名'];
        fputcsv($stream, $head);

        $companies = Company::all();

        foreach ($companies as $company) {
            $data = [
                $company->id,
                $company->name,
                $company->sections_count,
                $company->created_at,
                $company->updated_at,
            ];
            fputcsv($stream, $data);
        }

        CsvExportHistory::create([
            'user_id' => $request->user()->id,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'csv_type' => 'company',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        fclose($stream);

        return response()->download($filePath, $fileName, ['Content-Type' => 'text/csv']);
    }
}
