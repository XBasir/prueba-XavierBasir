<!DOCTYPE html>
<html>
 <head>
  <title>Importar datos de excel en Laravel</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
  <style>
    body{
        font-family: 'Open Sans', sans-serif;
    }

    header{
        background-color: #353839;
    }

    footer{
        background-color: #353839;
        height: 120px;
    }
  </style>
 </head>
 <body>
    <header class="text-white py-4">
        <h2 align="center" class="text-uppercase">Importar datos de excel en Laravel</h2>
            <h3 align="center" >Prueba hecha por <a href="http://xbasir.github.io/cv-portafolio/" target="_blank" class="text-warning">Xavier Basir</a> </h3>
            <br />
    @if(count($errors) > 0)
        <div class="alert alert-danger">
        UPS! Hubo un error :(<br><br>
        <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
        </ul>
        </div>
    @endif

    @if($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
    </div>
    @endif

    @if($message = Session::get('danger'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
    </div>
    @endif
    
        <form method="post" enctype="multipart/form-data" action="{{ url('/import') }}">
        {{ csrf_field() }}
        <div class="form-group">
        <table class="table" >
        <tr>
        <td width="40%" align="right"><label class="mt-1">Seleccionar Archivo <span class="text-warning">formatos .xls, xslx</span></td>
        <td width="30">
            <input type="file" name="select_file" />
        </td>
        <td width="30%" align="left">
            <input type="submit" name="Subir" class="btn btn-success" value="Importar" style="margin-top: -4px !important;">
        </td>
        </tr>
        <tr>
        <td width="40%" align="right"></td>
        <td width="30"></td>
        <td width="30%" align="left"></td>
        </tr>
        </table>
        </div>
    </form>
	</header>

   
   <br />
   <div class="container">
   <h2>Empleados importados del último excel</h2>
   <table id="empleados" class="table table-striped table-bordered" style="width:100%; white-space:nowrap;" >
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido 1</th>
                <th>Apellido 2</th>
                <th>IFE</th>
                <th>Clave elector</th>
                <th>RFC</th>
                <th>Tel</th>
                <th>CURP</th>
                <th>IMSS</th>
                <th>Fecha contrato</th>
                <th>Fecha Nacimiento</th>
                <th>ID Empresa</th>
                <th>ID Sexo</th>
                <th>ID Estado Civil</th>
                <th>Entidad </th>
                <th>Municipio</th>
                <th>Colonia</th>
                <th>Nacionalidad</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
        <tr>
                <td class="bg-warning">{{ $row->nombres }}</td>
                <td class="bg-warning">{{ $row->apellido_paterno_ }}</td>
                <td>{{ $row->apellido_materno }}</td>
                <td>{{ $row->clave_del_ife }}</td>
                <td>{{ $row->clave_de_elector }}</td>
                <td>{{ $row->rfc}}</td>
                <td>{{ $row->telefono}}</td>
                <td>{{ $row->curp}}</td>
                <td>{{ $row->afiliacion_a_imss}}</td>
                <td>{{ $row->fecha_de_contrato}}</td>
                <td>{{ $row->fecha_de_nacimiento}}</td>
                <td class="bg-warning">({{ $row->empresa }})
                @foreach($empresa as $e)
                    @if($row->empresa == $e->id)
                        {{$e->nombre}}
                    @endif
                @endforeach
                </td>
                <td class="bg-warning">({{ $row->sexo }})
                @foreach($sexo as $s)
                    @if($row->sexo == $s->id)
                        {{$s->nombre}}
                    @endif
                @endforeach</td>
                <td class="bg-warning">({{ $row->estado_civil }}) 
                @foreach($estado_civil as $e)
                    @if($row->estado_civil == $e->id)
                        {{$e->nombre}}
                    @endif
                @endforeach</td>
                <td>{{ $row->entidad_de_nacimiento }}</td>
                <td>{{ $row->municipio_de_nacimiento }}</td>
                <td>{{ $row->colonia_de_nacimiento_ }}</td>
                <td>{{ $row->modo_de_nacionalidad }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <br><br>
    <h2>Filas rechazadas del último excel</h2>
    <table id="rechazados" class="table table-striped table-bordered" style="width:100%; margin: auto; white-space:nowrap;">
        <thead>
            <tr>
                <th>Descripcion de Rechazo</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rejected_data as $row)
        <tr>
                <td  class="bg-danger text-white">{{ $row->nombre}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

  </div>
  <footer class="mt-5 mb-0 pb-0">
    <h4 align="center" class="text-warning pt-5"><a href="http://xbasir.github.io/cv-portafolio/" target="_blank" class="text-warning text-uppercase">Conoce más de mi aquí!</a></h4>
    
  </footer>
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <script>
        $(document).ready(function() {
                $('#empleados').DataTable( {
                        "scrollX": true,
                        "language": {
                        "info": "_TOTAL_ registros",
                        "search": "Buscar",
                        "paginate": {
                            "next": "Siguiente",
                            "previous": "Anterior",
                        },
                        "lengthMenu": 'Mostrar <select >'+
                                    '<option value="10">10</option>'+
                                    '<option value="30">30</option>'+
                                    '<option value="-1">Todos</option>'+
                                    '</select> registros',
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "emptyTable": "No hay datos",
                        "zeroRecords": "No hay coincidencias", 
                        "infoEmpty": "",
                        "infoFiltered": ""
                         }
                } );
                $('#rechazados').DataTable( {
                        "ordering": false,
                        "scrollX": true,
                        "language": {
                        "info": "_TOTAL_ registros",
                        "search": "Buscar",
                        "paginate": {
                            "next": "Siguiente",
                            "previous": "Anterior",
                        },
                        "lengthMenu": 'Mostrar <select >'+
                                    '<option value="10">10</option>'+
                                    '<option value="30">30</option>'+
                                    '<option value="-1">Todos</option>'+
                                    '</select> registros',
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "emptyTable": "No hay datos",
                        "zeroRecords": "No hay coincidencias", 
                        "infoEmpty": "",
                        "infoFiltered": ""
                         }
                } );
        } );
  </script>
 </body>
</html>
