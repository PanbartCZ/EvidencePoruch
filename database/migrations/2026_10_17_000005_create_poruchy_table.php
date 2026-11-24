<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('poruchy', function (Blueprint $table) {
            $table->id('IDPorucha');
            $table->string('Status_poruchy');
            $table->string('Priorita');
            $table->string('Lokace');
            $table->text('Popis')->nullable();
            $table->dateTime('Datum_nahlaseni');

            $table->foreignId('IDUzivatel')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('IDKategorie')->constrained('kategorie_poruch', 'IDKategorie')->onDelete('cascade');
            $table->foreignId('IDTechnik')->nullable()->constrained('users', 'id')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('poruchy');
    }
};
