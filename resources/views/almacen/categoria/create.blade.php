@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nueva Categoría</h3>
            @if(count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error) <!-- El foreach resibe todos los errores que me envia el archivo CategoriaFormRequest-y los almacenara en el objeto 'error' -->
                    <li>{{$error}}</li> <!-- lista error por error -->
                @endforeach
                </ul>
            </div>
            @endif

            {!!Form::open(array('url'=>'almacen/categoria','method'=>'POST','autocomplete'=>'off'))!!}
            {!!Form::token()!!}
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre..."> <!-- El objeto llamado 'nombre' va a ser recibido por CategoriaFormRequest e el metodo rules y tambien va a ser resibido por CatgoriaController en el metodo store-->
            </div>
            <div class="form-group">
                <label for="descripcion">Descripcíon</label>
                <input type="text" name="descripcion" class="form-control" placeholder="Descripcíon..."> <!-- El objeto llamado 'descripcion' va a ser recibido por CategoriaFormRequest e el metodo rules y tambien va a ser resibido por CatgoriaController en el metodo store-->
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
@endsection