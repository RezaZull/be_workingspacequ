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
        Schema::create('t_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_m_user');
            $table->timestamp('date_book');
            $table->decimal('grandtotal', 12, 2);
            $table->enum('payment_status', ["pending", "settlement", "deny", "cancel", "expire", "failure"]);

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
        Schema::dropIfExists('t_bookings');
    }
};
