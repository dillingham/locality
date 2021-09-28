<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_level_3', function (Blueprint $table) {
            $table->id();
            $table->string('display');
            $table->foreignId('admin_level_1_id')->index();
            $table->foreignId('admin_level_2_id')->index();
            $table->foreignId('country_id')->index();
            $table->timestamps();
        });
    }
};
