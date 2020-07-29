<?php
 require_once("db.class.php");
 date_default_timezone_set('America/Sao_Paulo');

class Financeiro extends db{
    private $link;


    function __construct(){
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    function geraCompetenciaApartir( $competenciaInicial ){
        $sqlCompetencia = "SELECT * FROM `custo_competencia` where id >= $competenciaInicial";
        $sqlCompetencia = mysqli_query( $this->link, $sqlCompetencia );
        while ( $competencia  = mysqli_fetch_array( $sqlCompetencia)   ) {
            $comp[] = array(
                "id" => $competencia['id'],
                "mes" => $competencia['mes'],
                "ano" =>$competencia['ano']
            );
        }

        return $comp;

    }

    function geraTodasCompetencia( ){
        $sqlCompetencia = "SELECT * FROM `custo_competencia` where ativo = 1 ORDER BY id";
        $sqlCompetencia = mysqli_query($this->link, $sqlCompetencia );
        while ( $competencia  = mysqli_fetch_array( $sqlCompetencia)   ) { 
            $comp[] = array(
                "id" => $competencia['id'],
                "mes" => $competencia['mes'],
                "ano" =>$competencia['ano']
            );
        }

        return $comp;
    }
    /*  */
    function geraRegistroConta(){
        $sqlRegistro = "SELECT id FROM `conta_pagar` ORDER BY `conta_pagar`.`id` DESC LIMIT 1";
        $registro = mysqli_fetch_array( mysqli_query( $this->link, $sqlRegistro ) );

        return intval( $registro['id'] ) + 1;
    }

    function cadConta( $registro, $usu, $unidade, $competencia, $fornecedor, $doc, $categoria, $detalhe, $tipo, $arq ){
        $sqlQuery = "INSERT INTO `conta_pagar`(`id`, `cad_usu`, `cad_unidade`, `competencia`, `fornecedor`, `num_doc`, `categoria`, `detalhe`, `tipo_conta`, `arq`, `status`) VALUES 
        (
            '$registro',
            '$usu',
            '$unidade',
            '$competencia',
            '$fornecedor',
            '$doc',
            '$categoria',
            '$detalhe',
            '$tipo',
            '$arq',
            '1'
        )";

        $sqlQuery = mysqli_query($this->link, $sqlQuery );

        if( $sqlQuery ){
            return 1;
        }else{
            return 0;
        }
    }

    function cadContaItem( $registro, $tdVencimento, $valor ){
        @$totalPArcela = count($valor);
        $cont = 1;
        $vencimento = is_array( $tdVencimento );

        if( is_array( $tdVencimento ) ){
            foreach ($tdVencimento as $key => $value) {
                
                $sqlRegistro = "INSERT INTO `conta_pagar_item`(`conta_pagar`, `dt_vencimento`, `valor`, `ordem`, `status`) VALUES 
                (
                    '$registro',
                    '$value',
                    '$valor[$key]',
                    '$cont/$totalPArcela',
                    '1'
                );";
                $sqlRegistro = mysqli_query( $this->link, $sqlRegistro );
                $cont++;
            }
        }else{
            $sqlRegistro = "INSERT INTO `conta_pagar_item`(`conta_pagar`, `dt_vencimento`, `valor`, `ordem`, `status`) VALUES 
                (
                    '$registro',
                    '$tdVencimento',
                    '$valor',
                    '$cont/$totalPArcela',
                    '1'
                );";
                $sqlRegistro = mysqli_query( $this->link, $sqlRegistro );
        }
            

        if( $sqlRegistro ){
            return 1;
        }else{
            return 0;
        }

      
    }

    function baixa( $id, $valor, $data, $obs ){
        date_default_timezone_set('America/Sao_Paulo');
        $sql = "UPDATE `conta_pagar` SET `status` = '5' WHERE `conta_pagar`.`id` = $id;";
        $sql1 = mysqli_query( $this->link, $sql );
        $sql = "UPDATE `conta_pagar_item` SET `status` = '5', `pago` = '$valor', `dt_pago` = '$data', `obs` = '$obs' WHERE `conta_pagar_item`.`id` = $id;";
        $sql2 = mysqli_query( $this->link, $sql );

        if( $sql1 == true && $sql2 == true ){
            return 1;
        }else{
            return 2;
        }
    }

    function excluir( $id ){
        $sql = "UPDATE `conta_pagar_item` SET `status` = '6' WHERE `conta_pagar_item`.`id` = $id";
        $sql = mysqli_query($this->link, $sql );

        if( $sql ){
            return 1;
        }
    }

    function regPagamento( $pagarItem, $pgValor, $pgData, $pgObs, $cadUsu, $pgTipo, $arq ){
        $sql = "INSERT INTO `reg_pagamentos`(`pagar_item`, `pg_valor`, `pg_data`, `pg_obs`, `cad_usu`, `pg_tipo`, `arq`) VALUES 
        (
            '$pagarItem',
            '$pgValor',
            '$pgData',
            '$pgObs',
            '$cadUsu',
            '$pgTipo',
            '$arq'
        )";
        $sql = mysqli_query( $this->link, $sql );
         if( $sql ){
            return 1;
        }else{
            return 2;
        }
    }

    function regPagamentoValorTotal($idRgPamento){
        $sql = "SELECT SUM(pg_valor) as valor FROM `reg_pagamentos` where pagar_item = $idRgPamento";
        $sql = mysqli_query( $this->link, $sql );
        $sql = mysqli_fetch_array( $sql );
        return number_format($sql['valor'], 2, '.', '');
    }

    function regPagamentoValorTotalSemClassDB($idRgPamento){
        $sql = "SELECT SUM(pg_valor) as valor FROM `reg_pagamentos` where pagar_item = $idRgPamento";
        $sql = mysqli_query( $this->link, $sql );
        $sql = mysqli_fetch_array( $sql );
        return number_format($sql['valor'], 2, '.', ',');
    }

    function regPamentoQuantidadeParcelasPagasClassDB($idRgPamento){
        $sql = "SELECT COUNT(id) as qtd FROM `reg_pagamentos` where pagar_item = $idRgPamento";
        $sql = mysqli_query( $this->link, $sql );
        $sql = mysqli_fetch_array( $sql );
        return $sql['qtd'];
    }

    function mudarValorContaApagar($id, $valor){
        $sql = "UPDATE `conta_pagar_item` SET `valor` = '$valor' WHERE `conta_pagar_item`.`id` = $id";
        $sql = mysqli_query( $this->link, $sql );
    }

    function mudaDataContaApagar($id, $data){
        $sql = "UPDATE `conta_pagar_item` SET `dt_vencimento` = '$data' WHERE `conta_pagar_item`.`id` = $id";
        $sql = mysqli_query( $this->link, $sql );
    }

    function retornaCompetenciaAtual(){
        $mes =  date("m");
        $ano =  date("Y");
        $sql = "SELECT id from custo_competencia WHERE mes = $mes AND ano = $ano";
        $res = mysqli_fetch_array( mysqli_query( $this->link, $sql ) );
        return $res['id'];
    }




}
