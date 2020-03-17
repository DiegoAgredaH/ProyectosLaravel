<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Agregamos
use App\Persona;//es el modelo que creamos
use Illuminate\Support\Facades\Redirect;//sirve para hacer algunas redirecciones
use App\Http\Requests\PersonaFormRequest;//Es donde estan las restricciones de nuestro formulario
use DB;//agregamos el espacio de nombres DB para trabajar con la clase DB de laravel

class ClienteController extends Controller
{
    //declaramos el constructor
    public function __construct(){

    }

    //definimos el metodo index para mostrar la pagina inicial

    //Recibe como parametro un objeto de tipo request llamado request
    public function index(Request $request){
        if($request){ //si existe el objeto request entonces: voy a obtener todos los registros de la tabla persona de la base de datos
            
            /*La variable query va a determinar cual va a ser el texto de busqueda para poder filtrar todas las personas
            entonces dice el objeto request utilizando metodo get va utilizar el parametro de busqueda searchText*/
            
            $query=trim($request->get('searchText'));

            /* personas utiliza la clase DB y se le especifica la tabla persona que es de donde va a obtener los registros donde el 
            nombre sea lo que tiene la variable query sin importar lo que tenga al inicio o al fin por eso son los '%' y ademas va a poder buscar por nombre o por documento*/ 

            $personas=DB::table('persona')->where('nombre','LIKE','%'.$query.'%')
            ->where('tipo_persona','=','Cliente') //donde la condicion sea igual a 1 osea las categorias activas 
            ->orwhere('num_documento','LIKE','%'.$query.'%')
            ->where('tipo_persona','=','Cliente')
            ->orderBy('idpersona','desc')
            ->paginate(7);//para que la paginacion sea de 7 en 7 registros

            /* retornara la vista index a la cual se le enviara los parametros que estan entre "" en personas iran 
            todas las personas y en searchText irá el texto de busqueda  */
            return view('ventas.cliente.index',["personas"=>$personas,"searchText"=>$query]);
        }
    }

    public function create(){
        return view("ventas.cliente.create");//retorna la vista create
    }

    /*definimos el metodo store para almacenar nuestro objeto de modelo Persona en la tabla persona de la base de datos,
    para eso validamos utilizando el request PersonaFormRequest y creamos un objeto llamado request*/
    
    public function store(PersonaFormRequest $request){
        $persona = new Persona; //creamos un objeto llamado 'categoria' que hara referencia al modelo 'Categoria'
        $persona->tipo_persona='Cliente';//cuando registre desde el formulario registrar cliente el tipo_perona va a ser Cliente 
        $persona->nombre=$request->get('nombre');//en el atributo 'nombre' del objeto persona se va a almacenar lo que esta en el espacio 'nombre' del formulario
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->save();//guarda el objeto persona
        return Redirect::to('ventas/cliente');//para que despues de almacenar nos redireccione a la vista index de los clientes que es donde se va a listar todas los clientes que existen.
    }

    //definimos el metodo show para mostrar, recibe un parametro que es el id de la persona que quiero mostrar
    public function show($id){
        return view("ventas.cliente.show",["persona"=>Persona::findOrFail($id)]);/*retorna la vista show la cual mostrara la categoria que tiene el id que se le envia como parametro*/
    }

    //definimos el metodo edit para editar, recibe un parametro que es el id de la persona que quiero editar
    public function edit($id){
        return view("ventas.cliente.edit",["persona"=>Persona::findOrFail($id)]);/*retorna la vista edit la cual mostrara la categoria que tiene el id que se le envia como parametro*/
    }

    //definimos el metodo update para actualizar, recibe 2 parametros un objeto llamado 'request' de tipo PersonaFormRequest y un 'id' de la persona que quiero modificar
   
    public function update(PersonaFormRequest $request,$id){
        $persona=Persona::findOrFail($id); //'persona' hace referencia al modelo Persona y con la funcion findOrFail se le envia la persona que quiero modificar por medio del 'id'
        $persona->nombre=$request->get('nombre');//en el atributo 'nombre' del objeto persona se va a almacenar lo que esta en el espacio 'nombre' del formulario
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->update();// llamamos al metodo update para actualizar el objeto 'persona'
        return Redirect::to('ventas/cliente');// redirigimos a la vista categoria
    }

    //definimos el metodo destroy para que no se muestre una persona en el listado de personas, recibimos como parametro el id de la persona que queremos que no se muestre
    public function destroy($id){
        $persona=Persona::findOrFail($id);//'persona' hace referencia al modelo Persona y con la funcion findOrFail se le envia la persona que quiero que no se muestre por medio del 'id'
        $persona->tipo_persona='Inactivo'; //el atributo 'condicion' de nuestro objeto va a ser 0 para que no se muestre en el metodo index ya que solo muestra los que tienen el valor 1
        $persona->update();// llamamos al metodo update para actualizar el objeto 'persona'
        return Redirect::to('ventas/cliente');// redirigimos a la vista index de cliente
    }
}
