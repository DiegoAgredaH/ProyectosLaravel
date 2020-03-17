@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar Artículo: {{$articulo->nombre}}</h3> <!-- le damos categoria->nombre porque en el controlador CategoriaController el metodo edit nos retorna un objeto llamado 'categoria' -->
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
    {!!Form::model($articulo,['method'=>'PATCH','route'=>['articulo.update',$articulo->idarticulo],'files'=>'true'])!!}   <!-- Se utiliza el metodo PATCH porque es el encargado de llamar al metodo update en el controllador CategoriaController, y en route revisamos como es el nombre de la ruta que queremos con php artisan route:list -->
    {!!Form::token()!!}
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required value="{{$articulo->nombre}}" class="form-control" > <!-- El objeto llamado 'nombre' va a ser recibido por ArticuloFormRequest en el metodo rules y tambien va a ser resibido por ArticuloController en el metodo store-->
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label>Categoría</label>
                <select name="idcategoria" class="form-control">
                    @foreach($categorias as $cat)
                        @if($cat->idcategoria == $articulo->idcategoria)<!-- Si el idcategoria es el mismo muestra la actegoria seleccionada sino no la muestra pero no la selecciona -->
                        <option value="{{$cat->idcategoria}}" selected>{{$cat->nombre}}</option>
                        @else
                        <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" required value="{{$articulo->codigo}}" class="form-control" > <!-- El objeto llamado 'nombre' va a ser recibido por CategoriaFormRequest e el metodo rules y tambien va a ser resibido por CatgoriaController en el metodo store-->
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="text" name="stock" required value="{{$articulo->stock}}" class="form-control" > <!-- El objeto llamado 'nombre' va a ser recibido por CategoriaFormRequest e el metodo rules y tambien va a ser resibido por CatgoriaController en el metodo store-->
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" value="{{$articulo->descripcion}}" class="form-control" placeholder="Descripción del artículo..."> <!-- El objeto llamado 'nombre' va a ser recibido por CategoriaFormRequest e el metodo rules y tambien va a ser resibido por CatgoriaController en el metodo store-->
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" name="imagen" class="form-control">            
                @if(($articulo->imagen)!="")<!-- Si es diferente de vacio quiere decir que ya existia una imagen subida -->
                    <img src="{{asset('imagenes/articulos/'.$articulo->imagen)}}" height="300px" width="300px">
                @endif
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
    </div>  
    {!!Form::close()!!}
@endsection