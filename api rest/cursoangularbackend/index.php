<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

$db = new mysqli('localhost', 'root', '', 'curso_angular');

//CONFIGURACIÓN DE CABECERAS
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

$app->get("/pruebas", function() use($app){
	echo "Hola mundo desde Slim PHP";
});

$app->get("/probando", function() use($app){
	echo "Otro texto cualquiera";
});

//GUARDAR PRODUCTOS

$app->post('/productos', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if(!isset($data['descripcion'])){
		$data['descripcion']=null;
	}

	if(!isset($data['nombre'])){
		$data['nombre']=null;
	}

	if(!isset($data['precio'])){
		$data['precio']=null;
	}

	if(!isset($data['imagen'])){
		$data['imagen']=null;
	}

	$query = "INSERT INTO productos VALUES(NULL,".
			 "'{$data['nombre']}',".
			 "'{$data['descripcion']}',".
			 "'{$data['precio']}',".
			 "'{$data['imagen']}'".
			 ");";

	$insert = $db->query($query);

	$result = array(
			'status' => 'error',
			'code' => 404,
			'message' => 'Producto no creado correctamente'
		);

	if($insert){
		$result = array(
			'status' => 'success',
			'code' => 200,
			'message' => 'Producto creado correctamente'
		);
	}

	echo json_encode($result);

});

//LISTAR TODOS LOS PRODUCTOS

$app->get('/productos', function() use($db, $app){

	$sql = 'SELECT * FROM productos ORDER BY id DESC;';
	$query = $db->query($sql);

	//fetch_all para sacar todos sin bucle
	//var_dump($query->fetch_all());

	//para sacarlo como array de objetos mejor asi:
	$productos = array();
	while ($producto = $query->fetch_assoc()) {
		$productos[] = $producto;
	}

	$result = array(
		'status' => 'success',
		'code' => 200,
		'data' => $productos
	);

	echo json_encode($result);

});

//DEVOLVER UN SOLO PRODUCTO
$app->get('/producto/:id', function($id) use($db, $app){

	$sql = 'SELECT * FROM productos WHERE id = '.$id;
	$query = $db->query($sql);

	$result = array(
		'status' => 'error',
		'code' => 404,
		'message' => 'Producto no disponible'
	);

	if($query->num_rows == 1){
		$producto = $query->fetch_assoc();

		$result = array(
			'status' => 'success',
			'code' => 200,
			'data' => $producto
		);
	}

	echo json_encode($result);

});

//ELIMINAR UN PRODUCTO
$app->get('/delete-producto/:id', function($id) use($db, $app){
	
	$sql = 'DELETE FROM productos WHERE id='.$id;
	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' => 'success',
			'code' => 200,
			'message' => 'Producto eliminado correctamente'
		);
	}else{
		$result = array(
			'status' => 'error',
			'code' => 404,
			'message' => 'Producto no eliminado correctamente'
		);
	}

	echo json_encode($result); 

});

//ACTUALIZAR UN PRODUCTO
$app->post('/update-producto/:id', function($id) use($db, $app){

	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$sql = "UPDATE productos SET ".
		   "nombre = '{$data["nombre"]}', ".
		   "descripcion = '{$data["descripcion"]}', ";

	if(isset($data['imagen'])){
		$sql .= "imagen = '{$data["imagen"]}', ";
	}

	$sql .= "precio = '{$data["precio"]}' WHERE id = {$id}";
	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' => 'success',
			'code' => 200,
			'message' => 'Producto actualizado correctamente'
		);
	}else{
		$result = array(
			'status' => 'error',
			'code' => 404,
			'message' => 'Producto no actualizado correctamente'
		);
	}

	echo json_encode($result);

});

//SUBIR UNA IMAGEN A UN PRODUCTO
$app->post('/upload-file', function() use($db, $app){
	$result = array(
		'status' => 'error',
		'code' => 404,
		'message' => 'El archivo no se ha subido correctamente'
	);

	if(isset($_FILES['uploads'])){
		$piramideUploader = new PiramideUploader();

		$upload = $piramideUploader->upload('image', 'uploads', 'uploads', array('image/jpeg', 'image/png', 'image/gif'));
		$file = $piramideUploader->getInfoFile();
		$file_name = $file['complete_name'];

		if(isset($upload) && $upload['uploaded'] == false){
			$result = array(
				'status' => 'error',
				'code' => 404,
				'message' => 'El archivo no se ha subido correctamente'
			);
		}else{
			$result = array(
				'status' => 'success',
				'code' => 200,
				'message' => 'El archivo se ha subido correctamente',
				'filename' => $file_name
			);
		}
	}

	echo json_encode($result);

});

$app->run();