<?php
session_start();
if( !isset($_SESSION['usuario_id'])  && !isset($_SESSION['usuario_id_unidade']) ){
    header('Location: index.php?erro=1');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;


?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>


    <?php require_once('head.php'); ?>
    <!-- TABELA -->
    <link href="plugins/tabela/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="plugins/tabela/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="plugins/tabela/css/style1.css" rel="stylesheet">
    <!-- FIM TABELA -->



    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">




    <script>
        $(document).ready(function() {


            $(".req_adm").addClass('menu-open');
            $(".req_adm_relatorio").addClass('menu-open');
            $("#req_admin_rel_ver_requisicoes").addClass('fas fa-circle nav-icon');







            $("#loading1").hide();

            $(document).on('click', '.visualizar_requisicao', function() {
                var id = $(this).attr("value");
                $("#envia").text("Cancelar Requisiação").val("1_" + id);
                $("#loading1").show();
                $("#envia_form").hide();
                $("#envia").show();
                $.ajax({
                    url: "tabelas/tb_req_tela_visualizar_itens.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $("#loading1").hide();
                        $("#modal-xl").modal("show");
                        $("#tb_itens").html(data);
                    }
                })
            })






        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">


    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando itens...</div>
        </div>

        <?php require_once('menu_superior.php'); ?>
        <?php require_once('menu_lataral.php'); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><strong>Consultar requisição:</strong>
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Consultar requisição ADM:</strong></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid ">
                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-default elevation-1">
                        <div class="card-header">
                            <h3 class="card-title">Consultar requisição:</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="col-sm-12 my-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped dataTable js-exportable table-bordered">
                                    <thead class="text-center BG-INFO elevation-1">
                                        <tr>
                                            <th>Pedido</th>
                                            <th>Unidade</th>

                                            <th>Data / Hora</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="text-center BG-INFO elevation-1">
                                        <tr>
                                            <th>Pedido</th>
                                            <th>Unidade</th>

                                            <th>Data / Hora</th>
                                            <th>Ação</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php


                                    $sql = "SELECT DISTINCT r.requisicao as registro, u.nome as unidade, r.data as data, r.hora as hora
    FROM estoque_registro_requisicao r 
    INNER JOIN cad_unidade u 
    ON r.unidade = u.id";






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
                                            <td> <?php echo 'REQ' . $pedido1["registro"]; ?> </td>
                                            <td title="<?php echo $pedido1["unidade"]; ?>">
                                                <?php echo  substr($pedido1["unidade"], 0, 30) . $conc;  ?> </td>
                                            <td>
                                                <?php echo date('d/m/Y', strtotime($pedido1["data"])) . " " . date('h:s', strtotime($pedido1["hora"])) . 'h'; ?>
                                            </td>
                                            <td>

                                                <button data-toggle="tooltip" data-placement="top" title="Visualizar" class="btn.btn-app-sm btn-light visualizar_requisicao elevation-1" value="pedido=<?php echo $pedido1['registro']; ?>">
                                                    <i class="fas fa-eye" style="font-size: 22px; color: #29088A"></i>

                                                </button>

                                            </td>
                                        </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>


                            <div class="card-footer text-right text-info" style="display: block;">
                                <small>WISE - SISTEMAS</small>
                            </div>
                            <!-- ./wrapper -->
                            <!-- Large modal -->
                            <div class="modal fade" id="modal-xl">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">WISE - SISTEMA</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form">
                                                <div id="tb_itens"></div>
                                            </form>
                                            <div class="callout callout-success" style="display: none" id="resposta">
                                                <h5>WISE - SISTEMA</h5>
                                                <div id="registrando">
                                                    <p>
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        Por favor, aguarde! Inserindo registros.
                                                    </p>
                                                </div>
                                                <p id="sucesso" style="display: none">Registros inseridos com sucesso!!
                                                </p>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-left ">
                                            <button type="button" class="btn btn-default elevation-1" data-dismiss="modal">Fechar Janela</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- fim modal -->

                            <?php require_once('footer.php'); ?>
                            <!-- Toastr -->
                            <script src="plugins/toastr/toastr.min.js"></script>
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
                            <script src="plugins/tabela/plugins/jquery-datatable/extensions/export/jszip.min.js">
                            </script>
                            <script src="plugins/tabela/plugins/jquery-datatable/extensions/export/pdfmake.min.js">
                            </script>
                            <script src="plugins/tabela/plugins/jquery-datatable/extensions/export/vfs_fonts.js">
                            </script>
                            <script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.html5.min.js">
                            </script>
                            <script src="plugins/tabela/plugins/jquery-datatable/extensions/export/buttons.print.min.js">
                            </script>
                            <script src="plugins/tabela/js/pages/tables/jquery-datatable.js"></script>
                            <!-- FIM Tabela  -->
                            <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>