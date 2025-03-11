<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Barangay;
use App\Models\IncidentType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('informant');
            $table->dateTime('date_time');
            $table->foreignIdFor(IncidentType::class);
            $table->foreignIdFor(Barangay::class);
            $table->text('description');
            $table->string('purok');
            $table->string('casualties');
            $table->string('other_incident')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
