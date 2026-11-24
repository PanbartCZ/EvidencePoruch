<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriePoruchy extends Model
{
    use HasFactory;

    protected $table = 'kategorie_poruch';
    protected $primaryKey = 'IDKategorie';

    protected $fillable = [
        'Nazev',
        'Popis',
    ];

    public function poruchy(): HasMany
    {
        return $this->hasMany(Porucha::class, 'IDKategorie', 'IDKategorie');
    }
}
