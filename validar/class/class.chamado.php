<?php
require_once("db.class.php");
date_default_timezone_set('America/Sao_Paulo');

class Chamado extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    function atualizaChamadoSolicitante($usuario, $chamado, $desc, $tipo){
        $data = date('Y-m-d');
        $hora = date('H:i:s');
        $sql = "INSERT INTO `ch_atual`(`id`, `chamado`, `por`, `data`, `hora`, `descricao`, `tipo_atualizacao`, `ativo`) VALUES 
        (
            NULL,
            '$chamado',
            '$usuario',
            '$data',
            '$hora',
            '$desc',
            '$tipo',
            '1'
        )";
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            return "1";
        } else {
            return '0';
        }
    }

    function novoChamado($usu, $unidade, $falar, $contato, $assunto, $descricao, $arq){
        $data = date('Y-m-d');
        $hora = date('H:i:s');
        $sql = 'INSERT INTO `ch_chamado` (`id`, `usu`, `unidade`, `falar_com`, `falar_contato`, `assunto`, `descricao`, `data`, `hora`, `status`, `arq`) VALUES 
        (   
            NULL,
            "'.$usu.'", 
            "'.$unidade.'", 
            "'.$falar.'", 
            "'.$contato.'", 
            "'.$assunto.'", 
            "'.$descricao.'", 
            "'.$data.'", 
            "'.$hora.'", 
            "1",
            "'.$arq.'"
        );';
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            return mysqli_insert_id($this->link);
        } else {
            return '0';
        }
    }

    function enviar($arquivo){
            date_default_timezone_set('America/Sao_Paulo');
            $date = date('Ymdhis');

            // matriz de entrada
            $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');
            // matriz de saída
            $by   = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');
            // devolver a string
            //return str_replace($what, $by, $string);
            $errors = array();
            $file_name = str_replace($what, $by, $_FILES['files']['name']);
            $file_size = $_FILES['files']['size'];
            $file_tmp = $_FILES['files']['tmp_name'];
            $file_type = $_FILES['files']['type'];
            @$file_ext = strtolower(end(explode('.', $_FILES['files']['name'])));

            $extensions = array("jpg", "jpeg", "png");

            if (in_array($file_ext, $extensions) == false) {
                return "[extensao_erro]";
            } else
            if ($file_size > 20000000) {
                return "[tamanho_excedido]";
            } else
            if (empty($errors) == true) {
                $caminho =  move_uploaded_file($file_tmp, "arquivos/" . $date . '.' . $file_ext);
                return "validar/arquivos/" . $date . '.' . $file_ext;
            } else {
                print_r($errors);
                return "0";
            }
    }

    function evoluirChamado($chamado,$usu,$descricao,$status,$arq){
        $data = date('Y-m-d');
        $hora = date('H:i:s');
        $sql = "INSERT INTO `ch_atual`(`id`, `chamado`, `por`, `data`, `hora`, `descricao`, `tipo_atualizacao`, `arq`) VALUES 
        (
            NULL,
            '$chamado',
            '$usu',
            '$data',
            '$hora',
            '$descricao',
            '$status',
            '$arq'
        )";
        $sql = mysqli_query($this->link, $sql);
        $sql = "UPDATE ch_chamado SET `status` = '$status' WHERE id = $chamado";
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            return "1";
        } else {
            return '0';
        }
    }

    function ultimaNotificacao($chamado){
        $sql = "SELECT u.ti AS ti
        FROM ch_atual AS a 
        INNER JOIN usuarios AS u 
        ON a.por = u.id
        WHERE chamado = $chamado ORDER BY a.id DESC LIMIT 1";
        $res = mysqli_fetch_array( mysqli_query($this->link, $sql) );
        return $res['ti'];
    }
 
}
/* session_start();
$estoque = new Estoque();
$estoque->atualizaStatusItem($_SESSION['itens_insert_estoque'],24,$_SESSION['usuario_id']); */
