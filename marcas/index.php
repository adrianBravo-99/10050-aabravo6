<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Marcas</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style>
        body {
            padding: 20px;
        }

        .center-div {
            margin: auto;
            width: 50%;
            padding: 10px;
        }
    </style>
</head>
<body>
	<div class="center-div">
	<a class="btn btn-primary rounded-pill" href="../index.html">Home</a>
	<br><br>
<?php
	include_once("constantes.php");
	require_once("class/class.vehiculo.php");
	
	$cn = conectar();
	$v = new vehiculo($cn);
	//vehiculo::MetodoEstatico();
	
		
	
// Codigo necesario para realizar pruebas.
	if(isset($_GET['d'])){
		
		// 2.2 DETALLE id
		$dato = base64_decode($_GET['d']);
		$tmp = explode("/", $dato);
		
		$op = $tmp[0];
		$id = $tmp[1];
		
		if($op == "det"){
			echo $v->get_detail_vehiculo($id);
		}elseif($op == "act"){
			echo $v->get_form($id);
		}elseif($op == "new"){
			echo $v->get_form();
		}elseif($op == "del"){
			echo $v->delete_vehiculo($id); 
		}

	
	}else{
		  
		if(isset($_POST['Guardar']) && $_POST['op']=="new"){
			$v->save_vehiculo();
		}elseif(isset($_POST['Guardar']) && $_POST['op']=="act"){
			$v->update_vehiculo($_POST['id']);
		}else{
			echo $v->get_list();
		}	
	}
			

	
//*******************************************************
	function conectar(){
		//echo "<br> CONEXION A LA BASE DE DATOS<br>";
		$c = new mysqli(SERVER,USER,PASS,BD);
		
		if($c->connect_errno) {
			die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
		}
	/*	else{
			echo "La conexión tuvo éxito .......<br><br>";
		}  */
		
		$c->set_charset("utf8");
		return $c;
	}
//**********************************************************
	
	
?>	
	</div>
	
</body>
</html>
