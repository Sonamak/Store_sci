<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotTableAdvertisementUser extends Migration
{
    
    public function up()
    {
        Schema::create('advertisement_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('advertisement_id');
            $table->foreignId('user_id');
            $table->boolean('seen')->default(false);

            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('advertisement_user');
    }
}
