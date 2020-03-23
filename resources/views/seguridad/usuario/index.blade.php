@extends ('layouts.admin')
@section ('contenido')

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Usuarios <a href="usuario/create"><button class="btn btn-success">Nuevo Usuario</button></a></h3>
        @include('seguridad.usuario.search')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Opciones</th>
                </thead>
                @foreach($usuarios as $usu) <!-- se le indica al foreach que la variable 'categorias' que estoy recibiendo desde el controlador lo voy a mostrar de manera independiente en una variable llamada 'cat' -->
                    <tr>
                        <td>{{ $usu->id }}</td>
                        <td>{{ $usu->name }}</td>
                        <td>{{ $usu->email }}</td>
                        <td>
                            <a href="{{URL::action('UsuarioController@edit',$usu->id)}}"><button class="btn btn-info">Editar</button></a>
                            <a href="" data-target="#modal-delete-{{$usu->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                        </td>
                    </tr>
                @include('seguridad.usuario.modal') <!-- lo incluimos aqui debido a que por cada categoria se va a generar un div modal -->
                @endforeach
            </table>
        </div>
        {{$usuarios->render()}} <!-- Se pone esto fuera de la tabla responsive para poder hacer la paginacion, para ello usamos la variable 'categorias' que resibimos como parametro y le decimos que utilice el metodo render que es el que nos permite paginar-->
    </div>
</div>

@endsection