<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technik extends Model
{
    use HasFactory;

    protected $table = 'technici';
    protected $primaryKey = 'IDTechnik';

    protected $fillable = [
        'IDUzivatel',
        'Dostupnost',
        'Specializace',
    ];

    public function uzivatel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'IDUzivatel', 'IDUzivatel');
    }

    public function poruchy(): HasMany
    {
        return $this->hasMany(Porucha::class, 'IDTechnik', 'IDTechnik');
    }
}
