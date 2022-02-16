<?php

require_once ('../db.php');
require_once ('../constants.php');
require_once ('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Autenticacao {

    public static function autenticar()
    {

        try {
            $db = DB::getInstance();

            // Veririficando se o usuário já existe
            $stmt = $db->prepare("SELECT id, atendente, email FROM usuario WHERE email = '" .$_REQUEST['email']. "'");
            $stmt->execute();
        
            $usuario = $stmt->fetch(PDO::FETCH_OBJ);

            if ($usuario)  $id_usuario = $usuario->id;

            // Se usuário não existe, então criamos um novo
            if (!$usuario) {
                $stmt = $db->prepare('INSERT INTO usuario (nome, email) VALUES (:nome, :email)');

                $stmt->execute(array(
                    ':nome' => $_REQUEST['nome'],
                    ':email' => $_REQUEST['email']
                ));
        
                $id_usuario = $db->lastInsertId();
                $usuario = (object) [ // Pro PHP não reclamar
                    'atendente' => '0',
                ];
            }          
            
            $payload = array(
                "iss" => "localhost",
                "aud" => "localhost",
                "iat" => 1356999524,
                "nbf" => 1357000000,
                "id_usuario" => $id_usuario,
                "atendente" => $usuario->atendente
            );
        
            $jwt = JWT::encode($payload, KEY, 'HS256');

            return array('token'=>$jwt, 'id_usuario'=>$id_usuario,"atendente"=>$usuario->atendente);    	

        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public static function isAutenticado($token,$id_usuario)
    {
        $decoded = JWT::decode($token, new Key(KEY, 'HS256'));
		try {
            
            if($decoded->id_usuario != $id_usuario){
                return array("status"=>false, "mensagem"=>"O Usuário não está autenticado");
            }

            return array("status"=>true, "mensagem"=>"O Usuário está autenticado","atendente" => $decoded->atendente);

        } catch (Exception $e) {
           return array("status"=>false, "mensagem"=>"O Usuário não está autenticado");
        }   
    }
}