@extends ('layouts.admin')
@section ('contenido')

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Artículos <a href="articulo/create"><button class="btn btn-success">Nuevo Artículo</button></a></h3>
        @include('almacen.articulo.search')
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                @foreach($articulos as $art) <!-- se le indica al foreach que la variable 'articulos' que estoy recibiendo desde el controlador en el metodo index lo voy a mostrar de manera independiente en una variable llamada 'art' -->
                    <tr>
                        <td>{{ $art->idarticulo }}</td>
                        <td>{{ $art->nombre }}</td>
                        <td>{{ $art->codigo }}</td>
                        <td>{{ $art->categoria }}</td>
                        <td>{{ $art->stock }}</td>
                        <td>
                            <img src="{{asset('imagenes/articulos/'.$art->imagen)}}" alt="{{ $art->nombre }}" height="100px" width="100px" class="img-thumbnail"> <!-- traemos la imagen desde la carpeta publica por medio de asset y nos vamos a la carpeta especificada y abrimos el archivo que tiene el nombre que tiene art->imagen y si no encuentra la imagen mueestra lo que le colocamos en alt en este caso art->nombre -->
                        </td>
                        <td>{{ $art->estado }}</td>
                        <td>
                            <a href="{{URL::action('ArticuloController@edit',$art->idarticulo)}}"><button class="btn btn-info">Editar</button></a>
                            <a href="" data-target="#modal-delete-{{$art->idarticulo}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a><!-- cuando de click en el boton eliminar se abrira un modal que especificamos por medio de art->idarticulo -->
                        </td>
                    </tr>
                @include('almacen.articulo.modal') <!-- lo incluimos aqui debido a que por cada articulo se va a generar un div modal -->
                @endforeach
            </table>
        </div>
        {{$articulos->render()}} <!-- Se pone esto fuera de la tabla responsive para poder hacer la paginacion, para ello usamos la variable 'articulos' que resibimos como parametro y le decimos que utilice el metodo render que es el que nos permite paginar-->
    </div>
</div>

@endsection