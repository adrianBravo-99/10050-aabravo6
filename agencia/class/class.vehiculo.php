<?php
class vehiculo{
	
	
	private $id;
	private $descripcion;
	private $direccion;
	private $telefono;
	private $horario_inicio;
	private $horario_fin;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR VEHICULO<br><br>";
	}
	

	public function get_form($id=NULL){
		if(($id == NULL) || ($id == 0) ) {
			$this->id = NULL;
			$this->descripcion = NULL;
			$this->direccion = NULL;
			$this->telefono = NULL;
			$this->horario_inicio = NULL;
			$this->horario_fin = NULL;
	
			$flag = "disabled";
			$op = "new";
			$bandera = 1;
	
		} else {
			$sql = "SELECT * FROM agencia WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			$num = $res->num_rows;
			$bandera = ($num==0) ? 0 : 1;
	
			if(!($bandera)){
				$mensaje = "tratar de actualizar la agencia con id= ".$id . "<br>";
				echo $this->_message_error($mensaje);
	
			}else{
				$this->descripcion = $row['descripcion'];
				$this->direccion = $row['direccion'];
				$this->telefono = $row['telefono'];
				$this->horario_inicio = $row['horario_inicio'];
				$this->horario_fin = $row['horario_fin'];
	
				$flag = "enabled";
				$op = "act"; 
			}
	
		}
	
		if($bandera){
			$html = '
			<form name="Form_nombre" method="POST" action="index.php" enctype="multipart/form-data">
				<input type="hidden" name="id" value="' . $this->id  . '">
				<input type="hidden" name="op" value="' . $op  . '">
				<table class="table table-striped table-hover" border="2" align="center">
					<tr>
						<th colspan="2">DATOS</th>
					</tr>
					<tr>
						<td>Descripción:</td>
						<td><input type="text" size="6" name="descripcion" value="' . $this->descripcion . '"></td>
					</tr>
					<tr>
						<td>Dirección:</td>
						<td><input type="text" size="15" name="direccion" value="' . $this->direccion . '"></td>
					</tr>
					<tr>
						<td>Teléfono:</td>
						<td><input type="text" size="15" name="telefono" value="' . $this->telefono . '"></td>
					</tr>
					<tr>
						<td>Horario inicio:</td>
						<td><input type="time" name="horario_inicio" value="' . $this->horario_inicio . '"></td>
					</tr>
					<tr>
						<td>Horario fin:</td>
						<td><input type="time" name="horario_fin" value="' . $this->horario_fin . '"></td>
					</tr>
					<tr>
						<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR"></th>
					</tr>                                                  
				</table>';
			return $html;
		}
	}
	


	
	
	public function get_list() {
		$d_new = "new/0";                           //Línea agregada
		$d_new_final = base64_encode($d_new);       //Línea agregada
	
		$html = ' 
		<table class="table table-striped table-hover" border="1" align="center" >
			<tr>
				<th colspan="8">Lista de Agencias</th>
			</tr>
			<tr>
				<th colspan="8"><a href="index.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr>
				<th>Descripción</th>
				<th>Dirección</th>
				<th>Teléfono</th>
				<th>Horario inicio</th>
				<th>Horario fin</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT * FROM agencia";    
		$res = $this->con->query($sql);
	
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		$num = $res->num_rows;
		if($num != 0){
			while($row = $res->fetch_assoc()){
				// URL PARA BORRAR
				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
	
				// URL PARA ACTUALIZAR
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
	
				// URL PARA EL DETALLE
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);  
	
				$html .= '
				<tr>
					<td>' . $row['descripcion'] . '</td>
					<td>' . $row['direccion'] . '</td>
					<td>' . $row['telefono'] . '</td>
					<td>' . $row['horario_inicio'] . '</td>
					<td>' . $row['horario_fin'] . '</td>
					<td><a href="index.php?d=' . $d_del_final . '">Borrar</a></td>
					<td><a href="index.php?d=' . $d_act_final . '">Actualizar</a></td>
					<td><a href="index.php?d=' . $d_det_final . '">Detalle</a></td>
				</tr>';
			}
		}else{
			$mensaje = "Tabla agencia" . "<br>";
			echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';
		return $html;
	}
	
	
	
