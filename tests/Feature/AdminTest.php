<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_user_can_get_admin()
    {
        Mail::fake();
        User::unsetEventDispatcher();

        User::factory()->create();
        $user = User::find(1);

        for ($i = 0; $i < 10; $i++) {

            $admin = User::factory()->create();
            Admin::factory()->create(['user_id' => $admin->id]);
            Profile::factory()->create(['user_id' => $admin->id]);
        }


        $this->actingAs($user, 'api');

        $response = $this->getJson(route('admins.index'));
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    public function test_api_super_admin_could_create_a_new_admin()
    {
        Mail::fake();
        User::unsetEventDispatcher();

        User::factory()->create();
        $user = User::find(1);

        $admin = User::factory()->make();
        $profile = Profile::factory()->make();

        $attribute = array_merge($admin->toArray(), $profile->toArray());

        $this->actingAs($user, 'api');
        $response = $this->postJson(route('admins.store'), $attribute);

        $response->assertStatus(200);
    }

    public function test_api_super_admin_can_get_a_single_admin()
    {
        User::factory()->create();
        $user = User::find(1);

        User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->admin()->save(Admin::factory()->make());
                $user->profile()->save(Profile::factory()->make());
            });

        $admin = Admin::find($this->faker()->numberBetween(1, Admin::count()));

        $this->actingAs($user, 'api');
        $response = $this->getJson(route('admins.show', $admin->id));
        $response->assertStatus(200);
    }

    public function test_api_admin_can_get_his_data()
    {
        User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->admin()->save(Admin::factory()->make());
                $user->profile()->save(Profile::factory()->make());
            });


        $admin = Admin::find($this->faker()->numberBetween(1, Admin::count()));

        $this->actingAs($admin->user, 'api');
        $response = $this->getJson(route('admins.show', $admin->id));
        $response->assertStatus(200);
    }

    public function test_api_super_admin_can_update_admin()
    {
        User::factory()->create();
        $user = User::find(1);

        User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->admin()->save(Admin::factory()->make());
                $user->profile()->save(Profile::factory()->make());
            });
        $admin = Admin::find($this->faker()->numberBetween(1, Admin::count()));

        $attribute = array_merge($admin->user->toArray(), $admin->user->profile->toArray());
        $attribute['password'] = '123456';
        $attribute['password_confirmation'] = '123456';

        $this->actingAs($user, 'api');

        $response = $this->putJson(route('admins.update', $admin->id), $attribute);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));
    }

    public function test_api_admin_can_update_admin()
    {
        User::factory()->create();

        User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->admin()->save(Admin::factory()->make());
                $user->profile()->save(Profile::factory()->make());
            });
        $admin = Admin::find($this->faker()->numberBetween(1, Admin::count()));

        $attribute = array_merge($admin->user->toArray(), $admin->user->profile->toArray());
        $attribute['password'] = '123456';
        $attribute['password_confirmation'] = '123456';

        $this->actingAs($admin->user, 'api');

        $response = $this->putJson(route('admins.update', $admin->id), $attribute);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));
    }

    public function test_api_super_admin_can_delete_admin()
    {
        User::factory()->create();
        $user = User::find(1);

        User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->admin()->save(Admin::factory()->make());
                $user->profile()->save(Profile::factory()->make());
            });
        $admin = Admin::find($this->faker()->numberBetween(1, Admin::count()));
        $this->actingAs($user, 'api');
        $response = $this->deleteJson(route('admins.destroy', $admin->id));
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));
    }
}
