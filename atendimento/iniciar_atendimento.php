<?php

require_once ('../db.php');
require_once('../class/Autenticacao.php');

try {

	$usuario = Autenticacao::autenticar();

	$db = DB::getInstance();

	// verificando se há chat aberto para usuário ou atendente
	$stmt = $db->prepare("SELECT id FROM atendimento WHERE (usuario_id = ".$usuario['id_usuario']. " OR atendente_id = ".$usuario['id_usuario']. ") AND finalizado = 0");
	$stmt->execute();
	
	$atendimento = $stmt->fetch(PDO::FETCH_OBJ);

	$atendimento = $atendimento ? $atendimento->id : null;

	// se não houver chat aberto, crio um novo (exceto para atendente)
	if(!$atendimento && !$usuario['atendente']){
		$stmt = $db->prepare('INSERT INTO atendimento (usuario_id, atendente_id) VALUES (:usuario, :atendente)');
		$stmt->execute(array(
			':usuario' => $usuario['id_usuario'],
			':atendente' => "1" // Por ser um exemplo, defini que somente o usuário 1 da tabela (suporte@s3nd) atuará como atendente do chat
		));
		
		$atendimento = $db->lastInsertId();
	}

	$dados = array();
	$dados = $usuario;
	$dados['id_atendimento'] = $atendimento;
	
	echo json_encode($dados);

} catch (Exception $e) {
	print $e->getMessage();
  
}