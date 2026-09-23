<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StanjeNalepnica extends Model
{
    protected $table = 'stanje_nalepnicas';
    protected $fillable = ['trenutna_pozicija'];
}
