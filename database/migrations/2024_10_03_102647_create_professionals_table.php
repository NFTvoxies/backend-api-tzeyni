<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->enum('gender',['Homme','Femme']);
            $table->string('email')->unique();
            $table->string('phone',14)->unique();
            $table->string('city');
            $table->tinyText('addresse');
            $table->string('profile')->nullable();
            $table->string('card_ID')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_verify')->default(false);
            $table->integer('experience')->nullable();
            $table->string('password');
            $table->string('code',6)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};
