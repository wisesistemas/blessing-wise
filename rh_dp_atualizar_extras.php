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

//--Query Unidade do Extra
$sql = "SELECT * from cad_unidade where tipo != 6";
$query_unidadeextra = mysqli_query($link, $sql);
//--Query Unidade do Extra
$sql = "SELECT * from cad_unidade where tipo != 6";
$query_unidadeextra2 = mysqli_query($link, $sql);
//--Query Unidade do Extra
$sql = "SELECT * from cad_unidade where tipo != 6";
$query_unidadeextra3 = mysqli_query($link, $sql);
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>


    <?php require_once('head.php'); ?>

    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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

            $(".rh_dp").addClass('menu-open');
            $(".rh_dp_admin").addClass('menu-open');
            $("#rh_dp_atualizar_exreas").addClass('fas fa-circle nav-icon');

            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })

            $("#liberar_analise").click(function() {
                $("#liberar").modal("show");
            });

            $("#sispag_gerado").click(function() {
                $("#sispag").modal("show");
            });

            $("#atualizar_pago").click(function() {
                $("#pago").modal("show");
            });

            $("#form_atualizar").click(function() {
                var unidade = $("#unidades1").val();
                var data = $("#data").val();
                atualiza(unidade, 5, 10, data);


            });

            $("#form_sispag").click(function() {
                var unidade = $("#unidades").val();
                atualiza(unidade, 20, 40);
            });

            $("#form_pago").click(function() {
                var unidade = $("#unidades2").val();
                atualiza(unidade, 40, 50);
            });

            function atualiza(_unidade,  _statusNovo, _statusAntigo, _data = null) {
                $.ajax({
                    url: "validar/validar.php?id=21",
                    type: "POST",
                    beforeSend: function() {

                    },
                    data: {
                        unidade: _unidade,
                        statusAntigo: _statusAntigo,
                        statusNovo: _statusNovo,
                        data: _data
                    },
                    success: function(data) {
                        console.log("data função: " + data);
                        if (data == "sem_unidade") {
                            Toast.fire({
                                icon: 'error',
                                title: 'Atualização sem unidade: Por favor, selecione uma unidade!!'
                            })
                        } else {
                            Toast.fire({
                                icon: 'success',
                                title: 'Registros atualizados com sucesso: Extras atualizados ' + data
                            })
                        }
                    }
                })
            }
        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">


    <!--  <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando itens...</div>
        </div> -->

    <?php require_once('menu_superior.php'); ?>
    <?php require_once('menu_lataral.php'); ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid elevation">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><strong>Atualizar status de extra:</strong>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Atualizar status de extra:</strong></li>
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
                    <div class="card-header ">
                        <h3 class="card-title">Atualizar status de extra:</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="col-sm-12 my-2">
                        <div class="card-body text-center">
                            <p></p>
                            <a class="btn btn-app" id="liberar_analise">
                                <i class="fas fa-retweet"></i>&nbsp&nbsp&nbsp&nbspLiberar para analise&nbsp&nbsp&nbsp&nbsp
                            </a>
                            <a class="btn btn-app" id="sispag_gerado">
                                <i class="fas fa-retweet"></i> Atualizar SISPAG Gerado
                            </a>
                            <a class="btn btn-app" id="atualizar_pago">
                                <i class="fas fa-retweet"></i>&nbsp&nbsp&nbsp&nbsp&nbspAtualizar para Pago&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            </a>
                        </div>
                    </div>


                    <div class="card-footer text-right text-info" style="display: block;">
                        <small>WISE - SISTEMAS</small>
                    </div>
                    <!-- ./wrapper -->
                    <!-- Large modal liberar -->
                    <div class="modal fade" id="liberar">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">WISE - SISTEMA</h4>
                                    <button type="button" class="close elevation-1" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="callout callout-info">
                                        <h5>Atualizar extra para liberação</h5>
                                        <p>Todos extras cadastrados serão atualizados para analise.</p>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="unidades">Unidade do Extra</label>
                                            <select name="unidades[]" class="chosen-select1 form-control" tabindex="3" id="unidades1" required multiple>
                                                <option value="<?php echo "todas"; ?>">TODAS UNIDADES</option>
                                                <?php while ($row = mysqli_fetch_array($query_unidadeextra)) { ?>
                                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row['nome'] ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="data">Data Máxima Para a Atualização</label>
                                            <input type="date" name="data" class="form-control" tabindex="3" id="data" required>
                                        </div>
                                    </div>
                                    </form>
                                    <hr />
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default elevation-1" data-dismiss="modal">Fechar</button>
                                    <button type="button" class="btn btn-danger elevation-1" id="form_atualizar">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- fim modal -->

                    <!-- Large modal SISPAG -->
                    <div class="modal fade" id="sispag">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">WISE - SISTEMA</h4>
                                    <button type="button" class="close elevation-1" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="callout callout-info">
                                        <h5>Atualizar extras para status SISPAG gerado</h5>
                                        <p>Todos extras liberados serão atualizados para SISPAG gerado.</p>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="unidades">Unidade do Extra</label>
                                            <select name="unidades[]" class="chosen-select1 form-control" tabindex="3" id="unidades" required multiple>
                                                <option value="<?php echo "todas"; ?>">TODAS UNIDADES</option>
                                                <?php while ($row = mysqli_fetch_array($query_unidadeextra2)) { ?>
                                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row['nome'] ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    </form>
                                    <hr />
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default elevation-1" data-dismiss="modal">Fechar</button>
                                    <button type="button" class="btn btn-danger elevation-1" id="form_sispag">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- fim modal -->

                    <!-- Large modal Pago -->
                    <div class="modal fade" id="pago">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">WISE - SISTEMA</h4>
                                    <button type="button" class="close elevation-1" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="callout callout-info">
                                        <h5>Atualizar extras para pago.</h5>
                                        <p>Todos extras com status SISPAG gerado serão atualizados para extra pago.</p>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="unidades">Unidade do Extra</label>
                                            <select name="unidades[]" class="chosen-select1 form-control" tabindex="3" id="unidades2" required multiple>
                                                <option value="<?php echo "todas"; ?>">TODAS UNIDADES</option>
                                                <?php while ($row = mysqli_fetch_array($query_unidadeextra3)) { ?>
                                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row['nome'] ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    </form>
                                    <hr />
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default elevation-1" data-dismiss="modal">Fechar</button>
                                    <button type="button" class="btn btn-danger elevation-1" id="form_pago">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- fim modal -->

                    <?php require_once('footer.php'); ?>
                    <!-- Select2 -->
                    <script src="plugins/select2/js/select2.full.min.js"></script>
                    <!-- Toastr -->
                    <script src="plugins/toastr/toastr.min.js"></script>
                    <script>
                        //Initialize Select2 Elements
                        $('.chosen-select1').select2({
                            theme: 'bootstrap4'
                        })
                    </script>
</body>

</html>