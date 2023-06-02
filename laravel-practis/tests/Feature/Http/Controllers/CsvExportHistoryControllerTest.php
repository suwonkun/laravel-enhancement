<?php

namespace Tests\Unit;

use App\Http\Controllers\CsvExportHistoryController;
use App\Models\Company;
use App\Models\CsvExportHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;
use Database\Factories\CsvExportHistoryFactory;

class CsvExportHistoryControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create();
        $this->user = User::factory([
            'company_id' => $this->company->id,
        ])->create();
    }

    public function testIndex()
    {
        $response = $this->actingAs($this->user)->get(route('csvExportHistories.index'));

        $response->assertStatus(200);
    }

    public function testDownload()
    {
        $response = $this->actingAs($this->user)->post(route('users.CSV', ['search' => $this->company->name, 'search_company' => true]));

        $response->assertStatus(200);
        $response->assertDownload();

        $csvExportHistory = $this->user->csvExportHistories()->latest()->first();

        $response = $this->actingAs($this->user)->get(route('csv-export-history.download', ['csv_export_history' => $csvExportHistory]));

        $response->assertStatus(200);

        $otherCsvExportHistory = CsvExportHistory::factory()->create();

        $response = $this->actingAs($this->user)->get(route('csv-export-history.download', ['csv_export_history' => $otherCsvExportHistory]));

        $response->assertStatus(500);
    }
}
