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
use Database\Seeders\EstateAdminSeeder;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstateTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_estate()
    {
        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('estates.index'));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->has('data')->etc());
    }

    public function test_api_admin_can_get_all_estate()
    {
        $response = $this->actingAs(static::$admin, 'api')
            ->getJson(route('estates.index'));

        $response->assertStatus(200);
    }

    public function test_api_super_admin_can_create_an_estate()
    {
        $data = array_merge(
            Estate::factory()->make()->toArray(),
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $response = $this->actingAs(static::$superAdmin, 'api')
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
            'role' => User::ESTATE_SUPER_ADMIN,
        ]);
    }

    public function test_api_admin_can_create_an_estate()
    {
        $data = array_merge(
            Estate::factory()->make()->toArray(),
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $response = $this->actingAs(static::$admin, 'api')
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
            'role' => User::ESTATE_SUPER_ADMIN,
        ]);

        $this->assertDatabaseHas('estates', [
            'name' => $data['name'],
            'code' => $data['code'],
            'address' => $data['address'],
        ]);
    }

    public function test_api_super_admin_can_update_an_estate()
    {
        $data = array_merge(
            Estate::factory()->make()->toArray(),
        );

        $estate = Estate::find($this->faker()->numberBetween(1, Estate::all()->count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
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

        $estate = Estate::find($this->faker()->numberBetween(1, Estate::all()->count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
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
        $estate = Estate::find($this->faker()->numberBetween(1, Estate::all()->count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
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
        $estate = EstateAdmin::find($this->faker()->numberBetween(1, EstateAdmin::all()->count()));

        $id = $estate->estate_id;

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('estates.deactivate', $id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $id,
            'status' => User::DEACTIVATED,
        ]);
    }

    public function test_api_super_admin_can_activate()
    {
        $estate = EstateAdmin::find($this->faker()->numberBetween(1, EstateAdmin::all()->count()));

        $id = $estate->estate_id;

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('estates.activate', $id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $id,
            'status' => User::ACTIVE,
        ]);
    }

    public function test_api_super_admin_can_suspend()
    {
        $estate = EstateAdmin::find($this->faker()->numberBetween(1, EstateAdmin::all()->count()));

        $id = $estate->estate_id;

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('estates.suspend', $id));

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message')->etc());

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $id,
            'status' => User::SUSPENDED,
        ]);
    }

    public function test_api_admin_can_deactivate()
    {
        $estate = EstateAdmin::find($this->faker()->numberBetween(1, EstateAdmin::all()->count()));

        $id = $estate->estate_id;

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('estates.deactivate', $id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $id,
            'status' => User::DEACTIVATED,
        ]);
    }

    public function test_api_admin_can_activate()
    {
        $estate = EstateAdmin::find($this->faker()->numberBetween(1, EstateAdmin::all()->count()));

        $id = $estate->estate_id;

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('estates.activate', $id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')->has('message')->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $id,
            'status' => User::ACTIVE,
        ]);
    }

    public function test_api_admin_can_suspend()
    {
        $estate = EstateAdmin::find($this->faker()->numberBetween(1, EstateAdmin::all()->count()));

        $id = $estate->estate_id;

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('estates.suspend', $id));

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message')->etc());

        $this->assertDatabaseHas('estate_admins', [
            'estate_id' => $id,
            'status' => User::SUSPENDED,
        ]);
    }

    public function test_api_super_admin_can_export()
    {
        Excel::fake();
        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('estates.export'));

        $response->assertStatus(200);
        Excel::assertStored('laravel-excel/estates.xlsx', function (EstateExport $export) {
            return true;
        });
    }

    public function test_api_admin_can_export()
    {
        Excel::fake();
        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('estates.export'));

        $response->assertStatus(200);
        Excel::assertStored('laravel-excel/estates.xlsx', function (EstateExport $export) {
            return true;
        });
    }

    public function test_api_super_admin_can_import()
    {
        Excel::fake();

        $uploadedFile = new UploadedFile(Storage::path('test\estates.xlsx'), 'estates.xlsx', null, null, true);

        $response = $this->actingAs(static::$superAdmin, 'api')
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
        $estate = Estate::find($this->faker()->numberBetween(1, Estate::all()->count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('estates.show', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($estate) {
                $json->has('data')->etc();
            });
    }

    public function test_api_admin_can_view_an_estate()
    {
        $estate = Estate::find($this->faker()->numberBetween(1, Estate::all()->count()));

        $response = $this->actingAs(static::$admin, 'api')
            ->getJson(route('estates.show', $estate->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($estate) {
                $json->has('data')->etc();
            });
    }
}
