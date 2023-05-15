<?php
class vehiculo{
	
	
	private $id;
	private $placa;
	private $marca;
	private $motor;
	private $chasis;
	private $combustible;
	private $anio;
	private $color;
	private $foto;
	private $avaluo;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR VEHICULO<br><br>";
	}
	

	public function get_form($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
		//Iniciaizar todo
			$this->placa = NULL;
			$this->marca = NULL;
			$this->motor = NULL;
			$this->chasis = NULL;
			$this->combustible = NULL;
			$this->anio = NULL;
			$this->color = NULL;
			$this->foto = NULL;
			$this->avaluo =NULL;

			
		//flag algo de algoritmo lo de abajo	
		//yo quiero aqui que el avaluo sea automatico
			$flag = "disabled";
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM vehiculo WHERE id=$id;";
			$res = $this->con->query($sql);
			//el fetch assoc saca las tuplas
			$row = $res->fetch_assoc();
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1;
            
            if(!($bandera)){
                $mensaje = "tratar de actualizar el vehiculo con id= ".$id . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				/*
				echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
			*/
		
             // ATRIBUTOS DE LA CLASE VEHICULO   
                $this->placa = $row['placa'];
                $this->marca = $row['marca'];
                $this->motor = $row['motor'];
                $this->chasis = $row['chasis'];
                $this->combustible = $row['combustible'];
                $this->anio = $row['anio'];
                $this->color = $row['color'];
                $this->foto = $row['foto'];
                $this->avaluo = $row['avaluo'];
				
                //$flag = "disabled";
				$flag = "enabled";
                $op = "act"; 
            }
	}
        
	if($bandera){
    
		$combustibles = ["Gasolina",
						 "Diesel",
						 "Eléctrico"
						 ];
		//Los dos input es para que cuando se manden con post se tenga esa data, por eso estan hidden
		//POST es con submit y GET es con el query
		$html = '
		<form name="Form_vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table class="table table-striped table-hover" border="2" align="center">
				<tr>
					<th colspan="2">DATOS VEHÍCULO</th>
				</tr>
				<tr>
					<td>Placa:</td>
					<td><input type="text" size="6" name="placa" value="' . $this->placa . '"></td>
				</tr>
				<tr>
					<td>Marca:</td>
					<td>' . $this->_get_combo_db("marca","id","descripcion","marca",$this->marca) . '</td>
				</tr>
				<tr>
					<td>Motor:</td>
					<td><input type="text" size="15" name="motor" value="' . $this->motor . '"></td>
				</tr>	
				<tr>
					<td>Chasis:</td>
					<td><input type="text" size="15" name="chasis" value="' . $this->chasis . '"></td>
				</tr>
				<tr>
					<td>Combustible:</td>
					<td>' . $this->_get_radio($combustibles, "combustible",$this->combustible) . '</td>
				</tr>
				<tr>
					<td>Año:</td>
					<td>' . $this->_get_combo_anio("anio",1950,$this->anio) . '</td>
				</tr>
				<tr>
					<td>Color:</td>
					<td>' . $this->_get_combo_db("color","id","descripcion","color", $this->color) . '</td>
				</tr>
				<tr>
					<td>Foto:</td>
					<td><input type="file" name="foto" '  . $flag . '></td>
				</tr>
				<tr>
					<td>Avalúo:</td>
					<td><input type="text" size="8" name="avaluo" value="' . $this->avaluo . '" ' . $flag . '></td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>												
			</table>';
		return $html;
		}
	}


	
	
	
	public function get_list(){
		$d_new = "new/0";                           //Línea agregada
        $d_new_final = base64_encode($d_new);       //Línea agregada
				
		$html = ' 
		<table class="table table-striped table-hover" border="1" align="center" >
			<tr>
				<th colspan="8">Lista de Vehículos</th>
			</tr>
			<tr>
				<th colspan="8"><a href="index.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr>
				<th>Placa</th>
				<th>Marca</th>
				<th>Color</th>
				<th>Año</th>
				<th>Avalúo</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT v.id, v.placa, m.descripcion as marca, c.descripcion as color, v.anio, v.avaluo  
		        FROM vehiculo v, color c, marca m 
				WHERE v.marca=m.id AND v.color=c.id;";	
		$res = $this->con->query($sql);
		
		
		
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		$num = $res->num_rows;
        if($num != 0){
		
		    while($row = $res->fetch_assoc()){
			/*
				echo "<br>VARIALE ROW ...... <br>";
				echo "<pre>";
						print_r($row);
				echo "</pre>";
			*/
		    		
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
						<td>' . $row['placa'] . '</td>
						<td>' . $row['marca'] . '</td>
						<td>' . $row['color'] . '</td>
						<td>' . $row['anio'] . '</td>
						<td>' . $row['avaluo'] . '</td>
						<td><a href="index.php?d=' . $d_del_final . '">Matricular</a></td>
					</tr>';
			 
		    }
		}else{
			$mensaje = "Tabla Vehiculo" . "<br>";
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
		$sql = "SELECT v.placa, m.descripcion as marca, v.motor, v.chasis, v.combustible, v.anio, c.descripcion as color, v.foto, v.avaluo  
				FROM vehiculo v, color c, marca m 
				WHERE v.id=$id AND v.marca=m.id AND v.color=c.id;";
		//con conexion
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle del vehiculo con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
		/*
	    echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";*/
	
		$html = '
		<table class="table table-striped table-hover" border="1" align="center">
			<tr>
				<th colspan="2">DATOS DEL VEHÍCULO</th>
			</tr>
			<tr>
				<td>Placa: </td>
				<td>'. $row['placa'] .'</td>
			</tr>
			<tr>
				<td>Marca: </td>
				<td>'. $row['marca'] .'</td>
			</tr>
			<tr>
				<td>Motor: </td>
				<td>'. $row['motor'] .'</td>
			</tr>
			<tr>
				<td>Chasis: </td>
				<td>'. $row['chasis'] .'</td>
			</tr>
			<tr>
				<td>Combustible: </td>
				<td>'. $row['combustible'] .'</td>
			</tr>
			<tr>
				<td>Anio: </td>
				<td>'. $row['anio'] .'</td>
			</tr>
			<tr>
				<td>Color: </td>
				<td>'. $row['color'] .'</td>
			</tr>
			<tr>
				<td>Avalúo: </td>
				<th>$'. $row['avaluo'] .' USD</th>
			</tr>
			<tr>
				<td>Valor Matrícula: </td>
				<th>$'. $this->_calculo_matricula($row['avaluo']) .' USD</th>
			</tr>			
			<tr>
				<th colspan="2"><img src="images/' . $row['foto'] . '" width="300px"/></th>
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
	   
		$sql = "DELETE FROM vehiculo WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
	}


public function update_vehiculo($id){
    $this->placa = $_POST['placa'];
    $this->marca = $_POST['marca'];
    $this->motor = $_POST['motor'];
    $this->chasis = $_POST['chasis'];
    $this->combustible = $_POST['combustible'];
    $this->anio = $_POST['anio'];
    $this->color = $_POST['color'];
    $this->foto = '';
    $this->avaluo = $_POST['avaluo'];

	//No hay como asiganr otra vez el id porque es la primary key
    $sql = "UPDATE vehiculo SET 
            placa = '$this->placa', 
            marca = '$this->marca', 
            motor = '$this->motor', 
            chasis = '$this->chasis', 
            combustible = '$this->combustible', 
            anio = '$this->anio', 
            color = '$this->color', 
            foto = '$this->foto', 
            avaluo = '$this->avaluo'
            WHERE id = $id";
	//Se puede copiar y pegar el sql en el workbench para verle
	//echo $sql;
    if($this->con->query($sql)){
        echo $this->_message_ok("actualizado");
    }else{
        echo $this->_message_error("actualizar<br>");
    }
}

public function save_vehiculo() {
    $this->placa = $_POST['placa'];
    $this->marca = $_POST['marca'];
    $this->motor = $_POST['motor'];
    $this->chasis = $_POST['chasis'];
    $this->combustible = $_POST['combustible'];
    $this->anio = $_POST['anio'];
    $this->color = $_POST['color'];
	$this->foto = ''; 
	/*
	echo "<br> FILES <br>";
	echo "<pre>";
		print_r($_FILES);
	echo "</pre>";
	*/
   // $this->foto = $this->_get_name_file($_FILES['foto']['name'],12); 
    $this->avaluo = isset($_POST['avaluo']) ? $_POST['avaluo'] : 0;


    $sql = "INSERT INTO vehiculo (placa, marca, motor, chasis, combustible, anio, color, foto, avaluo) VALUES (
            '$this->placa',
            '$this->marca',
            '$this->motor',
            '$this->chasis',
            '$this->combustible',
            '$this->anio',
            '$this->color',
            '$this->foto',
            '$this->avaluo'
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

