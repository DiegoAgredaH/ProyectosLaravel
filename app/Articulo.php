<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table='articulo';  // hace referencia a la tabla articulo de la base de datos dbsistemaventas
    protected $primaryKey='idarticulo'; // define la llave primaria del modelo

    public $timestamps=false; //false para que no se cree las columnas de creacion o actualizacion del registro.

    //para especificar los campos que reciben un valor para poder almacenarlo en la base de datos 
    
    protected $fillable =[
        'idcategoria',
        'codigo',
        'nombre',
        'stock',
        'descripcion',
        'imagen',
        'estado'
    ];

    //los campos guarded se especifican cuando no queremos que se agreguen al modelo

    protected $guarded =[

    ];
}
