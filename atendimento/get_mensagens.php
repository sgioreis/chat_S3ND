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

	$sql = "SELECT atendente, conteudo, DATE_FORMAT(data_envio, '%H:%i') AS data_envio 
			FROM mensagem
			WHERE atendimento_id =". $_REQUEST['id_atendimento'];
	
	$stmt = $db->prepare($sql);
	$stmt->execute();
	
	$dados['mensagens'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sqlAtendimento = "SELECT finalizado FROM atendimento WHERE id =". $_REQUEST['id_atendimento'];
	
	$stmta = $db->prepare($sqlAtendimento);
	$stmta->execute();

	
	$atendimento = $stmta->fetch(PDO::FETCH_OBJ);

	$dados['finalizado'] = $atendimento ? $atendimento->finalizado : 0;
	
	echo json_encode($dados);


} catch (Exception $e) {
	print $e->getMessage();
}