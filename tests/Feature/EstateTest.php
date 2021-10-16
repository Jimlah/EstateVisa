<?php

namespace Tests\Feature;

use tidy;
use Tests\TestCase;
use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
use App\Models\EstateAdmin;
use App\Exports\EstateExport;
use App\Imports\EstateImport;
use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Database\Seeders\EstateAdminSeeder;
use Database\Seeders\EstateSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstateTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            AdminSeeder::class,
            EstateSeeder::class
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_estate()
    {
        $user = Admin::first()->user;
        $response = $this->actingAs($user, 'api')
            ->getJson(route('estates.index'));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->has('data')->etc());
    }

    public function test_api_admin_can_get_all_estate()
    {
        $user = Admin::all()->skip(1)->random()->user;
        $response = $this->actingAs($user, 'api')
            ->getJson(route('estates.index'));

        $response->assertStatus(200);
    }

    public function test_api_super_admin_can_create_an_estate()
    {
        $user = Admin::first()->user;
        $data = array_merge(
            Estate::factory()->make()->toArray(),
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $response = $this->actingAs($user, 'api')
            ->postJson(route('estates.store'), $data);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);

        $this->assertDatabaseHas('profiles', [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'phone_number' => $data['phone_number'],
        ]);

        $this->assertDatabaseHas('estate_admins', [
            'user_id' => User::all()->last()->id,
            'is_owner' => User::ESTATE_SUPER_ADMIN,
        ]);
    }

    public function test_api_admin_can_create_an_estate()
    {
        $user = Admin::all()->skip(1)->random()->user;
        $data = array_merge(
            Estate::factory()->make()->toArray(),
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $response = $this->actingAs($user, 'api')
            ->postJson(route('estates.store'), $data);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);

        $this->assertDatabaseHas('profiles', [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'phone_number' => $data['phone_number'],
        ]);

        $this->assertDatabaseHas('estate_admins', [
            'user_id' => User::all()->last()->id,
            'is_owner' => User::ESTATE_SUPER_ADMIN,
        ]);

        $this->assertDatabaseHas('estates', [
            'name' => $data['name'],
            'code' => $data['code'],
            'address' => $data['address'],
        ]);
    }

    public function test_api_super_admin_can_update_an_estate()
    {
        $user = Admin::first()->user;

        $data = array_merge(
            Estate::factory()->make()->toArray(),
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->putJson(route('estates.update', $estate->id), $data);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estates', [
            'name' => $data['name'],
            'code' => $data['code'],
            'address' => $data['address'],
        ]);
    }

    public function test_api_super_admin_can_delete_estates()
    {
        $user = Admin::first()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('estates.destroy', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseMissing('estates', [
            'id' => $estate->id,
        ]);
    }

    public function test_api_admin_can_delete_estates()
    {
        $user = Admin::all()->skip(1)->random()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('estates.destroy', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseMissing('estates', [
            'id' => $estate->id,
        ]);
    }

    public function test_api_super_admin_can_deactivate()
    {
        $user = Admin::first()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estates.deactivate', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $estate->id,
            'status' => User::DEACTIVATED,
        ]);
    }

    public function test_api_super_admin_can_activate()
    {
        $user = Admin::first()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estates.activate', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $estate->id,
            'status' => User::ACTIVE,
        ]);
    }

    public function test_api_super_admin_can_suspend()
    {
        $user = Admin::first()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estates.suspend', $estate->id));

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message')->etc());

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $estate->id,
            'status' => User::SUSPENDED,
        ]);
    }

    public function test_api_admin_can_deactivate()
    {
        $user = Admin::all()->skip(1)->random()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estates.deactivate', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $estate->id,
            'status' => User::DEACTIVATED,
        ]);
    }

    public function test_api_admin_can_activate()
    {
        $user = Admin::all()->skip(1)->random()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estates.activate', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $estate->id,
            'status' => User::ACTIVE,
        ]);
    }

    public function test_api_admin_can_suspend()
    {
        $user = Admin::all()->skip(1)->random()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estates.suspend', $estate->id));

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message')->etc());

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $estate->id,
            'status' => User::SUSPENDED,
        ]);
    }

    public function test_api_super_admin_can_export()
    {
        $user = Admin::first()->user;
        Excel::fake();
        $response = $this->actingAs($user, 'api')
            ->getJson(route('estates.export'));

        $response->assertStatus(200);
        Excel::assertStored('laravel-excel/estates.xlsx', function (EstateExport $export) {
            return true;
        });
    }

    public function test_api_admin_can_export()
    {
        $user = Admin::all()->skip(1)->random()->user;
        Excel::fake();
        $response = $this->actingAs($user, 'api')
            ->getJson(route('estates.export'));

        $response->assertStatus(200);
        Excel::assertStored('laravel-excel/estates.xlsx', function (EstateExport $export) {
            return true;
        });
    }

    public function test_api_super_admin_can_import()
    {
        $user = Admin::first()->user;
        Excel::fake();
        Storage::fake('excel');

        // $uploadedFile = new UploadedFile(Storage::path('test\estates.xlsx'), 'estates.xlsx', null, null, true);
        $uploadedFile = UploadedFile::fake()->create('estates.xlsx');

        $response = $this->actingAs($user, 'api')
            ->postJson(route('estates.import'), [
                'file' => $uploadedFile,
            ]);

        $response->assertStatus(200);
        Excel::assertQueued($uploadedFile->getPath(), function (EstateImport $import) {
            return true;
        });
    }

    public function test_api_super_admin_can_view_an_estate()
    {
        $user = Admin::first()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->getJson(route('estates.show', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($estate) {
                $json->has('data')->etc();
            });
    }

    public function test_api_admin_can_view_an_estate()
    {
        $user = Admin::all()->skip(1)->random()->user;
        $estate = Estate::all()->random();

        $response = $this->actingAs($user, 'api')
            ->getJson(route('estates.show', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($estate) {
                $json->has('data')->etc();
            });
    }
}
