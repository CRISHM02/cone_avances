<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    protected $table = "CuentaBancaria";
    protected $primaryKey = 'codigo';
    public $timestamps = false;
    protected $fillable = [
        'codigo',
        'codigoEmpresa',
        'codigoEntidadFinanciera',
        'CCI',
        'numeroCuenta',
        'tipoMoneda',
        'nombre',
        'vigencia'
    ];
}
