<?php
require_once('../validar/class/db.class.php');
session_start();
$objDb = new db();
$link = $objDb->conecta_mysql();
$id_conta = intval($_POST['id']);
$cont = 0;
require_once("../validar/class/class.faturamento.php");
$objFaturamento = new Faturamento();
/* $validador = md5("wise123456");
$_SESSION['token_adm_atender_chamado'] = $validador; */

$sql = "SELECT i.id AS id, i.nf AS nf, f.empresa AS empresa, f.cnpj AS cnpj, 
u.nome AS unidade, i.valor AS valor, i.dt_inicial AS dt_inicial, i.dt_final AS dt_final,
CONCAT(c.mes,'/',c.ano) AS competencia, i.`status` AS `status`, f.obs AS obs, o.nome as oss,
i.arq AS arq, i.nf AS nf
FROM `fat_receber` as f 
INNER JOIN `fat_receber_item` as i 
ON f.id = i.fat_receber
INNER JOIN `cad_unidade` as u 
ON f.unidade = u.id
INNER JOIN `custo_competencia` as c 
ON f.competencia = c.id 
INNER JOIN cad_oss as o
ON f.oss = o.id
WHERE i.id = $id_conta";
$conta_info = mysqli_fetch_array(mysqli_query($link, $sql));

$rgValorTotal       =  "R$: ".$objFaturamento->regPagamentoValorTotal($id_conta);
$rgQtdPagamentos    =   $objFaturamento->regPamentoQuantidadeParcelasPagasClassDB($id_conta);

if( $conta_info['status'] == 1 ){
    $status = "Cadastrado";
}else
if( $conta_info['status'] == 2 ){
    $status = "Em Andamento";
}else
if( $conta_info['status'] == 3 ){
    $status = "Finalizado";
}else
if( $conta_info['status'] == 4 ){
    $status = "Excluido";
}

$sqlAtualizacao = "SELECT * FROM `reg_pagamentos_nf` WHERE item = $id_conta ORDER BY id";
$sqlAtualizacao = mysqli_query($link, $sqlAtualizacao);
?>
<div class="callout callout-info">
    <h5>Registro: <?php echo $id_conta ?></h5>
    <p>
        <label>Empresa:</label> <?php echo $conta_info['empresa']; ?><br>
        <label>CNPJ:</label> <?php echo $conta_info['cnpj']; ?><br>
        <label>Unidade:</label> <?php echo $conta_info['unidade']; ?><br>
        <label>NF:</label> <?php echo $conta_info['nf']; ?><br>
        <label>OSS:</label> <?php echo $conta_info['oss']; ?><br>
        <label>Competência:</label> <?php echo $conta_info['competencia']; ?><br>
        <label>Periodo Inicial:</label> <span class="data_pg"><?php echo date('d/m/Y', strtotime($conta_info['dt_inicial'])); ?> </span><br>
        <label>Periodo Final:</label> <span class="data_pg"><?php echo date('d/m/Y', strtotime($conta_info['dt_final'])); ?> </span><br>
        <label>Valor:</label> <span class="valor_pg"><?php echo 'R$: ' . number_format($conta_info['valor'], 2, ',', '.'); ?></span><br>
        <label>Status:</label> <?php echo $status; ?><br>
        <label>Parcelas Pagas:</label> <?php echo $rgQtdPagamentos; ?><br>
        <label>Valor Já Pago:</label><?php echo 'R$ '.number_format($objFaturamento->regPagamentoValorTotal($id_conta), 2, ',', '.');?><br>
        <label>Descrição:</label> <?php echo $conta_info['obs']; ?><br>
        <label>Imagem Anexada:</label>
        <?php
        if ($conta_info['arq'] == 'fat_arq/*') {
            echo "Sem Anexo!";
        } else {
            echo"<a href='validar/$conta_info[arq]' download>
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
            echo"<a href='validar/faturamento/comprovante_nf/$pagos[arq]' download>
            &nbsp;&nbsp;&nbsp;&nbsp;<i class='fas fa-file-download' style='font-size: 20px; color: green'></i>
          </a>";
        }
        ?><br>
        </p>
    </div>
<?php } ?>