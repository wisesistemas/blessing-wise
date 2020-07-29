<?php
session_start();
if( !isset($_SESSION['usuario_id'])  && !isset($_SESSION['usuario_id_unidade']) ){
    header('Location: index.php?erro=1');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset( $_SESSION['itens_insert_estoque'] );
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
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
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

        $(".estoque_admin").addClass('menu-open');
        $("#rel_admin_rensacao_unidade").addClass('fas fa-circle nav-icon');



        $("#inserir").click(function() {
            var unidade = $("#unidade").val();
            $.ajax({
                url: "tabelas/tb_rel_transacao_unidade.php",
                type: "POST",
                data: {unidade: unidade},
                success: function(data) {
                    $("#tb_itens").html(data).show();

                }
            });
        })

   

        
        

        $("#loading1").hide();

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
                            <h1 class="m-0 text-dark">Relatório transação estoque</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Relatório Estoque</strong></li>
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
                    <div class="card card-default elevation-1">
                        <div class="card-header">
                            <h3 class="card-title">Relatório transação estoque:</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <form id="form">
                            <div class="card-body" style="padding-left: 10px; padding-right: 10px;">
                                <div class="row">
                                    <input type="hidden" name="token" value="<?php  echo$token;?>">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Selecione a unidade:</label>
                                            <select class="form-control form-control-sm select2" style="width: 100%;"
                                                id="unidade" name="unidade">
                                                <option selected="selected" value="">...</option>
                                                <?php 
                                        $sql = "SELECT * from cad_unidade";
                                        $sql = mysqli_query( $link, $sql );
                                        while ( $sqlItem = mysqli_fetch_array( $sql ) ) {
                                        ?>
                                                <option value="<?php echo$sqlItem['id']?>"><?php echo$sqlItem['nome']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-1 my-2 text-right">
                                        <a class="btn btn-app btn-sm elevation-1" id="inserir" data-toggle="tooltip"
                                            data-placement="top" title="Inserir item na lista">
                                            <i class="fas fa-arrow-down"
                                                style="font-size: 24px; color: green"></i><strong>Inserir</strong>
                                        </a>
                                    </div>
                                </div>
                        </form>
                        <div id="tb_itens"></div>
                        <div class="row" style="display: none" id="button">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-app btn-sm" id="cancelar" data-toggle="tooltip" data-placement="top"
                                    title="Cancelar todos os itens.">
                                    <i class="fas fa-times"
                                        style="font-size: 24px; color: red"></i></i><strong>Cancelar</strong>
                                </a>
                                <a class="btn btn-app btn-sm" id="salvar" data-toggle="tooltip" data-placement="top"
                                    title="Salvar.">
                                    <i class="far fa-save"
                                        style="font-size: 24px; color: green"></i></i><strong>Salvar</strong>
                                </a>
                            </div>

                        </div>

                  
                        </div>
                        <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
                        <!-- ./wrapper -->

                        <?php require_once('footer.php'); ?>
                        <!-- Select2 -->
                        <script src="plugins/select2/js/select2.full.min.js"></script>
                        <!-- Toastr -->
                        <script src="plugins/toastr/toastr.min.js"></script>

                        <script>
                        $('.select2').select2()

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })
                        </script>
</body>

</html>