<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kategorie_poruch', function (Blueprint $table) {
            $table->id('IDKategorie');
            $table->string('Nazev');
            $table->text('Popis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kategorie_poruch');
    }
};
