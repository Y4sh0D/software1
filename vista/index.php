<?php 
  session_start();  
  if(!isset($_SESSION['param_rol']))
  {
    header("Location:../../index.php");
  }
  else
  {
    date_default_timezone_set('America/Lima');
 ?>
<!doctype html>
<html lang="en">
  <head>
  	<title>Sistema Experto - Infarto cerebral</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
  </head>
  <body>
		<form method="post" name="postForm">
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="p-4 pt-5">
		  		<a href="#" class="img logo rounded-circle mb-5" style="background-image: url(images/logoclinica.png);"></a>
	        <ul class="list-unstyled components mb-5">
			<?php
				if ($_SESSION['param_rol']=='m'){
			?>	
	          <li>
				<a href="javascript:inicio()" >Inicio</a>
	          </li>			  
	          <li>
				<a href="javascript:misdatosmedico(<?php echo $_SESSION['param_idmedico'];?>)" >Mis Datos</a>
			  </li>
			  <li>
				<a href="javascript:medicoagregarpaciente()" >Agregar Docente</a>
			  </li>
			  <li>
				<a href="javascript:listapacientes(<?php echo $_SESSION['param_idmedico'];?>)" >Listar Docentes</a>
	          </li>		  
			  <li>
				<a href="javascript:salirmedico()" >Salir</a>
	          </li>
			  <?php //paciente
				}else{
				?>
				<li>
				<a href="javascript:inicio()" >Inicio</a>
	          </li>
	          <li>
				<a href="javascript:misdatospaciente(<?php echo $_SESSION['param_idpaciente'];?>)" >Mis Datos</a>
			  </li>
	          <li>
				<a href="javascript:salirpaciente()" >Salir</a>
	          </li>				
				<?php
				}
				?>	          
	        </ul>

	        <div class="footer">
	        	<p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Curso: Ingeniería de Software
						  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
	        </div>

	      </div>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Bienvenido:<br><b><?php echo ($_SESSION['param_rol']=='m'?"Administrador: ":"Docente: ").strtoupper($_SESSION['param_apellidos'])." ".strtoupper($_SESSION['param_nombres']); ?></b></a>
                </li>                
              </ul>
            </div>
          </div>
        </nav>
		<div id="contenido">
        
		</div><div id="contenidox">
        
		</div>
      </div>
		</div>
		<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
	
  </form>
	</body>
</html>
 <script>
 
 $( "#contenido" ).load( "contenidoinicio.php", function( response, status, xhr ) {
  if ( status == "error" ) {
    var msg = "Hubo error: ";
    $( "#contenido" ).html( msg + xhr.status + " " + xhr.statusText );
  }
});

 function salirpaciente(){
	 $.ajax({		 
		url:   '../controlador/salirPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				//$("#contenido").html(response);
				window.location= '../index.html';
		}
	 });
 }
 function salirmedico(){
	 $.ajax({		 
		url:   '../controlador/salirMedico.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				//$("#contenido").html(response);
				window.location= '../index.html';
		}
	 });
 }
 function medicoagregarpaciente(){
	 $.ajax({		 
		url:   '../vista/agregarpaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
		}
	 });
 }
 function inicio(){
	 window.location= 'index.php';
 }
 
 function realizartest(idpaciente){
	 $.ajax({
		data:  {param_idpaciente: idpaciente,param_opcion:'realizarTest'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
		}
	 });
 }
 function realizardiagnostico(idpaciente){
	 var lista='';
	 var cantidad = 0;
	$("input:checkbox").each(   
		function() {
			if($(this).is(":checked")){
				lista = lista+$(this).val()+';s#';
				cantidad = cantidad + 1;
			}else{
				lista = lista+$(this).val()+';n#';
			}
		}
	);
	if(cantidad>0){
		$.ajax({
		data:  {param_lista: lista,param_idpaciente: idpaciente,param_opcion:'realizarDiagnostico'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#resultado").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#resultado").html(response);
		}
		});
	}else{
		alert('Debe seleccionar sintomas');	
	}
 }
 function evalucionpaciente(idatencion){
	 $.ajax({
		data:  {param_idatencion: idatencion,param_opcion:'evaluacionPaciente'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenidodetalle").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenidodetalle").html(response);
		}
	 });
 }
function misevaluaciones(idpaciente){
	 $.ajax({
		data:  {param_idpaciente: idpaciente,param_opcion:'misEvaluaciones'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
		}
	 });
 }


function listapacientes(idmedico){
	 $.ajax({
		data:  {param_idmedico: idmedico,param_opcion:'listaPacientes'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlMedico.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
		}
	 });
 }
 function listasintomas(){
	 $.ajax({
		data:  {param_opcion:'listaSintomas'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlSintoma.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
		}
	 });
 }
 
 function eliminarSintomaaccion(idsintoma){
	 var sintoma = $( "#txtsintoma" ).val();
	 var confirmAction = confirm("Confirma eliminar a: "+sintoma+" ?");
	 if(confirmAction){
		 $.ajax({
			data:  {param_idsintoma: idsintoma,
					param_opcion:'eliminarSintoma'		
			}, //datos que se envian a traves de ajax
			url:   '../controlador/controlSintoma.php', //archivo que recibe la peticion
			type:  'post', //método de envio
			beforeSend: function () {					
				$("#contenido").html("Procesando, espere por favor...");
			},
			success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
					$("#contenido").html(response);
					$("#contenidosintoma").html('');
			} 
		 });
	 }
 }
