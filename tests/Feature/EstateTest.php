<?php

namespace Tests\Feature;

use App\Exports\EstateExport;
use App\Mail\UserCreated;
use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Estate;
use App\Models\House;
use App\Models\House_type;
use App\Models\Profile;
use App\Models\UsersHouse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\assertEquals;

class EstateTest extends TestCase
{

    public function test_api_get_all_estate_for_super_admin()
    {
        User::factory()->create();
        $user = User::find(1);
        $estates = Estate::factory(10)->create();

        $this->actingAs($user, 'api');

        $response = $this->json('GET', '/api/estates');
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    public function test_api_can_not_get_access_for_non_super_admin()
    {

        User::factory(10)->create();
        $user = User::find($this->faker()->numberBetween(3, User::count()));

        $this->actingAs($user, 'api');

        $response = $this->json('GET', '/api/estates');
        $response->assertStatus(403)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('status'));
    }


    public function test_api_super_admin_can_create_a_new_estate()
    {
        Mail::fake();
        User::factory()->create();
        $user = User::find(1);
        $estate = Estate::factory()->make()->toArray();

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            [
                'estate_name' => $estate['name'],
                'estate_code' => $estate['code'],
            ],
            $estate,
            Profile::factory()->make()->toArray(),
        );

        $this->actingAs($user, 'api');


