<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'nombre'=>'required|max:50', //En nuestro formulario vamos a tener un objeto llamado 'nombre' obligatorio de ingresar de como maximo 50 caracteres
            'descripcion'=>'max:256' //'descripcion' será de maximo 256 caracteres
            
            /*se debe tener en cuenta que 'nombre' y 'descripcion' que estan en la regla no son los campos de la tabla categoria de la base de datos, sino el 
            nombre del objeto html que vamos a tener en nuestro formulario*/
        ];
    }
}
