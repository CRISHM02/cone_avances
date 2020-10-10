<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empresa extends Model
{
    protected $table = "empresa";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'RazonSocial',
        'RUC',
        'Facebook',
        'Instagram',
        'Youtube',
        'Whatsapp',
        'Correo',
        'Logo',
        'Vigencia'
    ];
}
