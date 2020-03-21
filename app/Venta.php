<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table='venta';  // hace referencia a la tabla ingreso de la base de datos dbsistemaventas
    protected $primaryKey='idventa'; // define la llave primaria del modelo

    public $timestamps=false; //false para que no se cree las columnas de creacion o actualizacion del registro.

    //para especificar los campos que reciben un valor para poder almacenarlo en la base de datos 
    
    protected $fillable =[
        'idcliente',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total_venta',
        'estado'
    ];

    //los campos guarded se especifican cuando no queremos que se agreguen al modelo

    protected $guarded =[

    ];
}
