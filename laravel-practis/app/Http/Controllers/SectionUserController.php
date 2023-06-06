<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionUserRequest;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SectionUserController extends Controller
{
    public function store(StoreSectionUserRequest $request, Section $section): RedirectResponse
    {
        $section->users()->attach($request->user_id);

        $company = $section->company;

        return redirect()->route('companies.sections.show', compact('company', 'section'));
    }

    public function destroy(Section $section, User $user): RedirectResponse
    {
        $section->users()->detach($user->id);

        $company = $section->company;

        return redirect()->route('companies.sections.show', compact('company', 'section'));
    }

    public function download(Request $request)
    {
        $fileName = Carbon::now()->format('YmdHis') . '_sectionList.csv';
        $filePath = storage_path('app/csv/' . $fileName);

        $stream = fopen($filePath, 'w');
        $head = ['部署ID', '部署名', '作成日', '更新日'];
        fputcsv($stream, $head);

        $sections = $request->user()->company->sections;

        foreach ($sections as $section) {
            $data = [
                $section->id,
                $section->name,
                $section->created_at,
                $section->updated_at,
            ];
            fputcsv($stream, $data);
        }

        fclose($stream);

        return response()->download($filePath, $fileName, ['Content-Type' => 'text/csv']);
    }
}
