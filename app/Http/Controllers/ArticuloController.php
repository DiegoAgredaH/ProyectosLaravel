<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Agregamos
use App\Articulo;//es el modelo que creamos
use Illuminate\Support\Facades\Redirect;//sirve para hacer algunas redirecciones
use Illuminate\Support\Facades\Input;//sirve para poder subir la imagen desde la maquina del cliente
use App\Http\Requests\ArticuloFormRequest;//Es donde estan las restricciones de nuestro formulario
use DB;//agregamos el espacio de nombres DB para trabajar con la clase DB de laravel

class ArticuloController extends Controller
{
    public function __construct(){

    }

    //definimos el metodo index para mostrar la pagina inicial

    //Recibe como parametro un objeto de tipo request llamado request
    public function index(Request $request){
        if($request){ //si existe el objeto request entonces: voy a obtener todos los registros de la tabla articulo de la base de datos
            
            /*La variable query va a determinar cual va a ser el texto de busqueda para poder filtrar todas los articulos
            entonces dice el objeto request utilizando metodo get va utilizar el parametro de busqueda searchText*/
            
            $query=trim($request->get('searchText'));

            /* articulos utiliza la clase DB y se le especifica la tabla articulo que es de donde va a obtener los registros donde el 
            nombre sea lo que tiene la variable query sin importar lo que tenga al inicio o al fin por eso son los '%' */

            $articulos=DB::table('articulo as a') //la tabla articulo va a tener un alias que sera 'a'
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')//la tabla articulo esta relacionada con la tabla categoria que tiene el alias 'c' donde el idcategoria de la tabla articulo sea igual al idcategoria de la tabla categoria
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.descripcion','a.imagen','a.estado')
            ->where('a.nombre','LIKE','%'.$query.'%')
            ->orderBy('a.idarticulo','desc')
            ->paginate(7);//para que la paginacion sea de 7 en 7 registros

            /* retornara la vista index a la cual se le enviara los parametros que estan entre "" en articulos iran 
            todos los articulos y en searchText irá el texto de busqueda  */
            return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);
        }
    }

    public function create(){
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();//para enviarle el listado de las categorias para poder mostrarlas en un combo box pero solo aquellas que estan activas osea que en condicion tengan el valor 1
        return view("almacen.categoria.create",["categorias"=>$categorias]);//le enviamos el parametro "categorias" que es lo que esta en la variable $categorias
    }

    /*definimos el metodo store para almacenar nuestro objeto de modelo categoria en la tabla categoria de la base de datos,
    para eso validamos utilizando el request CategoriaFormRequest y creamos un objeto llamado request*/
    
    public function store(ArticuloFormRequest $request){
        $articulo = new Articulo; //creamos un objeto llamado 'articulo' que hara referencia al modelo 'Articulo'
        $articulo->idcategoria=$request->get('idcategoria');//en el atributo 'idcategoria' del objeto articulo se va a almacenar lo que esta en el espacio 'idcategoria' del formulario
        $articulo->codigo=$request->get('codigo');//en el atributo 'codigo' del objeto articulo se va a almacenar lo que esta en el espacio 'codigo' del formulario
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';//en el atributo 'estado' se asigna Activo 
        //lo siguiente es para validar la imagen que el cliente quiere subir al servidor
        if (Input::hasFile('imagen')){ //si no esta vacio el objeto 'imagen' del formulario siga 
            $file=Input::file('imagen');//en file se guardara la imagen que tengo en el objeto del formulario llamado 'imagen'
            $file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());//nuestro archivo lo vamos a mover a la acrpeta public y dentro de ella voy a crear otra carpeta llamada imagenes y una subcarpeta llamada articulos y ahi se movera la imagen que esta en $file con el metodo getClienOriginalName para establecer el nombre de ese archivo que estoy moviendo
            $articulo->imagen=$file->getClientOriginalName();//en nuestro objeto articulo en el atributo imagen le enviamos el nombre que se le asigno con el metodo getOriginalName
        }
        $articulo->save();//guarda el objeto categoria
        return Redirect::to('almacen/articulo');//para que despues de almacenar nos redireccione a la vista articulo.
    }

    //definimos el metodo show para mostrar, recibe un parametro que es el id del articulo que quiero mostrar
    public function show($id){
        return view("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);/*retorna la vista show la cual mostrara el articulo que tiene el id que se le envia como parametro*/
    }

    //definimos el metodo edit para editar, recibe un parametro que es el id de la articulo que quiero editar
    public function edit($id){
        $articulo=Articulo::findOrFail($id);//en $articulo se guardara un articulo especifico cuyo id sea el que me llega como parametro
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        return view("almacen.articulo.edit",["articulo"=>$articulo,"categorias"=>$categorias]);/*retorna la vista edit la cual mostrara el articulo que tiene el id que se le envia como parametro y tambien las categorias */
    }

    //definimos el metodo update para actualizar, recibe 2 parametros un objeto llamado 'request' de tipo ArticuloFormRequest y un 'id' del articulo que quiero modificar
   
    public function update(ArticuloFormRequest $request,$id){
        $articulo=Articulo::findOrFail($id); //'articulo' hace referencia al modelo Articulo y con la funcion findOrFail se le envia el articulo que quiero modificar por medio del 'id'
        $articulo->idcategoria=$request->get('idcategoria');//en el atributo 'idcategoria' del objeto articulo se va a almacenar lo que esta en el espacio 'idcategoria' del formulario
        $articulo->codigo=$request->get('codigo');//en el atributo 'codigo' del objeto articulo se va a almacenar lo que esta en el espacio 'codigo' del formulario
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        //lo siguiente es para validar la imagen que el cliente quiere subir al servidor
        if (Input::hasFile('imagen')){ //si no esta vacio el objeto 'imagen' del formulario siga 
            $file=Input::file('imagen');//en file se guardara la imagen que tengo en el objeto del formulario llamado 'imagen'
            $file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());//nuestro archivo lo vamos a mover a la acrpeta public y dentro de ella voy a crear otra carpeta llamada imagenes y una subcarpeta llamada articulos y ahi se movera la imagen que esta en $file con el metodo getClienOriginalName para establecer el nombre de ese archivo que estoy moviendo
            $articulo->imagen=$file->getClientOriginalName();//en nuestro objeto articulo en el atributo imagen le enviamos el nombre que se le asigno con el metodo getOriginalName
        }
        $articulo->update();// llamamos al metodo update para actualizar el objeto 'articulo'
        return Redirect::to('almacen/articulo');// redirigimos a la vista articulo
    }

    //definimos el metodo destroy para que no se muestre un articulo en el listado de articulos, recibimos como parametro el id del articulo que queremos que no se muestre
    public function destroy($id){
        $articulo=Articulo::findOrFail($id);//'categoria' hace referencia al modelo Categoria y con la funcion findOrFail se le envia la categoria que quiero que no se muestre por medio del 'id'
        $articulo->estado='Inactivo'; //el atributo 'condicion' de nuestro objeto va a ser 0 para que no se muestre en el metodo index ya que solo muestra los que tienen el valor 1
        $categoria->update();// llamamos al metodo update para actualizar el objeto 'articulo'
        return Redirect::to('almacen/articulo');// redirigimos a la vista articulo
    }
}
