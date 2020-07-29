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
require_once("validar/class/class.estoque.php");
$objEstoque = new Estoque();



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
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 6000
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(".req_adm").addClass('menu-open');
        $("#folha_transporte").addClass('fas fa-circle nav-icon');


        carregarTabela();
        carregarTabelaReimpresso();



        $("#loading1").hide();

        $(document).on('click', '.folha_impressao', function() {
            var id = $(this).attr("value");
            $("#envia").val(id);
            $("#loading1").show();
            $(".btn-imprimir").show();
            $(".btn-reimprimir").hide();
            $.ajax({
                url: "impressao/visualizar_impressão.php",
                type: "POST",
                data: {
                    id: id,
                    tipo: 2
                },
                success: function(data) {
                    $("#loading1").hide();
                    $("#modal-xl").modal("show");
                    $("#tb_itens").html(data);
                }
            })
        })

        $(document).on('click', '.folha_reimpressa', function() {
            var id = $(this).attr("value");
            $("#envia").val(id);
            $("#loading1").show();
            $(".btn-imprimir").hide();
            $(".btn-reimprimir").show();
            $.ajax({
                url: "impressao/visualizar_impressão.php",
                type: "POST",
                data: {
                    id: id,
                    tipo: 4
                },
                success: function(data) {
                    $("#loading1").hide();
                    $("#modal-xl").modal("show");
                    $("#tb_itens").html(data);
                }
            })
        })

        $("#envia").click(function() {
            var id = $(this).val();
            $.ajax({
                url: "validar/validar.php?id=15",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    $("#modal-xl").modal("hide");
                    Toast.fire({
                        icon: 'success',
                        title: 'Folha de transporte atualizado com sucesso!'
                    })
                    carregarTabela();
                    carregarTabelaReimpresso();
                }
            })

        })



        function carregarTabela() {
            $.ajax({
                url: "tabelas/tb_folha_transporte.php",
                type: "POST",
                success: function(data) {
                    $("#tb_atender_req").html(data);
                }
            });
        }

        function carregarTabelaReimpresso() {
            $.ajax({
                url: "tabelas/tb_folha_transporte_reimpresso.php",
                type: "POST",
                success: function(data) {
                    $("#tb_atender_req_reimpresso").html(data);
                }
            });
        }



    });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">


    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando itens...</div>
        </div>
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
                        <h1 class="m-0 text-dark"><strong>Impressão De Folha De Transporte:</strong>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Folha de Transporte::</strong></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- SELECT2 EXAMPLE -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Folha de Transporte:</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex p-0  bg-light">
                            <h3 class="card-title p-3 "></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Gerar
                                        Folha de Transporte</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Reimprimir
                                        Folha de Transporte</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body"
                            style="padding-left: 1px; padding-right: 1px; padding-top: 0px; padding-bottom: 1px;">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div id="tb_atender_req"></div>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_2">
                                    <div id="tb_atender_req_reimpresso"></div>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card-header -->


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
                                <div class="modal-body" style="height: 500px">

                                    <div id="tb_itens"></div>

                                </div>
                                <div class="modal-footer justify-content-between btn-imprimir">
                                    <button type="button" class="btn btn-default left"
                                        data-dismiss="modal">Fechar</button>
                                    <button type="button" class="btn btn-danger " id="envia" value=""
                                        ata-dismiss="modal">Confirmar Impressão</button>

                                </div>
                                <div class="modal-footer text-right btn-reimprimir">
                                    <button type="button" class="btn btn-default  " data-dismiss="modal">Fechar</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- fim modal -->

                    <?php require_once('footer.php'); ?>
                    <!-- Toastr -->
                    <script src="plugins/toastr/toastr.min.js"></script>

</body>

</html>