<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\Company;
use App\Models\Section;
use App\Models\User;
use App\Models\CsvExportHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->companies = Company::factory()->count(10)->create();

        $this->company = $this->companies->first();

        $this->section = Section::factory([
            'company_id' => $this->company->id,
        ])->create();

        $this->user = User::factory([
            'company_id' => $this->company->id,
        ])->create();

        $this->admin = User::factory([
            'company_id' => $this->company->id,
            'role' => 'admin'
        ])->create();
    }

    public function testIndex()
    {
//      一般ユーザーは自分の会社しか見れない
        $otherCompany = Company::factory()->create();

        $response = $this->actingAs($this->user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee($this->company->name);
        $response->assertDontSee($otherCompany->name);

//　　　　管理者は自分の会社以外も閲覧できる
        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee($this->company->name);


//　　　　会社の検索
        $response = $this->actingAs($this->admin)->get(route('users.index', ['search' => $this->company->name, 'search_company' => true]));

        $response->assertStatus(200);
        $response->assertSee($this->company->name);

//      部署の検索
        $response = $this->actingAs($this->user)->get(route('users.index', ['search' => $this->section->name, 'search_section' => true]));

        $response->assertStatus(200);
        $response->assertSee($this->section->name);

//      ユーザー名で検索
        $response = $this->actingAs($this->user)->get(route('users.index', ['search' => $this->user->name, 'search_user' => true]));

        $response->assertStatus(200);
        $response->assertSee($this->user->name);
    }

    public function test_download()
    {
        $response = $this->actingAs($this->user)->post(route('users.CSV', ['search' => $this->company->name, 'search_company' => true]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $response = $this->actingAs($this->user)->post(route('users.CSV'), ['search' => $this->section->name, 'search_section' => true]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $response = $this->actingAs($this->user)->post(route('users.CSV'), ['search' => $this->user->name, 'search_user' => true]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

}
