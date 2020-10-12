<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    protected $table = "cuentabancaria";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'CodigoEmpresa',
        'CodigoEntidadBancaria',
        'CCI',
        'NumeroCuenta',
        'TipoMoneda',
        'Nombre',
        'Vigencia'
    ];
}
