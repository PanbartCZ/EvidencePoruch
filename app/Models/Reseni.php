<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reseni extends Model
{
    use HasFactory;

    protected $table = 'reseni';
    protected $primaryKey = 'IDReseni';

    protected $fillable = [
        'IDPorucha',
        'Datum_zahajeni',
        'Datum_dokonceni',
        'Popis_reseni',
    ];

    public function porucha(): BelongsTo
    {
        return $this->belongsTo(Porucha::class, 'IDPorucha', 'IDPorucha');
    }
}
