<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Profile;
use App\Mail\UserCreated;
use App\Exports\AdminExport;
use App\Imports\AdminImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Lanin\Laravel\ApiDebugger\Debugger;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_admins()
    {
        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('admins.index'));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('data')->etc();
                }
            );
    }


    public function test_super_admin_can_create_admin()
    {
        Mail::fake();

        $data = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->postJson(route('admins.store'), $data);

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status')->etc();
                }
            );

        Mail::assertQueued(UserCreated::class, function ($mail) use ($data) {
            return $mail->hasTo($data['email']);
        });

        $this->assertDatabaseHas('profiles', [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number']
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);

        $this->assertDatabaseHas('admins', [
            'user_id' => User::where('email', $data['email'])->first()->id,
            'status' => User::ACTIVE
        ]);
    }


    public function test_api_super_admin_can_get_a_single_admin()
    {

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('admins.show', 1));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('data')->etc();
                }
            );
    }

    public function test_api_super_admin_can_update_admin()
    {

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $data = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->putJson(route('admins.update', $admin->id), $data);

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status')->etc();
                }
            );

        $this->assertDatabaseHas('profiles', [
            'user_id' => $admin->user_id,
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number']
        ]);
    }

    public function test_api_super_admin_can_delete_an_admin()
    {

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->deleteJson(route('admins.destroy', $admin->id));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status')->etc();
                }
            );

        $this->assertDatabaseMissing('admins', [
            'id' => $admin->id
        ]);

        $this->assertDatabaseMissing('profiles', [
            'user_id' => $admin->user_id
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $admin->user_id
        ]);
    }

    public function test_api_super_admin_can_enable_an_admin()
    {

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::SUSPENDED]));
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('admins.activate', $admin->id));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status')->etc();
                }
            );

        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'status' => User::ACTIVE
        ]);
    }


    public function test_api_super_admin_can_deactivate_an_admin()
    {

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::ACTIVE]));
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('admins.deactivate', $admin->id));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status')->etc();
                }
            );

        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'status' => User::DEACTIVATED
        ]);
    }

    public function test_api_super_admin_can_suspend_an_admin()
    {

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::ACTIVE]));
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->patchJson(route('admins.suspend', $admin->id));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status')->etc();
                }
            );

        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'status' => User::SUSPENDED
        ]);
    }

    public function test_api_super_admin_can_export_admins()
    {

        Excel::fake();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('admins.export'));

        $response->assertStatus(200);
        Excel::assertStored('laravel-excel/admins.xlsx', 'local', function (AdminExport $export) use ($admin) {
            return true;
        });
    }


    public function test_api_super_admin_can_import_admins()
    {

        Excel::fake();

        $uploadedFile = new UploadedFile(Storage::path('test\admins.xlsx'), 'admins.xlsx', null, null, true);


        $response = $this->actingAs(static::$superAdmin, 'api')
            ->postJson(route('admins.import'), [
                'file' => $uploadedFile
            ]);

        $response->assertStatus(200);

        Excel::assertQueued('admins.xlsx', function (AdminImport $import) {
            return true;
        });
    }
}
