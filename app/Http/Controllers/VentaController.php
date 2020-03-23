<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Agregamos
use App\Venta;//es el modelo que creamos
use App\DetalleVenta;
use Illuminate\Support\Facades\Redirect;//sirve para hacer algunas redirecciones
use App\Http\Requests\VentaFormRequest;//Es donde estan las restricciones de nuestro formulario
use DB;

use Carbon\Carbon; //esto es para poder usar formato de fecha y hora de nuestra zona horaria
use Response;
use Illuminate\Support\Collection;

class VentaController extends Controller
{
    //declaramos el constructor
    public function __construct(){
        $this->middleware('auth');//para que no me permita entrar a la url de cada vista sin antes haberme logueado
    }

    public function index(Request $request){
        if($request){ 
            $query=trim($request->get('searchText'));

            /* ingresos utiliza la clase DB y se le especifica la tabla ingreso con el alias i que es de donde va a obtener los registros donde el 
            nombre sea lo que tiene la variable query sin importar lo que tenga al inicio o al fin por eso son los '%' */ 

            $ventas=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')//une la tabla ingreso con la tabla persona que tiene alias p y une ambas tablas por medio de idproveedor de la tabla ingreso que tiene que ser igual a idpersona de la tabla persona
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')//a la anterior union le unimos tambien la tabla detalle_ingreso que tiene el alias di y van a estar unidas por medio de idingreso de la tabla ingreso y idingreso de la tabla detalle_ingreso
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')//DB::raw('sum(di.cantidad*precio_compra) as total quiere decir que de la base de adtos a traves del metodo raw voy a sumar la multiplicacion de la cantidad * precio_compra de la tabla detalle ingreso y lo voy a guardar en una variable llamada total
            ->where('v.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
            ->paginate(7);
            /* retornara la vista index a la cual se le enviara los parametros que estan entre "" en ingresos iran 
            todos los ingresos y en searchText irá el texto de busqueda  */
            return view('ventas.venta.index',["ventas"=>$ventas,"searchText"=>$query]);
        }
    }

    public function create(){
        $personas=DB::table('persona')->where('tipo_persona','=','Cliente')->get();
        $articulos=DB::table('articulo as art')
        ->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
        ->select(DB::raw('CONCAT(art.codigo," ",art.nombre) AS articulo'),'art.idarticulo','art.stock',DB::raw('avg(di.precio_venta) as precio_promedio'))//precio_promedio es el promedio de todos los precios de venta que ha tenido un articulo
        ->where('art.estado','=','Activo')
        ->where('art.stock','>','0')
        ->groupBy('articulo','art.idarticulo','art.stock')
        ->get();
        return view("ventas.venta.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

    public function store(VentaFormRequest $request){
        /*try & catch es un capturador de excepciones entonces lo usamos debido a que en la base de datos debemos almacenar primero el ingreso y despues sus detalles de ingreso pero se tienen que almacenar ambos
        pero puede darce el caso de que exista un problema en la red y puede que se almacene solo los ingresos pero no sus detalles entonces la transaccion no será efectiva asi que vamos a cancelarla porque siempre
        se deben almacenar los ingresos y sus detalles*/
        try{
            DB::beginTransaction();//para iniciar una transaccion
            
            $venta=new Venta;
            $venta->idcliente=$request->get('idcliente');
            $venta->tipo_comprobante=$request->get('tipo_comprobante');
            $venta->serie_comprobante=$request->get('serie_comprobante');
            $venta->num_comprobante=$request->get('num_comprobante');
            $venta->total_venta=$request->get('total_venta');
            $mytime=Carbon::now('America/Bogota');
            $venta->fecha_hora=$mytime->toDateTimeString();
            $venta->impuesto='18';
            $venta->estado='A';
            $venta->save();

            $idarticulo=$request->get('idarticulo');
            $cantidad=$request->get('cantidad');
            $precio_venta=$request->get('precio_venta');
            $descuento=$request->get('descuento');
            
            $cont=0;//parea recorrer el array de todos los detalles

            while($cont < count($idarticulo)){
                $detalle=new DetalleVenta();
                $detalle->idventa=$venta->idventa;
                $detalle->idarticulo=$idarticulo[$cont];
                $detalle->cantidad=$cantidad[$cont];
                $detalle->precio_venta=$precio_venta[$cont];
                $detalle->descuento=$descuento[$cont];
                $detalle->save();
                $cont=$cont+1;
            }
            DB::commit();//para finalizar la transaccion
        }
        catch(\Exception $e){
            DB::rollback();//para anular una transaccion
        }
        return Redirect::to('ventas/venta');
    }

    public function show($id){
        $venta=DB::table('venta as v')
        ->join('persona as p','v.idcliente','=','p.idpersona')//une la tabla ingreso con la tabla persona que tiene alias p y une ambas tablas por medio de idproveedor de la tabla ingreso que tiene que ser igual a idpersona de la tabla persona
        ->join('detalle_venta as dv','v.idventa','=','dv.idventa')//a la anterior union le unimos tambien la tabla detalle_ingreso que tiene el alias di y van a estar unidas por medio de idingreso de la tabla ingreso y idingreso de la tabla detalle_ingreso
        ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
        ->where('v.idventa','=',$id)
        //->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado','v.total_venta')
        ->first();//para que elija el primero que cumpla con la condicion del where

        $detalles=DB::table('detalle_venta as d')
        ->join('articulo as a','d.idarticulo','=','a.idarticulo')
        ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
        ->where('d.idventa','=',$id)
        ->get();
        return view("ventas.venta.show",["venta"=>$venta,"detalles"=>$detalles]);
    }

    public function destroy($id){
       $venta=Venta::findOrFail($id);
       $venta->estado='C';
       $venta->update();
       return Redirect::to('ventas/venta'); 
    }
}
