@extends ('layouts.admin')
@section ('contenido')

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Cliientes <a href="cliente/create"><button class="btn btn-success">Nuevo Cliente</button></a></h3>
        @include('ventas.cliente.search')
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tipo Doc.</th>
                    <th>Número Doc.</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Opciones</th>
                </thead>
                @foreach($personas as $per) <!-- se le indica al foreach que la variable 'articulos' que estoy recibiendo desde el controlador en el metodo index lo voy a mostrar de manera independiente en una variable llamada 'art' -->
                    <tr>
                        <td>{{ $per->idpersona }}</td>
                        <td>{{ $per->nombre }}</td>
                        <td>{{ $per->tipo_documento }}</td>
                        <td>{{ $per->num_documento }}</td>
                        <td>{{ $per->telefono }}</td>
                        <td>{{ $per->email }}</td>
                        <td>
                            <a href="{{URL::action('ClienteController@edit',$per->idpersona)}}"><button class="btn btn-info">Editar</button></a>
                            <a href="" data-target="#modal-delete-{{$per->idpersona}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a><!-- cuando de click en el boton eliminar se abrira un modal que especificamos por medio de art->idarticulo -->
                        </td>
                    </tr>
                @include('ventas.cliente.modal') <!-- lo incluimos aqui debido a que por cada articulo se va a generar un div modal -->
                @endforeach
            </table>
        </div>
        {{$personas->render()}} <!-- Se pone esto fuera de la tabla responsive para poder hacer la paginacion, para ello usamos la variable 'personas' que resibimos como parametro y le decimos que utilice el metodo render que es el que nos permite paginar-->
    </div>
</div>

@endsection