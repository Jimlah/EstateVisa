<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
use Database\Seeders\AdminSeeder;
use Illuminate\Http\UploadedFile;
use App\Exports\EstateAdminExport;
use App\Imports\EstateAdminImport;
use Database\Seeders\EstateSeeder;
use Maatwebsite\Excel\Facades\Excel;
use Database\Seeders\EstateAdminSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;

class EstateAdminTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            EstateSeeder::class,
            EstateAdminSeeder::class
        ]);
    }


    public function test_api_estate_super_admin_can_view_all_admin()
    {
        $user = Estate::all()->random()->owner->user;

        $response = $this->actingAs($user, 'api')
            ->getJson(route('estate-admins.index'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function (AssertableJson $json) {
                    $json->has('data', function (AssertableJson $json) {
                        $json->first(function (AssertableJson $json) {
                            $json->has('id')
                                ->etc();
                        })
                            ->etc();
                    })->etc();
                })->etc();
            });
    }

    public function test_api_estate_super_admin_can_create_new_admin_for_is_estate()
    {
        $user = Estate::all()->random()->owner->user;

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs($user, 'api')
            ->postJson(route('estate-admins.store'), $attributes);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });
    }


    public function test_api_estate_super_admin_can_get_a_single_admin_for_his_estate()
    {
        $user = Estate::all()->random()->owner->user;

        $admin = $user->estate->random()->admins->random();

        $response = $this->actingAs($user, 'api')
            ->getJson(route('estate-admins.show', $admin->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }

    public function test_api_estate_super_admin_can_update_a_single_admin_for_his_estate()
    {

        $user = Estate::all()->random()->owner->user;

        $admin = $user->estate->random()->admins->random();

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs($user, 'api')
            ->putJson(route('estate-admins.update', $admin->id), $attributes);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('users', [
            'email' => $attributes['email']
        ]);
    }

    public function test_api_estate_super_admin_can_delete_a_single_admin_for_his_estate()
    {
        $user = Estate::all()->random()->owner->user;

        $admin = $user->estate->random()->admins->random();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('estate-admins.destroy', $admin->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseMissing('estate_admins', [
            'id' => $admin->id
        ]);
    }

    public function test_api_estate_super_admin_can_deactivate()
    {
        $user = Estate::all()->random()->owner->user;

        $admin = $user->estate->random()->admins->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estate-admins.deactivate', $admin->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'id' => $admin->id,
            'status' => User::DEACTIVATED
        ]);
    }

    public function test_api_estate_super_admin_can_activate()
    {
        $user = Estate::all()->random()->owner->user;

        $admin = $user->estate->random()->admins->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estate-admins.activate', $admin->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'id' => $admin->id,
            'status' => User::ACTIVE
        ]);
    }

    public function test_api_estate_super_admin_can_suspend()
    {

        $user = Estate::all()->random()->owner->user;

        $admin = $user->estate->random()->admins->random();

        $response = $this->actingAs($user, 'api')
            ->patchJson(route('estate-admins.suspend', $admin->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'id' => $admin->id,
            'status' => User::SUSPENDED
        ]);
    }

    public function test_api_estate_super_admin_can_export()
    {
        $user = Estate::all()->random()->owner->user;

        $admin = $user->estate->random()->admins->random();

        Excel::fake();

        $response = $this->actingAs($user, 'api')
            ->getJson(route('estate-admins.export', $admin->id));

        $response->assertStatus(200);
        Excel::assertStored('laravel-excel/estateAdmins.xlsx', function (EstateAdminExport $export) {
            return true;
        });
    }

    public function test_api_estate_super_admin_can_import()
    {

        $user = Estate::all()->random()->owner->user;
        $admin = $user->estate->random()->admins->random();

        Excel::fake();
        Storage::fake('excel');

        // $uploadedFile = new UploadedFile(Storage::path('test\estateAdmins.xlsx'), 'estateAdmins.xlsx', null, null, true);
        $uploadedFile = UploadedFile::fake()->create('estateAdmins.xlsx');

        $response = $this->actingAs($user, 'api')
            ->postJson(route('estate-admins.import', $admin->id), ['file' => $uploadedFile]);

        $response->assertStatus(200);
        Excel::assertQueued($uploadedFile->getPath(), function (EstateAdminImport $import) {
            return true;
        });
    }
}
