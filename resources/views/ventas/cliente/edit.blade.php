@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar Cliente: {{$persona->nombre}}</h3> <!-- le damos persona->nombre porque en el controlador ClienteController el metodo edit nos retorna un objeto llamado 'persona' -->
            @if(count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error) <!-- El foreach resibe todos los errores que me envia el archivo PersonaFormRequest-y los almacenara en el objeto 'error' -->
                    <li>{{$error}}</li> <!-- lista error por error -->
                @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    {!!Form::model($persona,['method'=>'PATCH','route'=>['cliente.update',$persona->idpersona]])!!}   <!-- Se utiliza el metodo PATCH porque es el encargado de llamar al metodo update en el controllador ClienteController, y en route revisamos como es el nombre de la ruta que queremos con php artisan route:list -->
    {!!Form::token()!!}
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required value="{{$persona->nombre}}" class="form-control" placeholder="Nombre..."> <!-- El objeto llamado 'nombre' va a ser recibido por PersonaFormRequest en el metodo rules y tambien va a ser resibido por ClienteController en el metodo store-->
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" value="{{$persona->direccion}}" class="form-control" placeholder="Dirección..."> 
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label>Documento</label>
                <select name="tipo_documento" class="form-control">
                    @if($persona->tipo_documento=='DNI')<!-- Si el tipo de documento de persona es DNI entonces saldra seleccionada la opcion DNI y las otras opciones tambien van a aparecer pero no estaran seleccionadas -->
                        <option value="DNI" selected>DNI</option>
                        <option value="RUC">RUC</option>
                        <option value="PAS">PAS</option>
                    @elseif($persona->tipo_documento=='RUC')
                        <option value="DNI">DNI</option>
                        <option value="RUC" selected>RUC</option>
                        <option value="PAS">PAS</option>
                    @else
                        <option value="DNI">DNI</option>
                        <option value="RUC">RUC</option>
                        <option value="PAS" selected>PAS</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="num_documento">Número de documento</label>
                <input type="text" name="num_documento" value="{{$persona->num_documento}}" class="form-control" placeholder="Número de documento..."> 
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" value="{{$persona->telefono}}" class="form-control" placeholder="Teléfono..."> 
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" value="{{$persona->email}}" class="form-control" placeholder="Email..."> 
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