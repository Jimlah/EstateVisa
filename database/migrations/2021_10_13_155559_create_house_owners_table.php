<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('estate_id')->nullable();
            $table->string('status')->default(User::ACTIVE);
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
        Schema::dropIfExists('house_owners');
    }
}
