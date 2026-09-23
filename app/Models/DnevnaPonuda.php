<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DnevnaPonuda extends Model
{
    use HasFactory;

    protected $fillable = ['datum', 'aktivna'];
    protected $casts = ['datum' => 'date:Y-m-d', 'aktivna' => 'boolean'];

    public function jelos()
    {
        return $this->belongsToMany(Jelo::class, 'dnevna_ponuda_jelo')
            ->withPivot('cena', 'kolicina_dostupna')
            ->withTimestamps();
    }
}
