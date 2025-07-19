<?php
    function agregarPaciente(){
        $c = "<div class='agregar-paciente-card'>";
        $c .= "<h3>Agregar Docente</h3>";
        $c .= "<form>";
        $c .= "<div class='form-group'><label>Apellidos:</label><input type='text' id='txtapellidos' class='form-control' onkeypress='return lettersOnly(event)'></div>";
        $c .= "<div class='form-group'><label>Nombres:</label><input type='text' id='txtnombres' class='form-control' onkeypress='return lettersOnly(event)'></div>";
        $c .= "<div class='form-group'><label>DNI:</label><input type='text' id='txtdni' class='form-control' onkeypress='return isNumber(event)' maxlength='8'></div>";
        $c .= "<div class='form-group'><label>Usuario:</label><input type='text' id='txtusuario' class='form-control' onkeypress='return lettersOnly(event)'></div>";
        $c .= "<div class='text-center'><a href='javascript:agregarPacienteaccion()' class='btn-evaluar'>Agregar</a></div>";
        $c .= "</form>";
        $c .= "</div>";
        return $c;
    }
    echo agregarPaciente();	
?>