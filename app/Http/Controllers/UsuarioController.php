<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Agregamos
use App\User;//es el modelo que creamos
use Illuminate\Support\Facades\Redirect;//sirve para hacer algunas redirecciones
use App\Http\Requests\UsuarioFormRequest;//Es donde estan las restricciones de nuestro formulario
use DB;

class UsuarioController extends Controller
{
    public function __construct(){
        $this->middleware('auth');//para que no me permita entrar a la url de cada vista sin antes haberme logueado
    }

    public function index(Request $request){
        if($request){ //si existe el objeto request entonces: voy a obtener todos los registros de la tabla persona de la base de datos
            $query=trim($request->get('searchText'));
            $usuarios=DB::table('users')->where('name','LIKE','%'.$query.'%')
            ->orderBy('id','desc')
            ->paginate(7);//para que la paginacion sea de 7 en 7 registros
            return view('seguridad.usuario.index',["usuarios"=>$usuarios,"searchText"=>$query]);
        }
    }

    public function create(){
        return view("seguridad.usuario.create");
    }

    public function store(UsuarioFormRequest $request){
        $usuario = new User; 
        $usuario->name=$request->get('name');
        $usuario->email=$request->get('email');
        $usuario->password=bcrypt($request->get('password'));//se bcrypt ṕara encriptar la contraseña
        $usuario->save();
        return Redirect::to('seguridad/usuario');
    }

    public function edit($id){
        return view("seguridad.usuario.edit",["usuario"=>User::findOrFail($id)]);
    }

    public function update(UsuarioFormRequest $request,$id){
        $usuario=User::findOrFail($id); 
        $usuario->name=$request->get('name');
        $usuario->email=$request->get('email');
        $usuario->password=bcrypt($request->get('password'));//se bcrypt ṕara encriptar la contraseña
        $usuario->update();
        return Redirect::to('seguridad/usuario');
    }
 
    public function destroy($id){
        $usuario=DB::table('users')->where('id','=', $id)->delete();
        return Redirect::to('seguridad/usuario');
    }
}
