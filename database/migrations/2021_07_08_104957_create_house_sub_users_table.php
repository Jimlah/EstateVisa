<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseSubUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_sub_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('house_id');
            $table->unsignedInteger('user_id');
            $table->enum('status', [
                User::ACTIVE,
                User::DEACTIVATED,
                USER::SUSPENDED,
            ])->default(User::ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_sub_users');
    }
}
