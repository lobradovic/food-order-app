<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Jelo extends Model
{
    use HasFactory;

    protected $fillable = ['naziv_jela', 'cena', 'opis','stalna_ponuda'];

    public function stavkas()
    {
        return $this->hasMany(Stavka::class);
    }

    public function dnevnePonude()
    {
        return $this->belongsToMany(DnevnaPonuda::class, 'dnevna_ponuda_jelo')
            ->withPivot('cena', 'kolicina_dostupna')
            ->withTimestamps();
    }

    public function scopeStalna($query)
    {
        return $query->where('stalna_ponuda', true);
    }
}
