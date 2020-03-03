<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//se crea una ruta de tipo resource para hacer un grupo de rutas de recursos con las peticiones index,create,show,edit,update,destroy
Route::resource('almacen/categoria','CategoriaController'); //cuando se ingrese a la ruta almacen/categoria se va a llamar al controlador CategoriaController