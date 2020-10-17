<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadBancaria extends Model
{
    protected $table = "entidadBancaria";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'RazonSocial',
        'Siglas',
        'Vigencia'];
}
