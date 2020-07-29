<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
@$unidade = $_POST['unidade'];


!empty($_POST['inicio'])   ? $inicio  = $_POST['inicio']  : $inicio   = '0000-00-00';
!empty($_POST['fim'])   ? $fim  = $_POST['fim']  : $fim   = '9999-12-31';
@$str = implode(',', $unidade);

@$todasUnidades = in_array('0', $unidade);
?>

<div class="table-responsive">
    <table class="table table-sm table-striped dataTable js-exportable table-bordered">
        <thead>
            <tr class="text-center BG-INFO elevation-1">
                <th>ID</th>
                <th>ITEM</th>
                <th>REFERENCIA</th>
                <th>MARCA</th>
                <th>FAMILIA</th>
                <th>QTD SOLICITADA</th>
                <th>QTD ENVIADA</th>
                <th>QTD RECEBIDA</th>   

            </tr>
        </thead>
        <tfoot>
            <tr class="text-center BG-INFO elevation-1">
                <th>ID</th>
                <th>ITEM</th>
                <th>REFERENCIA</th>
                <th>MARCA</th>
                <th>FAMILIA</th>
                <th>QTD SOLICITADA</th>
                <th>QTD ENVIADA</th>
                <th>QTD RECEBIDA</th>

            </tr>
        </tfoot>
        <tbody>
            <?php
            if ($todasUnidades == true) {
                $sql = "
            SELECT DISTINCT i.id as id, i.nome as nome, i.referencia as referencia, i.marca as marca, m.medida as medida, SUM(r.qtd_slt) as solicitado, SUM(r.qtd_env) as enviado,  SUM(r.qtd_ent) as entregue
  FROM estoque_registro_requisicao e 
  INNER JOIN estoque_item_requisicao r 
  ON e.requisicao = r.registro
  INNER JOIN cad_item i 
  ON r.item = i.id
  INNER JOIN cad_unidade u 
  ON e.unidade = u.id
  INNER JOIN cad_medida m 
  ON i.und_estoque = m.id
  where  e.data BETWEEN '$inicio' AND '$fim' GROUP BY id
          ";
            } else {
                $sql = "
            SELECT DISTINCT i.id as id, i.nome as nome, i.referencia as referencia, i.marca as marca, m.medida as medida, SUM(r.qtd_slt) as solicitado, SUM(r.qtd_env) as enviado,  SUM(r.qtd_ent) as entregue
  FROM estoque_registro_requisicao e 
  INNER JOIN estoque_item_requisicao r 
  ON e.requisicao = r.registro
  INNER JOIN cad_item i 
  ON r.item = i.id
  INNER JOIN cad_unidade u 
  ON e.unidade = u.id
  INNER JOIN cad_medida m 
  ON i.und_estoque = m.id
  where e.unidade in($str) && e.data BETWEEN '$inicio' AND '$fim' GROUP BY id
          ";
            }


            $sql = mysqli_query($link, $sql);

            while (@$item = mysqli_fetch_array($sql)) {
            ?>
                <tr class="text-center text-sm">
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['nome']; ?></td>
                    <td><?php echo $item['referencia']; ?></td>
                    <td><?php echo $item['marca']; ?></td>
                    <td><?php echo $item['medida']; ?></td>
                    <td><?php echo $item['solicitado']; ?></td>
                    <td><?php echo $item['enviado']; ?></td>
                    <td><?php echo $item['entregue']; ?></td>
                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>
</div>
</div>
<!-- Tabela  -->
<script src="plugins/tabela/plugins/jquery/jquery.js"></script>
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
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>