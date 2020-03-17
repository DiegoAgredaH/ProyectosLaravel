<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table='persona';  // hace referencia a la tabla persona de la base de datos dbsistemaventas
    protected $primaryKey='idpersona'; // define la llave primaria del modelo

    public $timestamps=false; //false para que no se cree las columnas de creacion o actualizacion del registro.

    //para especificar los campos que reciben un valor para poder almacenarlo en la base de datos 
    
    protected $fillable =[
        'tipo_persona',
        'nombre',
        'tipo_documento',
        'num_documento',
        'direccion',
        'telefono',
        'email'
    ];

    //los campos guarded se especifican cuando no queremos que se agreguen al modelo

    protected $guarded =[

    ];
}
