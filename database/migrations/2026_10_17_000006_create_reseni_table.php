<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reseni', function (Blueprint $table) {
            $table->id('IDReseni');
            $table->foreignId('IDPorucha')->constrained('poruchy', 'IDPorucha')->onDelete('cascade');
            $table->dateTime('Datum_zahajeni')->nullable();
            $table->dateTime('Datum_dokonceni')->nullable();
            $table->text('Popis_reseni')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reseni');
    }
};
