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
                    <th>CPF</th>
                    <th>Empresa</th>
                    <th>Funcao</th>
                    <th>unidade</th>
                    <th>Ativo</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tfoot class="text-center BG-INFO elevation-1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Empresa</th>
                    <th>Funcao</th>
                    <th>unidade</th>
                    <th>Ativo</th>
                    <th>Ver</th>
                </tr>
            </tfoot>
            <?php

            $sql = "SELECT * FROM vw_funcionario order by nome";
            $sql = mysqli_query($link, $sql);
            while ($func = mysqli_fetch_array($sql)) {
                $cpfr = preg_replace("/\D/", '', $func["cpf"]);
                $cpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', "\$1.\$2.\$3-\$4", $cpfr)
            ?>

            <tr class="text-center">

                <td><?php echo $func["id"]; ?></td>
                <td><?php echo $func["nome"]; ?></td>
                <td><?php echo $cpf; ?></td>
                <td><?php echo $func["emp_nome"]; ?></td>
                <td><?php echo $func["func_nome"]; ?></td>
                <td><?php echo $func["unidade_nome"]; ?></td>
                <td><?php 
                
                if( intval($func["situacao"]) == 1 ){
                    echo "<i class='fas fa-check-circle text-success' style='font-size: 18px'></i>";
                }else{
                    echo "<i class='fas fa-check-circle text-danger' style='font-size: 18px'></i>";
                }
                
                ?></td>
                <td>

                    <button data-toggle="tooltip" data-placement="top" title="Visualizar"
                        class="btn.btn-app-sm btn-light editar elevation-1"
                        value="<?php 
                            echo
                            $func["id"]
                            .'*'.
                            $func["nome"]
                            .'*'.
                            $cpf
                            .'*'.
                            $func["pis"]
                            .'*'.
                            $func["matricula"]
                            .'*'.
                            $func["nasc"]
                            .'*'.
                            $func["emp_id"]
                            .'*'.
                            $func["unidade_id"]
                            .'*'.
                            $func["func_id"]
                            .'*'.
                            $func["escala_id"]
                            .'*'.
                            $func["banco"]
                            .'*'.
                            $func["agencia"]
                            .'*'.
                            $func["conta"]
                            .'*'.
                            $func["digito"]
                            .'*'.
                            $func["situacao"];
                        ?>">
                        <i class="fas fa-eye" style="font-size: 22px; color: #29088A"></i>

                    </button>

                </td>
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