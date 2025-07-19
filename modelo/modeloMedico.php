<?php
session_start();
require "conexion.php";

class Medico_model extends Conexion
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
			case 'misdatosMedico':
				echo $this->misdatosMedico();
				break;
			case 'datosPaciente':
				echo $this->datosPaciente();
				break;
			case 'listaPacientes':
				echo $this->listaPacientes();
				break;
			case 'listaPacienteslike':
				echo $this->listaPacienteslike();
				break;
		}
	}

	private function datosPaciente()
	{
		$idpaciente = $this->param['param_idpaciente'];
		$c = "<div class='detalle-evaluacion-card'>";
		$c .= "<h4>Editar Docente</h4>";
		$c .= "<form>";
		$sql = "select p.idpaciente,p.apellidos,p.nombres,p.dni,u.usuario from paciente p,usuario u where p.idusuario=u.idusuario and p.idpaciente=$idpaciente";
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idpaciente = $mostrar['idpaciente'];
			$apellidos = $mostrar['apellidos'];
			$nombres = $mostrar['nombres'];
			$dni = $mostrar['dni'];
			$usuario = $mostrar['usuario'];
			$c .= "<div class='form-group'><label>ID Docente:</label><span class='datos-medico-value'>$idpaciente</span></div>";
			$c .= "<div class='form-group'><label>Apellidos:</label><input type='text' id='txtapellidos' class='form-control' value='$apellidos'></div>";
			$c .= "<div class='form-group'><label>Nombres:</label><input type='text' id='txtnombres' class='form-control' value='$nombres'></div>";
			$c .= "<div class='form-group'><label>DNI:</label><input type='text' id='txtdni' class='form-control' value='$dni'></div>";
			$c .= "<div class='form-group'><label>Usuario:</label><input type='text' id='txtusuario' class='form-control' value='$usuario'></div>";
			$c .= "<div class='text-center' style='margin-top:1.2rem;'>";
			$c .= "<a href=\"javascript:modificarPacienteaccion($idpaciente)\" class='btn-evaluar'>Modificar</a> ";
			$c .= "<a href=\"javascript:eliminarPacienteaccion($idpaciente)\" class='btn-evaluar' style='background:#e74c3c;'>Eliminar</a>";
			$c .= "</div>";
		}
		$c .= "</form>";
		$c .= "</div>";
		return $c;
	}
	private function listaPacientes()
	{
		$c = "<div class='tabla-pacientes-card'>";
		$c .= "<h3>Lista de Docentes</h3>";
		$c .= "Buscar Docente: <input type='text' id='txtbuscarpaciente' name='txtbuscarpaciente' onkeyup=\"checkInput(this);\" class='form-control' style='max-width:200px; display:inline-block; margin-bottom:1rem;'>";
		$c .= "<div id='contenidolista'><table class='tabla-pacientes' width='100%'>";
		$c .= "<tr><th>Apellidos y nombres</th><th>DNI</th><th>Usuario</th><th>Acción</th></tr>";
		$sql = "select p.idpaciente,p.apellidos,p.nombres,p.dni,u.usuario from paciente p,medico m,usuario u where p.idmedico=m.idmedico and p.idusuario=u.idusuario and p.idmedico=" . $_SESSION['param_idmedico'];
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idpaciente = $mostrar['idpaciente'];
			$apellidosynombres = $mostrar['apellidos'] . " " . $mostrar['nombres'];
			$dni = $mostrar['dni'];
			$usuario = $mostrar['usuario'];
			$c .= "<tr><td>$apellidosynombres</td>";
			$c .= "<td>$dni</td>";
			$c .= "<td>$usuario</td>";
			$c .= "<td><a href=\"javascript:datosPaciente($idpaciente)\" class='btn-evaluar-mini'><i class='fa fa-search'></i> Ver</a></td></tr>";
		}
		$c .= "</table></div>";
		$c .= "<div id='contenidopaciente'></div>";
		$c .= "</div>";
		return $c;
	}
	private function listaPacienteslike()
	{
		$idmedico = $_SESSION['param_idmedico'];
		$c = "<table WIDTH='100%'>";
		$c .= "<tr><th>Apellidos y nombres</th><th>DNI</th><th>Usuario</th><th>Acción</th></tr>";
		$sql = "select p.idpaciente,p.apellidos,p.nombres,p.dni,u.usuario from paciente p,medico m,usuario u where p.idmedico=m.idmedico and p.idusuario=u.idusuario and p.idmedico=" . $_SESSION['param_idmedico'] . " and p.apellidos like '%" . $this->param['param_q'] . "%'";
		$resultado = mysqli_query($this->conn, $sql);
		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idpaciente = $mostrar['idpaciente'];
			$apellidosynombres = $mostrar['apellidos'] . " " . $mostrar['nombres'];
			$dni = $mostrar['dni'];
			$usuario = $mostrar['usuario'];
			$c .= "<td>$apellidosynombres</td>";
			$c .= "<td>$dni</td>";
			$c .= "<td>$usuario</td>";
			$c .= "<td><a href=\"javascript:datosPaciente($idpaciente)\" >Ver</a></td></tr>";
		}
		$c .= "</table>";
		return $c;
	}
	private function misdatosMedico()
	{
		$c = "<table>";
		$sql = "select m.idmedico as idmedico,m.nombres as nombres,m.apellidos as apellidos,m.colegiado as colegiado,u.usuario as usuario from medico m,usuario u where u.idusuario=m.idusuario and m.idmedico=" . $_SESSION['param_idmedico'];
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idmedico = $mostrar['idmedico'];
			$apellidosynombres = $mostrar['apellidos'] . " " . $mostrar['nombres'];
			$colegiado = $mostrar['colegiado'];
			$usuario = $mostrar['usuario'];
			$c = '<div class="datos-medico-card">';
			$c .= '<h3>Datos del Administrador</h3>';
			$c .= '<ul class="datos-medico-list">';
			$c .= "<li><span class='datos-medico-label'>ID Administrador:</span> <span class='datos-medico-value'>$idmedico</span></li>";
			$c .= "<li><span class='datos-medico-label'>Apellidos y Nombres:</span> <span class='datos-medico-value'>$apellidosynombres</span></li>";
			$c .= "<li><span class='datos-medico-label'>Código de administrador:</span> <span class='datos-medico-value'>$colegiado</span></li>";
			$c .= "<li><span class='datos-medico-label'>Usuario:</span> <span class='datos-medico-value'>$usuario</span></li>";
			$c .= '</ul>';
			$c .= '</div>';
		}
		$c .= "<table>";
		return $c;
	}
}
