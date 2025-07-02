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
        Schema::create('patients_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_patients');
            $table->unsignedBigInteger('id_schedules');
            $table->tinyInteger('status')->default(1); // 0 = Done, 1 = OnGoing, 2 = cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('id_patients')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');
            $table->foreign('id_schedules')
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients_schedules');
    }
};
