<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Agregamos
use App\Categoria;//es el modelo que creamos
use Illuminate\Support\Facades\Redirect;//sirve para hacer algunas redirecciones
use App\Http\Requests\CategoriaFormRequest;//Es donde estan las restricciones de nuestro formulario
use DB;//agregamos el espacio de nombres DB para trabajar con la clase DB de laravel

class CategoriaController extends Controller
{
    //declaramos el constructor
    public function __construct(){
        $this->middleware('auth');//para que no me permita entrar a la url de cada vista sin antes haberme logueado
    }

    //definimos el metodo index para mostrar la pagina inicial

    //Recibe como parametro un objeto de tipo request llamado request
    public function index(Request $request){
        if($request){ //si existe el objeto request entonces: voy a obtener todos los registros de la tabla categoria de la base de datos
            
            /*La variable query va a determinar cual va a ser el texto de busqueda para poder filtrar todas las categorias
            entonces dice el objeto request utilizando metodo get va utilizar el parametro de busqueda searchText*/
            
            $query=trim($request->get('searchText'));

            /* categorias utiliza la clase DB y se le especifica la tabla categoria que es de donde va a obtener los registros donde el 
            nombre sea lo que tiene la variable query sin importar lo que tenga al inicio o al fin por eso son los '%' */

            $categorias=DB::table('categoria')->where('nombre','LIKE','%'.$query.'%')
            ->where('condicion','=','1') //donde la condicion sea igual a 1 osea las categorias activas 
            ->orderBy('idcategoria','desc')
            ->paginate(7);//para que la paginacion sea de 7 en 7 registros

            /* retornara la vista index a la cual se le enviara los parametros que estan entre "" en categorias iran 
            todas las categorias y en searchText irá el texto de busqueda  */
            return view('almacen.categoria.index',["categorias"=>$categorias,"searchText"=>$query]);
        }
    }

    public function create(){
        return view("almacen.categoria.create");//retorna la vista create
    }

    /*definimos el metodo store para almacenar nuestro objeto de modelo categoria en la tabla categoria de la base de datos,
    para eso validamos utilizando el request CategoriaFormRequest y creamos un objeto llamado request*/
    
    public function store(CategoriaFormRequest $request){
        $categoria = new Categoria; //creamos un objeto llamado 'categoria' que hara referencia al modelo 'Categoria'
        $categoria->nombre=$request->get('nombre');//en el atributo 'nombre' del objeto categoria se va a almacenar lo que esta en el espacio 'nombre' del formulario
        $categoria->descripcion=$request->get('descripcion');//en el atributo 'descripcion' del objeto categoria se va a almacenar lo que esta en el espacio 'descripcion' del formulario
        $categoria->condicion='1';//en el atributo 'condicion' se asigna 1 para que este activa, cuando se la borre pasara a cero para que este inactiva
        $categoria->save();//guarda el objeto categoria
        return Redirect::to('almacen/categoria');//para que despues de almacenar nos redireccione a la vista categorias que es donde se va a listar todas las categorias que existen.
    }

    //definimos el metodo show para mostrar, recibe un parametro que es el id de la categoria que quiero mostrar
    public function show($id){
        return view("almacen.categoria.show",["categoria"=>Categoria::findOrFail($id)]);/*retorna la vista show la cual mostrara la categoria que tiene el id que se le envia como parametro*/
    }

    //definimos el metodo edit para editar, recibe un parametro que es el id de la categoria que quiero editar
    public function edit($id){
        return view("almacen.categoria.edit",["categoria"=>Categoria::findOrFail($id)]);/*retorna la vista edit la cual mostrara la categoria que tiene el id que se le envia como parametro*/
    }

    //definimos el metodo update para actualizar, recibe 2 parametros un objeto llamado 'request' de tipo CategoriaFormRequest y un 'id' de la categoria que quiero modificar
   
    public function update(CategoriaFormRequest $request,$id){
        $categoria=Categoria::findOrFail($id); //'categoria' hace referencia al modelo Categoria y con la funcion findOrFail se le envia la categoria que quiero modificar por medio del 'id'
        $categoria->nombre=$request->get('nombre'); // en el espacio 'nombre' de el objeto 'categoria' se va a almacenar lo que esta en el objeto 'request' en el espacio 'nombre' el cual lo va a obtener mediante el metodo get 
        $categoria->descripcion=$request->get('descripcion'); // en el espacio 'descripcion' de el objeto 'categoria' se va a almacenar lo que esta en el objeto 'request' en el espacio 'descripcion' el cual lo va a obtener mediante el metodo get 
        $categoria->update();// llamamos al metodo update para actualizar el objeto 'categoria'
        return Redirect::to('almacen/categoria');// redirigimos a la vista categoria
    }

    //definimos el metodo destroy para que no se muestre una categoria en el listado de categorias, recibimos como parametro el id de la categoria que queremos que no se muestre
    public function destroy($id){
        $categoria=Categoria::findOrFail($id);//'categoria' hace referencia al modelo Categoria y con la funcion findOrFail se le envia la categoria que quiero que no se muestre por medio del 'id'
        $categoria->condicion='0'; //el atributo 'condicion' de nuestro objeto va a ser 0 para que no se muestre en el metodo index ya que solo muestra los que tienen el valor 1
        $categoria->update();// llamamos al metodo update para actualizar el objeto 'categoria'
        return Redirect::to('almacen/categoria');// redirigimos a la vista categoria
    }
}
