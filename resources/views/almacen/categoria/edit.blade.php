@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar Categoría: {{$categoria->nombre}}</h3> <!-- le damos categoria->nombre porque en el controlador CategoriaController el metodo edit nos retorna un objeto llamado 'categoria' -->
            @if(count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error) <!-- El foreach resibe todos los errores que me envia el archivo CategoriaFormRequest-y los almacenara en el objeto 'error' -->
                    <li>{{$error}}</li> <!-- lista error por error -->
                @endforeach
                </ul>
            </div>
            @endif

            {!!Form::model($categoria,['method'=>'PATCH','route'=>['categoria.update',$categoria->idcategoria]])!!}   <!-- Se utiliza el metodo PATCH porque es el encargado de llamar al metodo update en el controllador CategoriaController, y en route revisamos como es el nombre de la ruta que queremos con php artisan route:list -->
            {!!Form::token()!!}
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{$categoria->nombre}}" > <!-- El objeto llamado 'nombre' va a ser recibido por CategoriaFormRequest e el metodo rules y tambien va a ser resibido por CatgoriaController en el metodo store, como estamos editando en value colocamos el nombre de la categoria que estamos recibiendo desde el controlador para que nos muestre lo que tenemos actualmente-->
            </div>
            <div class="form-group">
                <label for="descripcion">Descripcíon</label>
                <input type="text" name="descripcion" class="form-control" value="{{$categoria->descripcion}}" > <!-- El objeto llamado 'descripcion' va a ser recibido por CategoriaFormRequest e el metodo rules y tambien va a ser resibido por CatgoriaController en el metodo store, como estamos editando en value colocamos la descripcion de la categoria que estamos recibiendo desde el controlador para que nos muestre lo que tenemos actualmente-->
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
@endsection