function modificarSintomaaccion(idsintoma){
	 var sintoma = $( "#txtsintoma" ).val();
	 $.ajax({
		data:  {param_idsintoma: idsintoma,
				param_opcion:'modificarSintoma',
				param_sintoma:sintoma		
		}, //datos que se envian a traves de ajax
		url:   '../controlador/controlSintoma.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
				$("#contenidosintoma").html('');
		} 
	});
 }
 function modificarPacienteaccion(idpaciente){
	 var apellidos = $( "#txtapellidos" ).val();
	 var nombres = $( "#txtnombres" ).val();
	 var dni = $( "#txtdni" ).val();
	 var usuario = $( "#txtusuario" ).val();
	 if (apellidos.length==0){
		 alert('Error en el ingreso de apellidos');
		 return;
	 }
	 if (nombres.length==0){
		 alert('Error en el ingreso de nombres');
		 return;
	 }
	 if (dni.length!=8){
		 alert('Error en el ingreso de dni');
		 return;
	 }
	 if (usuario.length==0){
		 alert('Error en el ingreso de usuario');
		 return;
	 }
	 $.ajax({
		data:  {param_idpaciente: idpaciente,
		param_opcion:'modificarPacientes',
		param_apellidos:apellidos,
		param_nombres:nombres,
		param_dni:dni,
		param_usuario:usuario		
		}, //datos que se envian a traves de ajax
		url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenidolista").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenidolista").html(response);
				$("#contenidopaciente").html('');
		} 
	});
 }	
 function eliminarPacienteaccion(idpaciente){
	 var apellidos = $( "#txtapellidos" ).val();
	 var nombres = $( "#txtnombres" ).val();
	 var confirmAction = confirm("Confirma eliminar a: "+apellidos+" "+nombres+" ?");
	 if(confirmAction){
		 $.ajax({
			data:  {param_idpaciente: idpaciente,
			param_opcion:'eliminarPacientes'					
			}, //datos que se envian a traves de ajax
			url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
			type:  'post', //método de envio
			beforeSend: function () {					
				$("#contenidolista").html("Procesando, espere por favor...");
			},
			success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
					$("#contenidolista").html(response);
					$("#contenidopaciente").html('');
			} 
		 });
	 }
 }	
function checkInput(obj,idmedico) {
    $.ajax({
		data:  {param_idmedico: idmedico,param_opcion:'listaPacienteslike',param_q : obj.value}, //datos que se envian a traves de ajax
		url:   '../controlador/controlMedico.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenidolista").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenidolista").html(response);
				$("#contenidopaciente").html('');
		}
	});	
}
 
function misdatospaciente(idpaciente){
	$.ajax({
		data:  {param_idpaciente: idpaciente,param_opcion:'misdatosPaciente'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
		}
	});
}
function misdatosmedico(idmedico){	
	$.ajax({
		data:  {param_idmedico: idmedico,param_opcion:'misdatosMedico'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlMedico.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenido").html(response);
		}
	});
}
function datosPaciente(idpaciente){
	$.ajax({
		data:  {param_idpaciente: idpaciente,param_opcion:'datosPaciente'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlMedico.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenidopaciente").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenidopaciente").html(response);
		}
	});
}
function datosSintoma(idsintoma){
	$.ajax({
		data:  {param_idsintoma: idsintoma,param_opcion:'datosSintoma'}, //datos que se envian a traves de ajax
		url:   '../controlador/controlSintoma.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenidosintoma").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve				
				$("#contenidosintoma").html(response);
		}
	});
}
function agregarPacienteaccion(){
	var apellidos = $( "#txtapellidos" ).val();
	 var nombres = $( "#txtnombres" ).val();
	 var dni = $( "#txtdni" ).val();
	 var usuario = $( "#txtusuario" ).val();
	 if (apellidos.length==0){
		 alert('Error en el ingreso de apellidos');
		 return;
	 }
	 if (nombres.length==0){
		 alert('Error en el ingreso de nombres');
		 return;
	 }
	 if (dni.length!=8){
		 alert('Error en el ingreso de dni');
		 return;
	 }
	 if (usuario.length==0){
		 alert('Error en el ingreso de usuario');
		 return;
	 }
	$.ajax({		
		data:  {
		param_opcion:'agregarPacienteaccion',
		param_apellidos:apellidos,
		param_nombres:nombres,
		param_dni:dni,
		param_usuario:usuario		
		}
		, //datos que se envian a traves de ajax
		url:   '../controlador/controlPaciente.php', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {					
			$("#contenido").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve	
		window.location= 'index.php'		
				//$("#contenido").html(response);
		}
	});
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function lettersOnly(){
	var charCode = event.keyCode;
	if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 8 || charCode == 32)
		return true;
	else
		return false;
}
  </script>
<?php } ?>
