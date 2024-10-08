<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->morphs('addressable');
            $table->enum('type', ['billing', 'shipping']);
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('address1');
            $table->string('address2');
            $table->string('zipcode');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
