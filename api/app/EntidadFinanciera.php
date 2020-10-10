<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadFinanciera extends Model
{
    protected $table = "EntidadFinanciera";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'razonSocial',
        'siglas',
        'vigencia'];
}
