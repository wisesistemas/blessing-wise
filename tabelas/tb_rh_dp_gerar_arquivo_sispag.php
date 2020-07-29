<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
require_once("../validar/class/class.estoque.php");
$objEstoque = new Estoque();
session_start();
$unidade = $_POST['unidade'];
?>
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">

<div class="table-responsive">
    <table class="table table-sm table-striped dataTable js-exportable table-bordered">
        <thead>
            <tr class="text-center bg-info elevation-1">
                <th>NOME</th>
                <th>CPF</th>
                <th>CNPJ</th>
                <th>BANCO</th>
                <th>AGENCIA</th>
                <th>CONTA</th>
                <th>DIGITO</th>
                <th>VALOR</th>
            </tr>
        </thead>
        <tfoot>
            <tr class="text-center bg-info elevation-1">
                <th>NOME</th>
                <th>CPF</th>
                <th>CNPJ</th>
                <th>BANCO</th>
                <th>AGENCIA</th>
                <th>CONTA</th>
                <th>DIGITO</th>
                <th>VALOR</th>
            </tr>
            <tbody class="text-center">
                                        <?php

                                            
  
$sql = "SELECT c.nome, p.cnpj AS cnpj, c.cpf, c.banco, c.agencia, c.conta, c.digito, SUM(e.valor_pagar) as valor 
FROM extras e 
INNER JOIN cad_funcionario c 
ON e.nome = c.cpf
INNER JOIN cad_empresa p 
ON c.empresa = p.codigo
WHERE e.status = 40 GROUP BY c.cpf";
$sql = mysqli_query( $link, $sql );

                                            while( $extras = mysqli_fetch_array( $sql ) ) {
                                        ?>
                                        <tr >
                                            <td><?php echo $extras["nome"] ?></td>
                                            <td><?php echo $extras["cpf"] ?></td>
                                            <td><?php echo $extras["cnpj"] ?></td>
                                            <td><?php echo str_pad($extras["banco"], 3, '0', STR_PAD_LEFT) ?></td>
                                            <td><?php echo str_pad($extras["agencia"], 4, '0', STR_PAD_LEFT) ?></td>
                                            <td><?php echo $extras["conta"] ?></td>
                                            <td><?php echo $extras["digito"] ?></td>
                                            <td><?php echo number_format($extras["valor"], 2, '.', '')?></td>
                                        </tr>
                                        <?php
                                            }
                                        ?>

                                    </tbody>
    </table>
</div>
<!-- Tabela  -->
<script src="plugins/tabela/plugins/jquery/jquery.min.js"></script>
<script src="plugins/tabela/plugins/node-waves/waves.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.flash.min.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.html5.min.js">
</script>
<script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.print.min.js">
</script>
<script src="plugins/tabela/js/pages/tables/jquery-datatable.js"></script>
<!-- FIM Tabela  -->