//********************************************************************************************************
	/*
	 $tabla es la tabla de la base de datos
	 $valor es el nombre del campo que utilizaremos como valor del option
	 $etiqueta es nombre del campo que utilizaremos como etiqueta del option
	 $nombre es el nombre del campo tipo combo box (select)
	 * $defecto es el valor para que cargue el combo por defecto
	 */ 
	 
	 // _get_combo_db("marca","id","descripcion","marca",$this->marca)
	 // _get_combo_db("color","id","descripcion","color", $this->color)
	 
	 /*Aquí se agregó el parámetro:  $defecto*/

	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto=NULL){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		//$num = $res->num_rows;
		
			
		while($row = $res->fetch_assoc()){
		
		/*
			echo "<br>VARIABLE ROW <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
		*/	
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	//_get_combo_anio("anio",1950,$this->anio)
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_anio($nombre,$anio_inicial,$defecto=NULL){
		$html = '<select name="' . $nombre . '">';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= ($defecto == $i)? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n":'<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	
	//_get_radio($combustibles, "combustible",$this->combustible) 
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_radio($arreglo,$nombre,$defecto=NULL){
		$html = '
		<table class="table table-striped table-hover" border=0 align="left">';
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
				$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>':'<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
			
			$html .= '</tr>';
		}
		$html .= '</table>';
		return $html;
	}
	
	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_vehiculo($id){
    $sql = "SELECT * FROM agencia WHERE id=$id;";
    $res = $this->con->query($sql);
    $row = $res->fetch_assoc();

    // VERIFICA SI EXISTE id
    $num = $res->num_rows;

    if($num == 0){
        $mensaje = "No se puede desplegar el detalle del registro con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
    }else{ 
        $html = '
        <table class="table table-striped table-hover" border="1" align="center">
            <tr>
                <th colspan="2">DETALLE DEL REGISTRO</th>
            </tr>
            <tr>
                <td>Descripción: </td>
                <td>'. $row['descripcion'] .'</td>
            </tr>
            <tr>
                <td>Dirección: </td>
                <td>'. $row['direccion'] .'</td>
            </tr>
            <tr>
                <td>Teléfono: </td>
                <td>'. $row['telefono'] .'</td>
            </tr>
            <tr>
                <td>Horario inicio: </td>
                <td>'. $row['horario_inicio'] .'</td>
            </tr>
            <tr>
                <td>Horario fin: </td>
                <td>'. $row['horario_fin'] .'</td>
            </tr>
            <tr>
                <th colspan="2"><a href="index.php">Regresar</a></th>
            </tr>                                                                                       
        </table>';

        return $html;
    }   
}



//CRUD
	public function delete_vehiculo($id){
		
	/*	$mensaje = "PROXIMAMENTE SE ELIMINARA el vehiculo con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
		
	*/	
	   
		$sql = "DELETE FROM agencia WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
	}


	public function update_vehiculo($id){
		$this->descripcion = $_POST['descripcion'];
		$this->direccion = $_POST['direccion'];
		$this->telefono = $_POST['telefono'];
		$this->horario_inicio = $_POST['horario_inicio'];
		$this->horario_fin = $_POST['horario_fin'];
		
		$sql = "UPDATE agencia SET 
				descripcion = '$this->descripcion', 
				direccion = '$this->direccion',
				telefono = '$this->telefono',
				horario_inicio = '$this->horario_inicio', 
				horario_fin = '$this->horario_fin'
				WHERE id = $id";
		if($this->con->query($sql)){
			echo $this->_message_ok("actualizado");
		}else{
			echo $this->_message_error("actualizar<br>");
		}
	}
	
	public function save_vehiculo() {
		$this->descripcion = $_POST['descripcion'];
		$this->direccion = $_POST['direccion'];
		$this->telefono = $_POST['telefono'];
		$this->horario_inicio = $_POST['horario_inicio'];
		$this->horario_fin = $_POST['horario_fin'];
	
		$sql = "INSERT INTO agencia (descripcion, direccion, telefono, horario_inicio, horario_fin) VALUES (
				'$this->descripcion',
				'$this->direccion',
				'$this->telefono',
				'$this->horario_inicio',
				'$this->horario_fin'
				)";
		if($this->con->query($sql)){
			echo $this->_message_ok("guardado");
		} else {
			echo $this->_message_error("guardar<br>");
		}
	}
	

//***************************************************************************************	
	
	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}
	
//***************************************************************************************************************************
	
	private function _message_error($tipo){
		$html = '
		<table class="table table-striped table-hover" border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_BD_Vacia($tipo){
	   $html = '
		<table class="table table-striped table-hover" border="0" align="center">
			<tr>
				<th> NO existen registros en la ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
	
		</table>';
		return $html;
	
	
	}
	
	private function _message_ok($tipo){
		$html = '
		<table class="table table-striped table-hover" border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}

//************************************************************************************************************************************************

 
}
?>

