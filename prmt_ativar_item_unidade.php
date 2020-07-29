<?php
session_start();
if(!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_nome'])  ){
    header('Location: index.php');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;

$sqlUnidade = "select * from cad_unidade";
$sqlUnidade = mysqli_query($link, $sqlUnidade);

$sqlFamilia   = "select * from cad_familia";
$sqlFamilia   = mysqli_query($link, $sqlFamilia);

$sqlGenero  = "select * from cad_genero";
$sqlGenero   = mysqli_query($link, $sqlGenero);

$sqlReferencia   = "SELECT referencia FROM cad_item WHERE referencia <> ''";
$sqlReferencia   = mysqli_query($link, $sqlReferencia);

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
            window.alert = function() {};
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })

            $(".parametrizacao").addClass('menu-open');
            $(".prmt_estoque").addClass('menu-open');
            $("#prmt_ativar_item_unidade").addClass('fas fa-circle nav-icon');

            function cancelarComSucesso() {
                Toast.fire({
                    icon: 'success',
                    title: 'Extra cancelado com sucesso!'
                })
            }



            $("#gerar").click(function() {
                var form = $("#form12").serialize();
                $("#loading1").show();
                $.ajax({
                    url: 'tabelas/ul.prmt_ativar_unidade_x_item.php',
                    type: 'POST',
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    data: form,
                    success: function(data) {
                        $("#loading1").hide();
                        console.log(data);
                        $("#tabela").html(data);
                    }
                })
            })

            $(document).on('click', '.desativar', function() {
                var value = $(this).attr('value');
                $.ajax({
                    url: 'validar/validar.php?id=24',
                    type: 'POST',
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    data: {
                        value: value,
                        acao: 0
                    },
                    success: function(data) {
                        var form = $("#form12").serialize();
                        $("#loading1").show();
                        $.ajax({
                            url: 'tabelas/ul.prmt_ativar_unidade_x_item.php',
                            type: 'POST',
                            beforeSend: function() {

                            },
                            complete: function() {

                            },
                            data: form,
                            success: function(data) {
                                $("#loading1").hide();
                                console.log(data);
                                $("#tabela").html(data);
                            }
                        })
                    }
                })
            });

            $(document).on('click', '.ativar', function() {
                var value = $(this).attr('value');
                $.ajax({
                    url: 'validar/validar.php?id=24',
                    type: 'POST',
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    data: {
                        value: value,
                        acao: 1
                    },
                    success: function(data) {
                        var form = $("#form12").serialize();
                        $("#loading1").show();
                        $.ajax({
                            url: 'tabelas/ul.prmt_ativar_unidade_x_item.php',
                            type: 'POST',
                            beforeSend: function() {

                            },
                            complete: function() {

                            },
                            data: form,
                            success: function(data) {
                                $("#loading1").hide();
                                console.log(data);
                                $("#tabela").html(data);
                            }
                        })
                    }
                })
            });


            $("#loading1").hide();



        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">

    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando dados...</div>
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
                            <h1 class="m-0 text-dark">Ativar item por unidade</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Ativar item por unidade</strong></li>
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
                            <h3 class="card-title">Ativar item por unidade:</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <form id="form12" class="col-md-12 my-2">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="unidade">Unidade</label>
                                    <select class="form-control form-control-sm select2" name="unidade" id="unidade" required>
                                        <?php
                                        while ($unidades = mysqli_fetch_array($sqlUnidade)) {
                                        ?>
                                            <option value="<?php echo $unidades['id']; ?>"><?php echo $unidades['nome']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="status">Status</label>
                                    <select class="form-control form-control-sm select2" name="status" id="status" required>
                                        <option value="1">Todos</option>
                                        <option value="2">Ativos</option>
                                        <option value="3">Desativados</option>
                                    </select>
                                </div>



                                <div class="form-group col-md-12">
                                    <hr>
                                
                                <button type="button" class="btn btn-primary btn-lg btn-block btn-sm" id="gerar">Buscar</button>
                        </form>

                        <div class="col-12 my-1">
                            <div id="tabela"></div>
                        </div>
                        <!-- ./wrapper -->
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">WISE - SISTEMAS</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <!--   <iframe class="embed-responsive embed-responsive-1by1" src="analisar_extra.php?query_extras=4260" allowfullscreen></iframe> -->
                                        </div>
                                        <form id="form1">
                                            <div id="getExtra"></div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Fechar Janela</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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