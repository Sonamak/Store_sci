<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivoteTableAdvertisementUserField extends Migration
{
    public function up()
    {
        Schema::create('advertisement_userField', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('advertisement_id');
            $table->foreignId('user_field_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('advertisement_userField');
    }
}
