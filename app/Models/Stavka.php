<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Stavka extends Model
{
    use HasFactory;

        protected $fillable = ['trenutna_cena', 'kolicina', 'rezervacija_id', 'jelo_id'];

        public function rezervacija()
        {
            return $this->belongsTo(Rezervacija::class);
        }

        public function jelo()
        {
            return $this->belongsTo(Jelo::class);
        }
}
