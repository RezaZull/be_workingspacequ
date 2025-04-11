<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('value');

            $table->string("obj_type");
            $table->boolean("flag_active");
            $table->timestamps();
            $table->softDeletesDatetime();
            $table->string("created_by");
            $table->string("updated_by")->nullable();
            $table->string("deleted_by")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
