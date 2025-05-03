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
        Schema::create('t_booking_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_t_booking');
            $table->unsignedBigInteger('id_m_room');
            $table->timestamp('date_checkin');
            $table->string('book_code');


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
        Schema::dropIfExists('t_booking_lines');
    }
};
