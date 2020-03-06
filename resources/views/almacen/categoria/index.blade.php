@extends ('layouts.admin')
@section ('contenido')

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Categorías <a href="categoria/create"><button class="btn btn-success">Nueva categoria</button></a></h3>
        @include('almacen.categoria.search')
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripcíon</th>
                    <th>Opciones</th>
                </thead>
                @foreach($categorias as $cat) <!-- se le indica al foreach que la variable 'categorias' que estoy recibiendo desde el controlador lo voy a mostrar de manera independiente en una variable llamada 'cat' -->
                    <tr>
                        <td>{{ $cat->idcategoria }}</td>
                        <td>{{ $cat->nombre }}</td>
                        <td>{{ $cat->descripcion }}</td>
                        <td>
                            <a href="{{URL::action('CategoriaController@edit',$cat->idcategoria)}}"><button class="btn btn-info">Editar</button></a>
                            <a href="" data-target="#modal-delete-{{$cat->idcategoria}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                        </td>
                    </tr>
                @include('almacen.categoria.modal') <!-- lo incluimos aqui debido a que por cada categoria se va a generar un div modal -->
                @endforeach
            </table>
        </div>
        {{$categorias->render()}} <!-- Se pone esto fuera de la tabla responsive para poder hacer la paginacion, para ello usamos la variable 'categorias' que resibimos como parametro y le decimos que utilice el metodo render que es el que nos permite paginar-->
    </div>
</div>

@endsection