<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Seznam sloupců, které lze hromadně ukládat
    protected $fillable = [
        'mistnost',
        'kategorie',
        'status',
        'budova', // Přidej sem všechny sloupce, které posíláš z formuláře
    ];
}
