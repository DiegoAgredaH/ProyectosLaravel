@extends ('layouts.admin')
@section ('contenido')

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Ingresos <a href="ingreso/create"><button class="btn btn-success">Nuevo Ingreso</button></a></h3>
        @include('compras.ingreso.search')
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Comprobante</th>
                    <th>Impuesto</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                @foreach($ingresos as $ing) <!-- se le indica al foreach que la variable 'articulos' que estoy recibiendo desde el controlador en el metodo index lo voy a mostrar de manera independiente en una variable llamada 'art' -->
                    <tr>
                        <td>{{ $ing->fecha_hora }}</td>
                        <td>{{ $ing->nombre }}</td>
                        <td>{{ $ing->tipo_comprobante.': '.$ing->serie_comprobante.'-'.$ing->num_comprobante}}</td>
                        <td>{{ $ing->impuesto }}</td>
                        <td>{{ $ing->total }}</td>
                        <td>{{ $ing->estado }}</td>
                        <td>
                            <a href="{{URL::action('IngresoController@show',$ing->idingreso)}}"><button class="btn btn-primary">Detalles</button></a>
                            <a href="" data-target="#modal-delete-{{$ing->idingreso}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a><!-- cuando de click en el boton eliminar se abrira un modal que especificamos por medio de art->idarticulo -->
                        </td>
                    </tr>
                @include('compras.ingreso.modal') <!-- lo incluimos aqui debido a que por cada articulo se va a generar un div modal -->
                @endforeach
            </table>
        </div>
        {{$ingresos->render()}} <!-- Se pone esto fuera de la tabla responsive para poder hacer la paginacion, para ello usamos la variable 'personas' que resibimos como parametro y le decimos que utilice el metodo render que es el que nos permite paginar-->
    </div>
</div>

@endsection