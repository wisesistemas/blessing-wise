<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');
class Requisicao extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    /* Função cadastra de requisção */
    function cadastraRequisicao($unidade, $usuario, $obs, $itens){
        $data = date("Y-m-d");
        $hora = date("H:i:s");
        /* Registra titulo */
        $sql = 'INSERT INTO `estoque_registro_requisicao`(`requisicao`, `unidade`, `cad`, `data`, `hora`, `obs_slt`, `obs_etq`, `status`, `obs_ret`) VALUES 
        (
            NULL,
            "'.$unidade.'",
            "'.$usuario.' ",
            "'.$data.'",
            "'.$hora.'",
            "'.$obs.'",
            NULL,
            "500",
            NULL
        )';
        $sql = mysqli_query( $this->link, $sql );
        $req = mysqli_insert_id( $this->link );
        /* Registra itens */
        foreach ($itens as $key => $value) {
            $sql = "INSERT INTO `estoque_item_requisicao` (`id`, `registro`, `item`, `qtd_slt`, `qtd_etq`, `ref`, `data`, `usuario`, `obs_stq`, `qtd_env`, `status`, `imp`, `qtd_ent`, `obs_slt`, `obs_ent`, `usu_atua`) VALUES 
            (
                NULL,
                $req,
                '$key',
                '$value[qtd_insert]',
                '$value[qtd_atual]',
                '$value[ref]',
                '$data',
                '$usuario',
                NULL,
                NULL,
                '550',
                '0',
                NULL,
                NULL,
                NULL,
                '$usuario'
            )";
             $sql = mysqli_query( $this->link, $sql );
        }
        return $req;
    }

    /* Atualiza item */
    function atualizaStatusItem( $item, $status, $usuario, $impresso){
        $sql = "UPDATE estoque_item_requisicao SET `status` = '$status', `dt_atua` = NULL, `usu_atua` = '$usuario', `imp` = '$impresso' WHERE id = $item";
        $sql = mysqli_query( $this->link, $sql );
    }

    /* Recebimento e Retificação  */
    function recebimentoEretificacao( $tipo, $item, $usuario, $status, $qtd){
        if( intval($tipo) == 1 ){ /* Recebimento */
            $sql = "UPDATE estoque_item_requisicao SET `qtd_ent` = '$qtd',`status` = '$status', `dt_atua` = NULL, `usu_atua` = '$usuario'  WHERE id = $item";
            
        }else
        if( intval($tipo) == 2){ /* Retificação */
            $sql = "UPDATE estoque_item_requisicao SET `qtd_slt` = '$qtd',`status` = '$status', `dt_atua` = NULL, `usu_atua` = '$usuario'  WHERE id = $item";
        }
        $sql = mysqli_query( $this->link, $sql );
    }
    

}

/* $requisicao = new Requisicao();
echo $requisicao->cadastraRequisicao(); */