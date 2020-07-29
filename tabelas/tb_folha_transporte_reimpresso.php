<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
?>

    <div class="table-responsive">
        <table class="table table-sm table-striped dataTable js-exportable">
            <thead class="text-center">
                <tr class="bg-info">
                    <th>Pedido</th>
                    <th>Unidade</th>
                    <th>Data</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tfoot class="text-center">
                <tr class="bg-info">
                    <th>Pedido</th>
                    <th>Unidade</th>
                    <th>Data</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
            <tbody>
            <?php
        
                $sql = "SELECT DISTINCT r.requisicao as requisicao, r.data as data, u.nome as unidade
                FROM estoque_item_requisicao i
                INNER JOIN estoque_registro_requisicao r
                ON i.registro = r.requisicao
                INNER JOIN cad_unidade u 
                ON u.id = r.unidade
                where i.imp = 1";
            

            $pedido = mysqli_query($link, $sql);
            while ($pedido1 = mysqli_fetch_array($pedido)) {
                $contString = strlen(24);
                if ($contString > 29) {
                    $conc = '...';
                } else {
                    $conc = '';
                }
            ?>

                <tr class="text-center">

                    <td><?php echo 'REQ'.$pedido1["requisicao"]; ?></td>
                    <td title="<?php echo $pedido1["unidade"]; ?>"><?php echo  substr($pedido1["unidade"], 0, 30).$conc;  ?></td>
                    <td><?php echo date('d/m/Y', strtotime($pedido1["data"])); ?></td>
                    <td><a data-toggle="tooltip" data-placement="top" title="Folha De Impressão" class="folha_reimpressa  elevation-1" value="<?php echo$pedido1["requisicao"];?>">
                            <i class="fas fa-print " style="font-size: 25px; color: Goldenrod"></i>
                        </a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
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