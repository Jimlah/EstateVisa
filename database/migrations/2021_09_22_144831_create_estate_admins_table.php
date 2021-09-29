<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estate_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default(User::ACTIVE);
            $table->string('role')->default(User::ESTATE_ADMIN);
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
        Schema::dropIfExists('estate_admins');
    }
}
