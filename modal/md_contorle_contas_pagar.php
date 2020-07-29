<?php
require_once('../validar/class/db.class.php');
session_start();
$objDb = new db();
$link = $objDb->conecta_mysql();
$id_conta = intval($_POST['id']);
$cont = 0;
require_once('../validar/class/class.financeiro.php');
$conta = new Financeiro();
/* $validador = md5("wise123456");
$_SESSION['token_adm_atender_chamado'] = $validador; */

$sql = "SELECT i.id as id, f.nome as fornecedor, p.nome AS categoria, g.nome as unidade,
CONCAT(cp.mes,'/',cp.ano) AS competencia, 
i.dt_vencimento as vencimento, i.ordem as parcela, i.valor as valor, i.status as status, 
c.detalhe AS obs, c.num_doc as doc, c.arq as arq
FROM conta_pagar_item i 
INNER JOIN conta_pagar c 
ON i.conta_pagar = c.id
INNER JOIN cad_fornecedor f 
ON c.fornecedor = f.id
INNER JOIN cad_unidade g 
ON c.cad_unidade = g.id
INNER JOIN plano_contas p
ON c.categoria = p.id
INNER JOIN custo_competencia AS cp
ON c.competencia = cp.id
WHERE i.id = $id_conta";
$conta_info = mysqli_fetch_array(mysqli_query($link, $sql));

$rgValorTotal       = $conta->regPagamentoValorTotalSemClassDB($id_conta);
$rgQtdPagamentos    = $conta->regPamentoQuantidadeParcelasPagasClassDB($id_conta);

if( $conta_info['status'] == 1 ){
    $status = "PENDENTE";
}else
if( $conta_info['status'] == 5 ){
    $status = "FINALIZADO";
}else
if( $conta_info['status'] == 6 ){
    $status = "EXCLUIDO";
}

$sqlAtualizacao = "SELECT * FROM `reg_pagamentos` WHERE pagar_item = $id_conta ORDER BY id";
$sqlAtualizacao = mysqli_query($link, $sqlAtualizacao);
?>
<div class="callout callout-info">
    <h5>Registro: <?php echo $id_conta ?></h5>
    <p>
        <label>fornecedor:</label> <?php echo $conta_info['fornecedor']; ?><br>
        <label>DOC Número:</label> <?php echo $conta_info['doc']; ?><br>
        <label>Vencimento:</label> <span class="data_pg"><?php echo date('d/m/Y', strtotime($conta_info['vencimento'])); ?> - <i class="fas fa-edit"></i></span><br>
        <label>Competência:</label> <?php echo $conta_info['competencia']; ?><br>
        <label>Parcela:</label> <?php echo $conta_info['parcela']; ?><br>
        <label>Valor:</label> <span class="valor_pg"><?php echo 'R$: ' . number_format($conta_info['valor'], 2, ',', '.'); ?> - <i class="fas fa-edit"></i></span><br>
        <label>Status:</label> <?php echo $status; ?><br>
        <label>Parcelas Pagas:</label> <?php echo $rgQtdPagamentos; ?><br>
        <label>Valor Já Pago:</label> <?php echo $rgValorTotal; ?><br>
        <label>Descrição:</label> <?php echo $conta_info['obs']; ?><br>
        <label>Imagem Anexada:</label>
        <?php
        if ($conta_info['arq'] == '*') {
            echo "Sem Anexo!";
        } else {
            echo"<a href='validar/nf_pagar/$conta_info[arq]' download>
            &nbsp;&nbsp;&nbsp;&nbsp;<i class='fas fa-file-download' style='font-size: 20px; color: green'></i>
          </a>";
        }
        ?>
    </p>
</div>
<div class="">
<?php 
while ($pagos = mysqli_fetch_array($sqlAtualizacao)) { 
    $cont++;
    if($pagos['arq'] == '*'){

    }else{

    }
?>
    <div class="callout callout-success">
        <h5>#<?php echo$cont?> Pagamento Efetuado!</h5>
        <p>
        <label>Valor:</label> <?php echo 'R$: ' . number_format($pagos['pg_valor'], 2, ',', '.'); ?> <br>
        <label>Data do Pagamento:</label> <?php echo date('d/m/Y', strtotime($pagos['pg_data'])); ?><br>
        <label>Observação:</label> <?php echo $pagos['pg_obs']; ?><br>
        <label>Anexo:</label> <?php
        if($pagos['arq'] == '*'){
            echo"Sem Anexo";
        }else{
            echo"<a href='validar/$pagos[arq]' download>
            &nbsp;&nbsp;&nbsp;&nbsp;<i class='fas fa-file-download' style='font-size: 20px; color: green'></i>
          </a>";
        }
        ?><br>
        </p>
    </div>
<?php } ?>