<?php

require_once ('../db.php');
require_once('../class/Autenticacao.php');

try {
	
	$db = DB::getInstance();
	
	$autenticado = Autenticacao::isAutenticado($_REQUEST['token'],$_REQUEST['id_usuario']);

	if($autenticado['status'] == false){
		echo json_encode($autenticado);
		return;
	}

	if($autenticado['atendente'] != 1){
		echo json_encode(array("status"=>false, "mensagem"=>"Você não pode encerrar este atendimento, você precisa ser um atendente."));
		return;
	}

	$stmt = $db->prepare("UPDATE atendimento SET finalizado = 1 WHERE id = :id_atendimento"); 
	$result = $stmt->execute(array(
		':id_atendimento' => $_REQUEST['id_atendimento']
	));

	if($result)
		echo json_encode(array("status"=>true, "mensagem"=>"Atendimento encerrado"));

} catch (Exception $e) {
	print $e->getMessage();
}