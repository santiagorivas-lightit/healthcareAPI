<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_doctor', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropForeign(['doctor_id']);

            $table->foreign('clinic_id')
                ->references('id')->on('clinics')
                ->onDelete('cascade');

            $table->foreign('doctor_id')
                ->references('id')->on('doctors')
                ->onDelete('cascade');

        });
    }
};
