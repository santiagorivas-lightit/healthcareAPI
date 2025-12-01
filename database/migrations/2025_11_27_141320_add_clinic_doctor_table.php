<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         Schema::create('clinic_doctor', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('clinic_id');
            $table->foreignId('doctor_id');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_doctor');
    }
};