        $response = $this->json(
            'POST',
            '/api/estates',
            $attributes,
            ['Content-Type' => 'application/json']
        );


        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));

        $this->assertDatabaseHas('estates', [
            'name' => $attributes["name"],
            'code' => $attributes["code"],
        ]);


        $this->assertDatabaseHas('users', [
            'email' => $attributes["email"],
        ]);

        $this->assertDatabaseHas('profiles', [
            'firstname' => $attributes["firstname"],
            'lastname' => $attributes["lastname"]
        ]);

        $user = User::all()->last();
        $estate = Estate::all()->last();
        $profile = Profile::all()->last();
        assertEquals(
            $user->id,
            $estate->user_id
        );

        assertEquals(
            $user->id,
            $profile->user_id
        );

        Mail::assertSent(UserCreated::class);
    }

    public function test_api_can_not_create_a_new_estate_without_email()
    {
        User::factory()->create();
        $user = User::find(1);
        $this->actingAs($user, 'api');
        $estate = Estate::factory()->make();
        $attributes = [
            'estate_name' => $estate->name,
            'estate_code' => $estate->code,
        ];

        $response = $this->json(
            'POST',
            '/api/estates',
            $attributes,
        );
        $response->assertStatus(422);

        $this->assertDatabaseMissing('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);
    }

    public function test_api_can_not_create_a_new_estate_without_estate_name()
    {
        User::factory()->create();
        $user = User::find(1);
        $this->actingAs($user, 'api');
        $attributes = [
            'email' => $this->faker->email,
            'estate_code' => $this->faker->word,
        ];

        $response = $this->json(
            'POST',
            '/api/estates',
            $attributes,
        );
        $response->assertStatus(422);


        $this->assertDatabaseMissing('users', [
            'email' => $attributes['email'],
        ]);
    }

    public function test_api_can_not_create_a_new_estate_without_estate_code()
    {
        User::factory()->create();
        $user = User::find(1);
        $estate = Estate::factory()->make();
        $attributes = [
            'email' => $this->faker->email,
            'estate_name' => $estate->name,
        ];
        $this->actingAs($user, 'api');

        $response = $this->json(
            'POST',
            '/api/estates',
            $attributes,
        );

        $response->assertStatus(422);
        $this->assertDatabaseMissing('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $estate->email,
        ]);
    }

    public function test_api_super_admin_get_single_estate()
    {
        User::factory()->create();
        $user = User::find(1);
        $estate = Estate::factory()->create();
        $this->actingAs($user, 'api');
        $response = $this->json('GET', '/api/estates/' . $estate->id);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    /**
     * test_api_estate_owner_can_only_get_their_estate
     * this needs fixing
     *
     * @return void
     */
    public function test_api_estate_owner_can_only_get_their_estate()
    {
        User::factory(5)->create();
        $user = User::find($this->faker->numberBetween(3, User::count()));
        $estate = Estate::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user, 'api');

        $response = $this->json('GET', '/api/estates/' . $estate->id);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    /**
     * test_api_estate_owner_can_not_get_other_estate
     *
     * @return void
     */
    public function test_api_estate_owner_can_not_get_other_estate()
    {
        User::factory(5)->create();
        $user = User::find($this->faker->numberBetween(3, User::count()));
        $estate = Estate::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->json('GET', '/api/estates/' . $estate->id);
        $response->assertStatus(403);
    }

    public function test_api_super_admin_can_update_an_estate()
    {
        User::factory()->create();
        $user = User::find(1);
        $estate = Estate::factory()->create();
        $this->actingAs($user, 'api');

        $attributes = [
            'estate_name' => $this->faker->word,
            'estate_code' => $this->faker->word,
        ];

        $response = $this->json(
            'PUT',
            '/api/estates/' . $estate->id,
            $attributes,
        );
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));

        $this->assertDatabaseHas('estates', [
            'name' => $attributes['estate_name'],
            'code' => $attributes['estate_code'],
        ]);
    }

    public function test_api_estate_owner_can_update_his_estate()
    {
        User::factory()->create();
        $user = User::find(1);
        $estate = Estate::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user, 'api');
        $attributes = [
            'estate_name' => $this->faker->word,
            'estate_code' => $this->faker->word,
        ];

        $response = $this->json(
            'PUT',
            '/api/estates/' . $estate->id,
            $attributes,
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('estates', [
            'name' => $attributes['estate_name'],
            'code' => $attributes['estate_code'],
        ]);

        $this->assertDatabaseMissing('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);
    }

    public function test_api_estate_owner_can_not_update_other_estate()
    {
        User::factory(5)->create();
        $user = User::find($this->faker->numberBetween(3, 5));
        Estate::factory(10)->create();
        $estate = Estate::find($this->faker->numberBetween(1, 10));
        $this->actingAs($user, 'api');

        $attributes =  [
            'estate_name' => $this->faker->word,
            'estate_code' => $this->faker->word,
        ];

        $response = $this->json(
            'PUT',
            '/api/estates/' . $estate->id,
            $attributes
        );
        $response->assertStatus(403);
        $this->assertDatabaseMissing('estates', $attributes);

        $this->assertDatabaseHas('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);
    }

    public function test_api_super_admin_can_delete_an_estate()
    {
        User::factory()->create();
        $user = User::find(1);
        $estate = Estate::factory()->create();
        $this->actingAs($user, 'api');
        $response = $this->json('DELETE', '/api/estates/' . $estate->id);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));
    }


    public function test_api_non_super_admin_can_not_delete_an_estate()
    {
        User::factory(5)->create();
        $user = User::find(5);
        $estate = Estate::factory()->create();
        $this->actingAs($user, 'api');
        $response = $this->json('DELETE', '/api/estates/' . $estate->id);
        $response->assertStatus(403);
    }

    function test_api_super_admin_can_deactivate_an_estate()
    {
        House::unsetEventDispatcher();
        $this->withoutEvents();
        User::factory()->create();
        $user = User::find(1);

        $EstateOwner = User::factory()->create();
        $estate = Estate::factory()->create(['user_id' => $EstateOwner->id]);
        $estateHouseType = House_type::factory(5)->create(['estate_id' => $estate->id]);
        $estateHouse = House::factory(10)->create([
            'estate_id' => $estate->id,
            'houses_types_id' => House_type::where('estate_id', $estate->id)->get()->random()->id,
        ]);

        foreach ($estateHouse as $house) {
            $houseUser = User::factory()->create();
            UsersHouse::factory()->create([
                'user_id' => $houseUser->id,
                'house_id' => $house->id,
            ]);
        }

        $this->actingAs($user, 'api');
        $response = $this->patchJson(route('estates.deactivate', $estate->id));
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json
                ->has('status')
                ->has('message'));

        $this->assertDatabaseHas('estates', [
            'id' => $estate->id,
            'status' => "deactivated",
        ]);

        //    $this->assertDatabaseHas('houses', [
        //        'estate_id' => $estate->id,
        //        'status' => "deactivated",
        //    ]);
    }



    //    function test_api_super_admin_can_enable_an_estate()

    //   {
    //        House::unsetEventDispatcher();
    //        $this->withoutEvents();
    //        User::factory()->create();
    //        $user = User::find(1);
    //        House::factory(20)->create();
    //        $estate = Estate::find($this->faker->numberBetween(1, 1));

    //        $this->actingAs($user, 'api');
    //        $response = $this->json('POST', route('estates.enable', $estate->id));
    //        $response->assertStatus(200)
    //                  ->assertJson(fn (AssertableJson $json) =>
    //                     $json
    //                         ->has('status')
    //                         ->has('message'));

    //        $this->assertDatabaseHas('estates', [
    //            'id' => $estate->id,
    //            'status' => true,
    //        ]);

    //        $this->assertDatabaseHas('houses', [
    //            'estate_id' => $estate->id,
    //            'status' => true,
    //        ]);
    //    }



    public function test_api_user_can_import()
    {
        $this->withoutEvents();
        $this->withExceptionHandling();
        Excel::fake();
        User::factory()->create();
        $user = User::find(1);

        // Storage::fake();

        $this->actingAs($user, 'api');



        $attributes = [
            'file' => new UploadedFile(storage_path('framework/laravel-excel/estates.xlsx'), 'estates.xlsx'),
        ];

        $response = $this->postJson(route('estates.import'), $attributes);

        // dd(Estate::all()->toArray());
        // Excel::assertImported('estates.xlsx');

        $this->assertTrue(true);
    }

    public function test_api_user_can_export()
    {
        $this->withoutEvents();
        $this->withExceptionHandling();

        Excel::fake();

        User::factory()->create();
        $user = User::find(1);

        User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->profile()->save(Profile::factory()->make());
                $user->estate()->save(Estate::factory()->make());
            });


        $this->actingAs($user, 'api');
        $response = $this->getJson(route('estates.export'));
        Excel::assertDownloaded('estates.xlsx', function (EstateExport $excel) {
            return $excel->collection()->count() > 0;
        });
    }
}
