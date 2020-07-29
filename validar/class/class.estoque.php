<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');

class Estoque extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    /* Função retorna saldo atual de item estoque */
    function saldoAtual( $unidade, $item ){
        $sql = "SELECT saldo FROM item_unidade WHERE item = $item AND unidade = $unidade;";
        $saldo = mysqli_fetch_array( mysqli_query( $this->link, $sql ));
        return $saldo['saldo'];
    }

    /* Função registra inventario */
    function registraInventario( $items, $unidade, $idUsuario){
        $data = date("Y-m-d");
        foreach ( $items as $item => $qtd) {
            $saldo = $this->saldoAtual( $unidade , $item ); //SALDO ATUAL
            if( intval($saldo) == intval($qtd['qtd_insert']) ){ //SALDO É IGUAL A QUANTIDADE
                
            }else
            if(  intval($qtd['qtd_insert']) > intval($saldo) ){ //SALDO É MAIOR QUE A QUANTIDADE. CODIGO 1
                $atualizar = intval($qtd['qtd_insert']) - intval($saldo);
                $sql = "INSERT INTO `estoque`(`id`, `item`, `unidade`, `tipo`, `qtd`, `qtd_consumida`, `valor_unt`, `validade`, `usuario`, `data`, `obs`, `atu_data`, `qtd_informado`) VALUES 
                (
                    NULL, 
                    $item, 
                    $unidade, 
                    1, 
                    $atualizar, 
                    NULL, 
                    NULL, 
                    NULL, 
                    $idUsuario, 
                    '$data', 
                    NULL,
                    NULL,
                    '$qtd[qtd_insert]'
                )";
                mysqli_query( $this->link, $sql );
            }else
            if( intval($qtd['qtd_insert']) < intval($saldo)){ //SALDO É MENOR QUE A QUANTIDADE. CODIGO 11
                $atualizar = intval($saldo) - intval($qtd['qtd_insert']);
                $sql = "INSERT INTO `estoque`(`id`, `item`, `unidade`, `tipo`, `qtd`, `qtd_consumida`, `valor_unt`, `validade`, `usuario`, `data`, `obs`, `atu_data`, `qtd_informado`) VALUES 
                (
                    NULL, 
                    $item, 
                    $unidade, 
                    11, 
                    $atualizar, 
                    NULL, 
                    NULL, 
                    NULL, 
                    $idUsuario, 
                    '$data', 
                    NULL,
                    NULL,
                    '$qtd[qtd_insert]'
                )";
                mysqli_query( $this->link, $sql );
            } 
        }
        return 1;
    }

    /* Função baixa de material */
    function baixaDeMaterial( $itens, $unidade, $idUsuario){
        $data = date("Y-m-d");
        foreach ( $itens as $item => $qtd ) {
            $sql = "INSERT INTO `estoque`(`id`, `item`, `unidade`, `tipo`, `qtd`, `qtd_consumida`, `valor_unt`, `validade`, `usuario`, `data`, `obs`, `atu_data`, `qtd_informado`) VALUES 
                (
                    NULL, 
                    $item, 
                    $unidade, 
                    12, 
                    $qtd[qtd_insert], 
                    NULL, 
                    NULL, 
                    NULL, 
                    $idUsuario, 
                    '$data', 
                    NULL,
                    NULL,
                    '$qtd[qtd_insert]'
                )";
            mysqli_query( $this->link, $sql );	
	    }
    }

     /* Função acerto de entrada de material */
     function acertoEntrada( $itens, $unidade, $idUsuario){
        $data = date("Y-m-d");
        foreach ( $itens as $item => $qtd ) {
            $sql = "INSERT INTO `estoque`(`id`, `item`, `unidade`, `tipo`, `qtd`, `qtd_consumida`, `valor_unt`, `validade`, `usuario`, `data`, `obs`, `atu_data`, `qtd_informado`) VALUES 
                (
                    NULL, 
                    $item, 
                    $unidade, 
                    3, 
                    $qtd[qtd_insert], 
                    NULL, 
                    NULL, 
                    NULL, 
                    $idUsuario, 
                    '$data', 
                    NULL,
                    NULL,
                    '$qtd[qtd_insert]'
                )";
            mysqli_query( $this->link, $sql );	
	    }
    }

     /* Função acerto de saída de material */
     function acertoSaida( $itens, $unidade, $idUsuario){
        $data = date("Y-m-d");
        foreach ( $itens as $item => $qtd ) {
            $sql = "INSERT INTO `estoque`(`id`, `item`, `unidade`, `tipo`, `qtd`, `qtd_consumida`, `valor_unt`, `validade`, `usuario`, `data`, `obs`, `atu_data`, `qtd_informado`) VALUES 
                (
                    NULL, 
                    $item, 
                    $unidade, 
                    13, 
                    $qtd[qtd_insert], 
                    NULL, 
                    NULL, 
                    NULL, 
                    $idUsuario, 
                    '$data', 
                    NULL,
                    NULL,
                    '$qtd[qtd_insert]'
                )";
            mysqli_query( $this->link, $sql );	
	    }
    }

    /* Função acerto de saída de material */
    function entradaPorRequisicao( $item, $unidade, $idUsuario, $qtd){
        $data = date("Y-m-d");
        $sql = "INSERT INTO `estoque`(`id`, `item`, `unidade`, `tipo`, `qtd`, `qtd_consumida`, `valor_unt`, `validade`, `usuario`, `data`, `obs`, `atu_data`, `qtd_informado`) VALUES 
            (
                NULL, 
                $item, 
                $unidade, 
                2, 
                $qtd, 
                NULL, 
                NULL, 
                NULL, 
                $idUsuario, 
                '$data', 
                NULL,
                NULL,
                '$qtd'
            )";
        mysqli_query( $this->link, $sql );	
	    
    }

    /* Função Saida de material para atendimento */
    function baixaMaterialAtendimento( $itens, $unidade, $idUsuario, $qtd ){
        $data = date("Y-m-d");
           $sql = "INSERT INTO `estoque`(`id`, `item`, `unidade`, `tipo`, `qtd`, `qtd_consumida`, `valor_unt`, `validade`, `usuario`, `data`, `obs`, `atu_data`, `qtd_informado`) VALUES 
            (
                NULL, 
                $itens, 
                $unidade, 
                14, 
                $qtd, 
                NULL, 
                NULL, 
                NULL, 
                $idUsuario, 
                '$data', 
                NULL,
                NULL,
                '$qtd'
            );";
            
        mysqli_query( $this->link, $sql );	
       
    }

    /* Função altera status dos itens pelo ID do item */
    function atualizaStatusItem( $idItem, $atualizarStatus, $usuario, $obs = "", $qtd = NULL ){
        if($qtd == NULL){
            $sql = "UPDATE estoque_item_requisicao SET `obs_stq` = '$obs', `status` = '$atualizarStatus', `dt_atua` = NULL, `usu_atua` = '$usuario' WHERE id = $idItem;";
        }else{
            $sql = "UPDATE estoque_item_requisicao SET `obs_stq` = '$obs', `qtd_env` = '$qtd', `status` = '$atualizarStatus', `dt_atua` = NULL, `usu_atua` = '$usuario' WHERE id = $idItem;";
        }
        
       
        mysqli_query( $this->link, $sql );	
    }

    /* Função registra observção do estoque central */
    function registraObsCentral( $req, $obs){
        $sql = "UPDATE estoque_registro_requisicao SET `obs_etq` = '$obs' WHERE requisicao = $req";
        mysqli_query( $this->link, $sql );	
    }

}
/* session_start();
$estoque = new Estoque();
$estoque->atualizaStatusItem($_SESSION['itens_insert_estoque'],24,$_SESSION['usuario_id']); */