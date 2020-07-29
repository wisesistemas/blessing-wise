<?php
session_start();


require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();

require_once('../validar/class/class.financeiro.php');
$conta = new Financeiro();

$id = intval($_POST['id']);

$sql = "SELECT * FROM `reg_pagamentos` WHERE pagar_item = $id";
$sql = mysqli_query($link, $sql);
$total = 0;
$cont = 1;
?>
<table class="table table-sm table-striped" id="tabela">
    <thead>
        <tr class="text-center table-secondary">
            <th scope="col">#</th>
            <th scope="col">Data</th>
            <th scope="col">Valor</th>
            <th scope="col">Obs</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($res = mysqli_fetch_array($sql)) {
            $total = $total + $res['pg_valor'];
        ?>
            <tr class="text-center">
                <th scope="row"><?php echo $cont++; ?></th>
                <td><?php echo date("d/m/Y", strtotime($res['pg_data'])); ?></td>
                <td><?php echo "R$: " . number_format($res['pg_valor'], 2, '.', ','); ?></td>
                <td title="<?php echo $res['pg_obs']; ?>"><?php echo mb_strimwidth($res['pg_obs'], 0, 20, "..."); ?></td>
            </tr>
        <?php
        }
        ?>
        <tr class="text-center">
            <th scope="row">TOTAL</th>
            <td></td>
            <td><?php echo "R$: " . number_format($total, 2, '.', ','); ?></td>
            <td></td>
        </tr>
    </tbody>
</table>
<div class="form-group col-12 finalizado">
    <hr>
</div>