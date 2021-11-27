<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\AdminFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUserId($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Estate
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $address
 * @property string|null $logo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EstateAdmin[] $admins
 * @property-read int|null $admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HouseType[] $houseTypes
 * @property-read int|null $house_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\House[] $houses
 * @property-read int|null $houses_count
 * @property-read \App\Models\EstateAdmin|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Visitor[] $visitors
 * @property-read int|null $visitors_count
 * @method static \Database\Factories\EstateFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Estate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Estate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estate whereUpdatedAt($value)
 */
	class Estate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EstateAdmin
 *
 * @property int $id
 * @property int|null $estate_id
 * @property int|null $user_id
 * @property string $status
 * @property int $is_owner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Estate|null $estate
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin admin()
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin estateOnly()
 * @method static \Database\Factories\EstateAdminFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin whereEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstateAdmin whereUserId($value)
 */
	class EstateAdmin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\House
 *
 * @property int $id
 * @property int|null $estate_id
 * @property string $name
 * @property string $address
 * @property int|null $house_type_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Estate|null $estate
 * @property-read \App\Models\HouseType|null $houseType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserHouse[] $houseUsers
 * @property-read int|null $house_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserHouse[] $members
 * @property-read int|null $members_count
 * @property-read \App\Models\UserHouse|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|House estateHouses()
 * @method static \Database\Factories\HouseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|House newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|House newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|House query()
 * @method static \Illuminate\Database\Eloquent\Builder|House whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereHouseTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereUpdatedAt($value)
 */
	class House extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HouseType
 *
 * @property int $id
 * @property int|null $estate_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Estate|null $estate
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType estateOnly()
 * @method static \Database\Factories\HouseTypeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType query()
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType whereEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HouseType whereUpdatedAt($value)
 */
	class HouseType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Profile
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $phone_number
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ProfileFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property string|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin|null $admin
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Estate[] $estate
 * @property-read int|null $estate_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EstateAdmin[] $estateAdmin
 * @property-read int|null $estate_admin_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\House[] $houses
 * @property-read int|null $houses_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Profile $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserHouse[] $userHouses
 * @property-read int|null $user_houses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Visitor[] $visitors
 * @property-read int|null $visitors_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserHouse
 *
 * @property int $id
 * @property int|null $house_id
 * @property int|null $user_id
 * @property string $is_owner
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\House|null $house
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\UserHouseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse userHouse()
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHouse whereUserId($value)
 */
	class UserHouse extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Visitor
 *
 * @property int $id
 * @property int $user_id
 * @property int $estate_id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string|null $gender
 * @property string $phone
 * @property string|null $address
 * @property string $sent_by
 * @property string|null $visited_at
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Estate $estate
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor estateOnly()
 * @method static \Database\Factories\VisitorFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor userOnly()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereSentBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereVisitedAt($value)
 */
	class Visitor extends \Eloquent {}
}

