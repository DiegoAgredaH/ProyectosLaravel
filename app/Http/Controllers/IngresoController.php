<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Agregamos
use App\Ingreso;//es el modelo que creamos
use App\DetalleIngreso;
use Illuminate\Support\Facades\Redirect;//sirve para hacer algunas redirecciones
use App\Http\Requests\IngresoFormRequest;//Es donde estan las restricciones de nuestro formulario
use DB;

use Carbon\Carbon; //esto es para poder usar formato de fecha y hora de nuestra zona horaria
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
    //declaramos el constructor
    public function __construct(){

    }

    public function index(Request $request){
        if($request){ 
            $query=trim($request->get('searchText'));

            /* ingresos utiliza la clase DB y se le especifica la tabla ingreso con el alias i que es de donde va a obtener los registros donde el 
            nombre sea lo que tiene la variable query sin importar lo que tenga al inicio o al fin por eso son los '%' */ 

            $ingresos=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')//une la tabla ingreso con la tabla persona que tiene alias p y une ambas tablas por medio de idproveedor de la tabla ingreso que tiene que ser igual a idpersona de la tabla persona
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')//a la anterior union le unimos tambien la tabla detalle_ingreso que tiene el alias di y van a estar unidas por medio de idingreso de la tabla ingreso y idingreso de la tabla detalle_ingreso
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))//DB::raw('sum(di.cantidad*precio_compra) as total quiere decir que de la base de adtos a traves del metodo raw voy a sumar la multiplicacion de la cantidad * precio_compra de la tabla detalle ingreso y lo voy a guardar en una variable llamada total
            ->where('i.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy('i.idingreso','desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->paginate(7);
            /* retornara la vista index a la cual se le enviara los parametros que estan entre "" en ingresos iran 
            todos los ingresos y en searchText irá el texto de busqueda  */
            return view('compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);
        }
    }

    public function create(){
        $personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
        $articulos=DB::table('articulo as art')
        ->select(DB::raw('CONCAT(art.codigo," ",art.nombre) AS articulo'),'art.idarticulo')
        ->where('art.estado','=','Activo')
        ->get();
        return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

    public function store(IngresoFormRequest $request){
        /*try & catch es un capturador de excepciones entonces lo usamos debido a que en la base de datos debemos almacenar primero el ingreso y despues sus detalles de ingreso pero se tienen que almacenar ambos
        pero puede darce el caso de que exista un problema en la red y puede que se almacene solo los ingresos pero no sus detalles entonces la transaccion no será efectiva asi que vamos a cancelarla porque siempre
        se deben almacenar los ingresos y sus detalles*/
        try{
            DB::beginTransaction();//para iniciar una transaccion
            
            $ingreso=new Ingreso;
            $ingreso->idproveedor=$request->get('idproveedor');
            $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
            $ingreso->serie_comprobante=$request->get('serie_comprobante');
            $ingreso->num_comprobante=$request->get('num_comprobante');
            $mytime=Carbon::now('America/Bogota');
            $ingreso->fecha_hora=$mytime->toDateTimeString();
            $ingreso->impuesto='18';
            $ingreso->estado='A';
            $ingreso->save();

            $idarticulo=$request->get('idarticulo');
            $cantidad=$request->get('cantidad');
            $precio_compra=$request->get('precio_compra');
            $precio_venta=$request->get('precio_venta');
            
            $cont=0;//parea recorrer el array de todos los detalles

            while($cont < count($idarticulo)){
                $detalle=new DetalleIngreso();
                $detalle->idingreso=$ingreso->idingreso;
                $detalle->idarticulo=$idarticulo[$cont];
                $detalle->cantidad=$cantidad[$cont];
                $detalle->precio_compra=$precio_compra[$cont];
                $detalle->precio_venta=$precio_venta[$cont];
                $detalle->save();
                $cont=$cont+1;
            }
            DB::commit();//para finalizar la transaccion
        }
        catch(\Exception $e){
            DB::rollback();//para anular una transaccion
        }
        return Redirect::to('compras/ingreso');
    }

    public function show($id){
        $ingreso=DB::table('ingreso as i')
        ->join('persona as p','i.idproveedor','=','p.idpersona')//une la tabla ingreso con la tabla persona que tiene alias p y une ambas tablas por medio de idproveedor de la tabla ingreso que tiene que ser igual a idpersona de la tabla persona
        ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')//a la anterior union le unimos tambien la tabla detalle_ingreso que tiene el alias di y van a estar unidas por medio de idingreso de la tabla ingreso y idingreso de la tabla detalle_ingreso
        ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))//DB::raw('sum(di.cantidad*precio_compra) as total quiere decir que de la base de adtos a traves del metodo raw voy a sumar la multiplicacion de la cantidad * precio_compra de la tabla detalle ingreso y lo voy a guardar en una variable llamada total
        ->where('i.idingreso','=',$id)
        ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
        ->first();//para que elija el primero que cumpla con la condicion del where

        $detalles=DB::table('detalle_ingreso as d')
        ->join('articulo as a','d.idarticulo','=','a.idarticulo')
        ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
        ->where('d.idingreso','=',$id)
        ->get();
        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }

    public function destroy($id){
       $ingreso=Ingreso::findOrFail($id);
       $ingreso->estado='C';
       $ingreso->update();
       return Redirect::to('compras/ingreso'); 
    }
}
