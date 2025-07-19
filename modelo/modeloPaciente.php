<?php
session_start();
require "conexion.php";
//require "pChart/pData.class";
//require "pChart/pChart.class";

class Paciente_model extends Conexion
{
	private $param = array();
	public function __construct()
	{
		parent::__construct();
	}

	public function gestionar($param)
	{
		$this->param = $param;
		switch ($this->param['param_opcion']) {
			case 'realizarTest':
				return $this->realizarTest();
				break;
			case 'realizarDiagnostico':
				return $this->realizarDiagnostico();
				break;
			case 'misdatosPaciente':
				return $this->misdatosPaciente();
				break;
			case 'evaluacionPaciente':
				echo $this->evaluacionPaciente();
				break;
			case 'misEvaluaciones':
				echo $this->misEvaluaciones();
				break;
			case 'misreportesPaciente':
				echo $this->misreportesPaciente();
				break;
			case 'listaPacientes':
				echo $this->listaPacientes();
				break;
			case 'listaPacienteslike':
				echo $this->listaPacienteslike();
				break;
			case 'modificarPacientes':
				echo $this->modificarPacientes();
				break;
			case 'eliminarPacientes':
				echo $this->eliminarPacientes();
				break;
			case 'agregarPacienteaccion':
				return $this->agregarPacienteaccion();
				break;
		}
	}
	private function evaluarprolog($pares)
	{
		if (!file_exists("archivoER.pl"))
			die("No se puede localizar el archivo seic.pl, el directorio actual es: " . __DIR__);
		$X = '[';
		$i = 1;
		foreach ($pares as $par) {
			if (strlen($par) > 1) {
				list($idsintoma, $r) = explode(";", $par);
				if ($r == 's') {
					$X .= 's' . $i . ',';
				}
			}
			$i++;
		}
		if (substr($X, -1, 1) == ',')
			$X = substr($X, 0, strlen($X) - 1);
		$X .= ']';


		$output = `swipl -s archivoER.pl -g "test($X)." -t halt.`;

		return $output;
	}
	private function realizarDiagnostico()
	{
		$idpaciente = $_SESSION['param_idpaciente'];
		$pares = explode("#", $this->param['param_lista']);
		$resultado = $this->evaluarprolog($pares);
		$sql = "insert into atencion(idpaciente,fechahora,resultado) values($idpaciente,(select current_timestamp()),'$resultado')";
		mysqli_query($this->conn, $sql);

		foreach ($pares as $par) {
			if (strlen($par) > 1) {
				list($idsintoma, $r) = explode(";", $par);
				$sql = "insert into atencion_sintoma(idatencion,idsintoma,respuesta) values((select max(a.idatencion) from atencion a),$idsintoma,'$r')";
				mysqli_query($this->conn, $sql);
			}
		}
		return "<div class='resultado-diagnostico'>$resultado</div>";
	}
	private function realizarTest()
	{
		$idpaciente = $_SESSION['param_idpaciente'];
		$c = "<table class='tabla-sintomas' width='100%'>";
		$c .= "<tr><th>Síntomas</th><th>Lo padece?</th></tr>";
		$sql = "select * from sintoma order by idsintoma";
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idsintoma = $mostrar['idsintoma'];
			$sintoma = $mostrar['sintoma'];
			$c .= "<tr><td>$sintoma</td>";
			$c .= "<td><input type=\"checkbox\" name=\"sintoma\" value=\"$idsintoma\"></td></tr>";
		}
		$c .= "<tr><td></td><td><a href=\"javascript:realizardiagnostico($idpaciente)\" class='btn-evaluar'>Evaluar Diagnóstico</a></td></tr>";
		$c .= "</table>";
		$c .= "<div id='resultado'></div>";
		return $c;
	}
	private function evaluacionPaciente()
	{
		$idatencion = $this->param['param_idatencion'];
		$c = "<div class='detalle-evaluacion-card'>";
		$c .= "<h4>Detalle de Evaluación</h4>";
		$c .= "<table class='detalle-evaluacion-tabla'>";
		$c .= "<tr><th>Síntoma</th><th>Respuesta</th></tr>";
		$sql = "select a.fechahora,a.resultado,s.sintoma,upper(as_.respuesta) as respuesta from atencion a,atencion_sintoma as_,sintoma s where a.idatencion=as_.idatencion and as_.idsintoma=s.idsintoma and a.idatencion=$idatencion order by s.idsintoma";
		$resultado = mysqli_query($this->conn, $sql);
		$fechahora = '';
		$mresultado = '';
		while ($mostrar = mysqli_fetch_array($resultado)) {
			$sintoma = $mostrar['sintoma'];
			$respuesta = $mostrar['respuesta'];
			$fechahora = $mostrar['fechahora'];
			$mresultado = $mostrar['resultado'];
			$c .= "<tr><td>$sintoma</td>";
			$c .= "<td>$respuesta</td></tr>";
		}
		$c .= "</table>";
		$c .= "<div class='detalle-evaluacion-info'>";
		$c .= "<span class='detalle-evaluacion-label'>Fecha:</span> <span class='detalle-evaluacion-value'>$fechahora</span><br>";
		$c .= "<span class='detalle-evaluacion-label'>Resultado:</span> <span class='detalle-evaluacion-value'>$mresultado</span>";
		$c .= "</div>";
		$c .= "</div>";
		return $c;
	}
	private function misEvaluaciones()
	{
		$idpaciente = $this->param['param_idpaciente'];
		$c = "<table class='tabla-evaluaciones' width='100%'>";
		$c .= "<tr><th>Fecha atención</th><th>Resultado</th><th>Ver</th></tr>";
		$sql = "select idatencion,fechahora,resultado from atencion where idpaciente=$idpaciente AND resultado IS NOT NULL AND resultado <> ''";
		$resultado = mysqli_query($this->conn, $sql);
		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idatencion = $mostrar['idatencion'];
			$fechahora = $mostrar['fechahora'];
			$mresultado = $mostrar['resultado'];
			$c .= "<tr><td>$fechahora</td>";
			$c .= "<td>$mresultado</td>";
			$c .= "<td><a href=\"javascript:evalucionpaciente($idatencion)\" class='btn-evaluar-mini'>Ver</a></td></tr>";
		}
		$c .= "</table>";
		$c .= "<div id='contenidodetalle'></div>";
		return $c;
	}

	private function misreportesPaciente()
	{
		sleep(1);
		$idpaciente = $this->param['param_idpaciente'];
		$c = "<table WIDTH='100%'><tr><td>";
		$c .= "<table WIDTH='100%'>";
		$c .= "<tr><th>Resultado</th><th>Cantidad</th></tr>";
		$sql = "select resultado,count(*) as cantidad from atencion where idpaciente=$idpaciente group by resultado";
		$resultado = mysqli_query($this->conn, $sql);
		while ($mostrar = mysqli_fetch_array($resultado)) {
			$mresultado = $mostrar['resultado'];
			$cantidad = $mostrar['cantidad'];
			$c .= "<td>$mresultado</td>";
			$c .= "<td>$cantidad</td></tr>";
		}
		$c .= "</table>";
		$c .= "</td><td><img src=\"../modelo/pie.png\"></td></tr></table>";
		return $c;
	}
	private function listaPacientes()
	{
		$c = "<table WIDTH='100%'><tr><td>";
		$c .= "Buscar Peciente: <input type='text' id='txtbuscarpaciente' name='txtbuscarpaciente' onkeyup=\"checkInput(this);\" >";
		$c .= "<div id='contenidolista'><table WIDTH='100%'>";
		$c .= "<tr><th>Apellidos y nombres</th><th>DNI</th><th>Usuario</th><th>Accion</th></tr>";
		$sql = "select p.idpaciente,p.apellidos,p.nombres,p.dni,u.usuario from paciente p,medico m,usuario u where p.idmedico=m.idmedico and p.idusuario=u.idusuario and u.estado='a' and p.idmedico=" . $_SESSION['param_idmedico'];
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idpaciente = $mostrar['idpaciente'];
			$apellidosynombres = $mostrar['apellidos'] . " " . $mostrar['nombres'];
			$dni = $mostrar['dni'];
			$usuario = $mostrar['usuario'];
			$c .= "<tr><td>$apellidosynombres</td>";
			$c .= "<td>$dni</td>";
			$c .= "<td>$usuario</td>";
			$c .= "<td><a href=\"javascript:datospaciente($idpaciente)\" >Ver</a></td></tr>";
		}
		$c .= "</table></div>";
		$c .= "</td><td><div id='contenidopaciente'></div></td></tr></table>";
		return $c;
	}
	private function listaPacienteslike()
	{
		$idmedico = $_SESSION['param_idmedico'];
		$c = "<table WIDTH='100%'>";
		$c .= "<tr><th>Apellidos y nombres</th><th>DNI</th><th>Usuario</th><th>Acción</th></tr>";
		$sql = "select p.idpaciente,p.apellidos,p.nombres,p.dni,u.usuario from paciente p,medico m,usuario u where p.idmedico=m.idmedico and p.idusuario=u.idusuario and p.idmedico=$idmedico and u.estado='a' and p.apellidos like '%" . $this->param['param_q'] . "%'";
		$resultado = mysqli_query($this->conn, $sql);
		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idpaciente = $mostrar['idpaciente'];
			$apellidosynombres = $mostrar['apellidos'] . " " . $mostrar['nombres'];
			$dni = $mostrar['dni'];
			$usuario = $mostrar['usuario'];
			$c .= "<tr><td>$apellidosynombres</td>";
			$c .= "<td>$dni</td>";
			$c .= "<td>$usuario</td>";
			$c .= "<td><a href=\"javascript:datosPaciente($idpaciente)\" >Ver</a></td></tr>";
		}
		$c .= "</table>";
		return $c;
	}
	private function misdatosPaciente()
	{
		$idpaciente = $this->param['param_idpaciente'];
		$c = '<div class="datos-medico-card">'; // Reutilizamos la misma clase para mantener el estilo
		$c .= '<h3>Datos del Docente</h3>';
		$c .= '<ul class="datos-medico-list">';
		$sql = "select p.idpaciente,p.apellidos,p.nombres,p.dni,u.usuario from paciente p,usuario u where p.idusuario=u.idusuario and p.idpaciente=$idpaciente";
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$apellidosynombres = $mostrar['apellidos'] . " " . $mostrar['nombres'];
			$dni = $mostrar['dni'];
			$usuario = $mostrar['usuario'];
			$c .= "<li><span class='datos-medico-label'>ID Docente:</span> <span class='datos-medico-value'>$idpaciente</span></li>";
			$c .= "<li><span class='datos-medico-label'>Apellidos y Nombres:</span> <span class='datos-medico-value'>$apellidosynombres</span></li>";
			$c .= "<li><span class='datos-medico-label'>DNI:</span> <span class='datos-medico-value'>$dni</span></li>";
			$c .= "<li><span class='datos-medico-label'>Usuario:</span> <span class='datos-medico-value'>$usuario</span></li>";
		}
		$c .= '</ul>';
		$c .= '</div>';
		return $c;
	}
	private function datosPaciente22()
	{
		$idpaciente = $this->param['param_idpaciente'];
		$c = "<table>";
		$sql = "select p.idpaciente,p.apellidos,p.nombres,p.dni,u.usuario from paciente p,usuario u where p.idusuario=u.idusuario and p.idpaciente=$idpaciente";
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idpaciente = $mostrar['idpaciente'];
			$apellidos = $mostrar['apellidos'];
			$nombres = $mostrar['nombres'];
			$dni = $mostrar['dni'];
			$usuario = $mostrar['usuario'];
			$c .= "<tr><td>IdPaciente:</td><td>$idpaciente</td></tr>";
			$c .= "<tr><td>Apellidos:</td><td><input type='text' id='txtapellidos'  value='$apellidos'></td></tr>";
			$c .= "<tr><td>Nombres:</td><td><input type='text' id='txtnombres' name='txtnombres' value='$nombres'></td></tr>";
			$c .= "<tr><td>DNI:</td><td><input type='text' id='txtdni' name='txtdni' value='$dni'></td></tr>";
			$c .= "<tr><td>Usuario:</td><td><input type='text' id='txtusuario' name='txtusuario' value='$usuario'></td></tr>";
			//$c .= "<tr><td></td><td><a href=\"javascript:modificarPacienteaccion($idpaciente)\" >Modificar</a>-  -<a href=\"javascript:eliminarPacienteaccion($idpaciente)\" >Eliminar</a></td></tr>";
		}
		$c .= "</table>";
		return $c;
	}

	private function agregarPacienteaccion()
	{
		$idmedico = $_SESSION['param_idmedico'];
		$apellidos = $this->param['param_apellidos'];
		$nombres = $this->param['param_nombres'];
		$dni = $this->param['param_dni'];
		$usuario = $this->param['param_usuario'];
		$sql = "insert into usuario(usuario,clave,rol,estado) values('$usuario',md5('$usuario'),'p','a')";
		mysqli_query($this->conn, $sql);
		sleep(1);
		$sql = "insert into paciente(idmedico,apellidos,nombres,dni,fechahora,idusuario) values($idmedico,'$apellidos','$nombres','$dni',(select current_timestamp()),(select max(u.idusuario) from usuario u))";
		mysqli_query($this->conn, $sql);
		$this->param['param_q'] = '';
		return $this->listaPacienteslike();
	}
	private function modificarPacientes()
	{
		$idpaciente = $this->param['param_idpaciente'];
		$apellidos = $this->param['param_apellidos'];
		$nombres = $this->param['param_nombres'];
		$dni = $this->param['param_dni'];
		$usuario = $this->param['param_usuario'];
		$sql = "update paciente set apellidos='$apellidos',nombres='$nombres',dni='$dni' where idpaciente=$idpaciente";
		mysqli_query($this->conn, $sql);
		$sql = "update usuario set usuario.usuario='$usuario' where usuario.idusuario=(select paciente.idusuario from paciente where paciente.idpaciente=$idpaciente)";
		mysqli_query($this->conn, $sql);
		$this->param['param_q'] = '';
		return $this->listaPacienteslike();
	}
	private function eliminarPacientes()
	{
		$idpaciente = $this->param['param_idpaciente'];
		//$sql = "delete from paciente where idpaciente=$idpaciente";
		$sql = "update usuario set estado ='i' where idusuario=(select p.idusuario from paciente p where p.idpaciente=$idpaciente)";
		mysqli_query($this->conn, $sql);
		$this->param['param_q'] = '';
		return $this->listaPacienteslike();
	}
}
