<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table='detalle_ingreso';  // hace referencia a la tabla detalle_ingreso de la base de datos dbsistemaventas
    protected $primaryKey='iddetalle_ingreso'; // define la llave primaria del modelo

    public $timestamps=false; //false para que no se cree las columnas de creacion o actualizacion del registro.

    //para especificar los campos que reciben un valor para poder almacenarlo en la base de datos 
    
    protected $fillable =[
        'idingreso',
        'idarticulo',
        'cantidad',
        'precio_compra',
        'precio_venta'
    ];

    //los campos guarded se especifican cuando no queremos que se agreguen al modelo

    protected $guarded =[

    ];
}
