<?php

use App\Enums\PatientTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->primary();
            $table->string('name');
            $table->string('surname');
            $table->string('login')->unique();
            $table->string('password');
            $table->enum('sex', PatientTypeEnum::cases());
            $table->date('birth');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
