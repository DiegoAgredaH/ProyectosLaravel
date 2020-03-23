@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nuevo Ingreso</h3>
            @if(count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error) <!-- El foreach resibe todos los errores que me envia el archivo CategoriaFormRequest-y los almacenara en el objeto 'error' -->
                    <li>{{$error}}</li> <!-- lista error por error -->
                @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    {!!Form::open(array('url'=>'compras/ingreso','method'=>'POST','autocomplete'=>'off'))!!}
    {!!Form::token()!!}
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Proveedor</label>
                <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true">
                    @foreach($personas as $persona)
                    <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label>Tipo Comprobante</label>
                <select name="tipo_comprobante" class="form-control">
                        <option value="Boleta">Boleta</option>
                        <option value="Factura">Factura</option>
                        <option value="Ticket">Ticket</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="serie_comprobante">Serie Comprobante</label>
                <input type="text" name="serie_comprobante" value="{{old('serie_comprobante')}}" class="form-control" placeholder="Serie Comprobante..."> 
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">Número Comprobante</label>
                <input type="text" name="num_comprobante" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Número Comprobante..."> 
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>Artículo</label>
                        <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
                            @foreach($articulos as $articulo)
                            <option value="{{$articulo->idarticulo}}">{{$articulo->articulo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="Cantidad">
                    </div>    
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra</label>
                        <input type="number" name="pprecio_compra" id="pprecio_compra" class="form-control" placeholder="P. Compra">
                    </div>    
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="precio_venta">Precio de Venta</label>
                        <input type="number" name="pprecio_venta" id="pprecio_venta" class="form-control" placeholder="P. Venta">
                    </div>    
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                    </div>    
                </div>
                <div class="table-responsive col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">
                            <th>Opciones</th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Subtotal</th>
                        </thead><!-- ponemos los th vacios porque el pie debe tener las mismas celdas que la cabeza-->
                        <tfoot>
                        <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><h4 id="total">S/. 0.00</h4></th>
                        </tfoot>
                        <tbody>

                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12 " id="guardar">
            <div class="form-group">
                <input name="_token" value="{{csrf_token()}}" type="hidden"></input><!-- es un token que va a estar oculto para el usuario pero lo hacemos para que me permita trabajar con las transacciones en el controlador-->
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
    </div>
    {!!Form::close()!!}

    @push('scripts')<!-- Dentro de esta seccion push va a ir codigo javascript y se pone el nombre 'scripts' que es el que colocamos en la seccion stack('scripts') en admin.blade.php--> 
    <script>
        $(document).ready(function(){//esta funcion se ejecuta cuando se ejecuta el documento web y cuando se empiece a ejecutar cada vez que se haga click en el boton bt_add que es el que tiene la eiqueta agregar va a llamar a la funcion agregar
            $('#bt_add').click(function(){
                agregar();
            });
        });

        total=0;
        var cont=0;
        subtotal=[];// este array sirve para capturar todos los subtotales de cada una de las lineas de los detalles
        $("#guardar").hide();//le indico que cuando inicie o cargue el documento el boton guardar va a estar oculto
        
        function agregar(){
            idarticulo=$("#pidarticulo").val();//creo una variable llamada idarticulo y le asignoel valor que tiene el objeto pidarticulo 
            articulo=$("#pidarticulo option:selected").text();//articulo guardo el texto que tiene pidarticulo de la opcion que el usuario a seleccionado
            cantidad=$("#pcantidad").val();
            precio_compra=$("#pprecio_compra").val();
            precio_venta=$("#pprecio_venta").val();
            if(idarticulo!="" && cantidad!="" && cantidad>0 && precio_compra!="" && precio_venta!=""){
                subtotal[cont]=(cantidad*precio_compra);
                total=total+subtotal[cont];
                var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="precio_compra[]" value="'+precio_compra+'"></td><td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td><td>'+subtotal[cont]+'</td></tr>';//fila es una variable tipo string entonces en la tabla se va agregar una fila que va a tenerla clase selected y el id'fila' concatenada con 'cont' y ese id me va a permitir evaluar la fila que quiero eliminar, cuando de click en el boton la funcion onclick del boton me llama a la funcion eliminar pero me envia el parametro cont
                cont++;
                limpiar();//pra dejar vacis las cajas y poder meter nuevos valores
                $("#total").html("S/. "+total);//en el documento tengo un id llamado total y con la funcion html escribe $+lo que tengo en total
                evaluar();//para que muestre los botones siempre y cuando tenga un detalle en mi tabla
                $('#detalles').append(fila);//en nuestro id detalles agregue la fila
            }
            else{
                alert("Error al ingresar el detalle del ingreso, revise los datos del articulo");
            }

        }

        function limpiar(){
            $("#pcantidad").val("");//al objeto del formulario que se llama pcantidad  que lo identificamos por medio del id le enviamos el valor cadena vacia, lo mismo para las siguientes""
            $("#pprecio_compra").val("");
            $("#pprecio_venta").val("");
        }

        function evaluar(){//para no permitir que enviemos un ingreso que no tenga detalles hacia el request o al controlador para no tener despues errores
            if(total>0){
                $("#guardar").show();//el div con id guardar se va a visualizar 
            }
            else{
                $("#guardar").hide();//el div con id guardar va a estar oculto
            }
        }

        function eliminar(index){
            total=total-subtotal[index];
            $("#total").html("S/. "+total);
            $("#fila"+index).remove();
            evaluar;
        }

    </script>
    @endpush
@endsection