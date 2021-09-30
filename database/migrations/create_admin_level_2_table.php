<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_level_2', function (Blueprint $table) {
            $table->id();
            $table->string('display');
            $table->foreignId('admin_level_1_id')->index();
            $table->foreignId('country_code_id')->index();
            $table->timestamps();
        });
    }
};
