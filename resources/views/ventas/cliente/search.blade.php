<!-- Este no incluye a nuesta plantilla principal admin.blade porque esta vista va a estar incluida dentro de la vista index.blade entonces simplemente agregamos un formulario-->
<!-- Lo siguiente quiere decir que abrimos nuestro formulario y le enviamos losparametros
url es donde va a redireccionar este formulario y le va a enviar el parametro de busqueda por medio de esta url y se va a enviar con metodo Get osea enviado en la url y nos permitira filtar los articulos -->
{!! Form::open(array('url'=>'ventas/cliente','method'=>'GET','autocomplete'=>'off','role'=>'search'))!!}
<div class="form-group">
    <div class="input-group">
        <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}"> <!-- El nombre de este input es searchText porque nuestro controlador en el metodo index espera un objeto llamado searchText -->
        <span class="input-group-btn">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </span>
    </div>
</div>
{{Form::close()}}