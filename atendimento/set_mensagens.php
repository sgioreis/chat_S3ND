<?php

require_once ('../db.php');
require_once('../class/Autenticacao.php');

try {
	
	$autenticado = Autenticacao::isAutenticado($_REQUEST['token'],$_REQUEST['id_usuario']);
	
	if($autenticado['status'] == false){
		echo json_encode($autenticado);
		return;
	}

	$db = DB::getInstance();

	$stmt = $db->prepare('INSERT INTO mensagem (atendimento_id, atendente, conteudo) VALUES (:atendimento_id, :atendente, :msg)');
	$stmt->execute(array(
		':atendimento_id' => $_REQUEST['id_atendimento'],
		':atendente' => $autenticado['atendente'],
		':msg' => $_REQUEST['conteudo']
	));

} catch (Exception $e) {
	print $e->getMessage();
}