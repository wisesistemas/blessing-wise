<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
session_start();
?>
    <div class="table-responsive my-2">
        <table class="table table-sm table-striped dataTable js-exportable table-bordered">
            <thead class="text-center BG-INFO elevation-1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Familia</th>
                    <th>Genero</th>
                    <th>Referencia</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tfoot class="text-center BG-INFO elevation-1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Familia</th>
                    <th>Genero</th>
                    <th>Referencia</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
            <?php
            if(intval($_POST['status']) == 1 ){
                $sql = "SELECT item.id AS id_item,
                item.nome AS nome,
                item.referencia AS referencia,
                f.familia AS familia,
                g.genero AS genero,
                IFNULL(rel.unidade, '0') AS unidade,
                IFNULL(rel.ativo, '0') AS ativo
                FROM cad_item AS item
                INNER JOIN cad_genero AS g 
                ON item.genero = g.id
                INNER JOIN cad_familia AS f 
                ON item.familia = f.id
                LEFT JOIN item_unidade AS rel
                ON item.id = rel.item
                WHERE rel.unidade = $_POST[unidade] OR rel.unidade  IS NULL";
            }else
            if(intval($_POST['status']) == 2 ){
                $sql = "SELECT item.id AS id_item,
                item.nome AS nome,
                item.referencia AS referencia,
                f.familia AS familia,
                g.genero AS genero,
                IFNULL(rel.unidade, '0') AS unidade,
                IFNULL(rel.ativo, '0') AS ativo
                FROM cad_item AS item
                INNER JOIN cad_genero AS g 
                ON item.genero = g.id
                INNER JOIN cad_familia AS f 
                ON item.familia = f.id
                LEFT JOIN item_unidade AS rel
                ON item.id = rel.item
                WHERE rel.unidade = $_POST[unidade] AND rel.ativo = 1";
            }else
            if(intval($_POST['status']) == 3 ){
                $sql = "SELECT item.id AS id_item,
                item.nome AS nome,
                item.referencia AS referencia,
                f.familia AS familia,
                g.genero AS genero,
                IFNULL(rel.unidade, '0') AS unidade,
                IFNULL(rel.ativo, '0') AS ativo
                FROM cad_item AS item
                INNER JOIN cad_genero AS g 
                ON item.genero = g.id
                INNER JOIN cad_familia AS f 
                ON item.familia = f.id
                LEFT JOIN item_unidade AS rel
                ON item.id = rel.item
                WHERE rel.unidade = $_POST[unidade] AND rel.ativo = 0 OR rel.unidade  IS NULL ";
            }




            $sql = mysqli_query($link, $sql);
            while ($item = mysqli_fetch_array($sql)) {
            ?>

            <tr class="text-center">

                <td><?php echo $item["id_item"]; ?></td>
                <td><?php echo$item["nome"]; ?></td>
                <td><?php echo $item["familia"]; ?></td>
                <td><?php echo $item["genero"]; ?></td>
                <td><?php echo $item["referencia"]; ?></td>
                <td>
                    <?php if( intval($item["ativo"]) == 1){ ?>
                        <a data-toggle="tooltip" data-placement="top" title="Visualizar"
                            class="btn.btn-app-sm btn-light elevation-1 desativar"
                            value="<?php echo$_POST['unidade'].'*'.$item["id_item"]; ?>">
                            <i class="fas fa-check-circle" style="font-size: 22px; color: green"></i>
                    </a>
                    <?php }else{ ?>
                        <a data-toggle="tooltip" data-placement="top" title="Visualizar"
                            class="btn.btn-app-sm btn-light elevation-1 ativar"
                            value="<?php echo$_POST['unidade'].'*'.$item["id_item"]; ?>">
                            <i class="fas fa-check-circle" style="font-size: 22px; color: red"></i>
                    </a>
                    <?php } ?>
                </td>
            </tr>

            <?php } ?>
            </tbody>
        </table>
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