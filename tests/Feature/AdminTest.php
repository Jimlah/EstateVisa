<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Profile;
use App\Mail\UserCreated;
use App\Exports\AdminExport;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Lanin\Laravel\ApiDebugger\Debugger;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_admins()
    {
        User::factory()->create();

        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $response = $this->actingAs($user, 'api')
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
        User::factory()->create();
        $user = User::first();

        $data = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs($user, 'api')
            ->postJson(route('admins.store'), $data);

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status')->etc();
                }
            );

        Mail::assertQueued(UserCreated::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
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
        User::factory()->create();
        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $response = $this->actingAs($user, 'api')
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
        User::factory()->create();
        $user = User::first();

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

        $response = $this->actingAs($user, 'api')
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
        User::factory()->create();
        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs($user, 'api')
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
        User::factory()->create();
        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::SUSPENDED]));
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs($user, 'api')
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
        User::factory()->create();
        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::ACTIVE]));
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs($user, 'api')
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
        User::factory()->create();
        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::ACTIVE]));
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs($user, 'api')
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
        User::factory()->create();
        $user = User::first();

        Excel::fake();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker->numberBetween(1, Admin::count()));

        $response = $this->actingAs($user, 'api')
            ->getJson(route('admins.export'));

        $response->dump();

        $response->assertStatus(200);
        Excel::assertDownloaded('admins.xlsx', function (AdminExport $export) use ($admin) {
            return $export->collection->first()->id === $admin->id;
        });
    }
}
