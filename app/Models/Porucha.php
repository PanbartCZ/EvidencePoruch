<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Porucha extends Model
{
    use HasFactory;

    protected $table = 'poruchy';
    protected $primaryKey = 'IDPorucha';

    protected $fillable = [
        'Status_poruchy',
        'Priorita',
        'Lokace',
        'Popis',
        'Datum_nahlaseni',
        'IDUzivatel',
        'IDKategorie',
        'IDTechnik',
    ];

    protected function casts(): array
    {
        return [
            'Datum_nahlaseni' => 'datetime',
        ];
    }

    public function uzivatel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'IDUzivatel', 'id');
    }

    public function kategorie(): BelongsTo
    {
        return $this->belongsTo(KategoriePoruchy::class, 'IDKategorie', 'IDKategorie');
    }

    public function technik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'IDTechnik', 'id');
    }

    public function reseni(): HasMany
    {
        return $this->hasMany(Reseni::class, 'IDPorucha', 'IDPorucha');
    }
}
