<?php
session_start();
if (!isset($_SESSION['usuario_id'])  && !isset($_SESSION['usuario_id_unidade'])) {
    header('Location: index.php?erro=1');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;

$sql1 = "SELECT * from cad_item";
$sql1 = mysqli_query($link, $sql1);

$cont = 0;

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
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>



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

            $(".estoque").addClass('menu-open');
            $("#set_invenratio").addClass('fas fa-circle nav-icon');

            $("#gerar_pedido").click( function(){
                $.ajax({
                    url: "validar/validar.php?id=32",
                    type: "POST",
                    success: function(){
                        location.reload();
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
            <div class="text-bold pt-2">Carregando...</div>
        </div>

        <?php require_once('menu_superior.php'); ?>
        <?php require_once('menu_lataral.php'); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper ">
            <!-- Content Header (Page header) -->
            <div class="content-header ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Inventário estoque</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Inventário</strong></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content ">
                <div class="container-fluid ">
                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-default elevation-1">
                        <div class="card-header ">
                            <h3 class="card-title">Inventário estoque:</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                            </div>
                        </div>
                        <div class="col-sm-12 text-right my-2">
                            <a class="btn btn-app" id="gerar_pedido">
                                <i class="fas fa-play"></i> Gerar Pedido
                            </a>
                        </div>
                        <form id="form_fornecedor">
                            <!-- /.card-header -->
                            <div class="col-md-12">
                                <?php
                                $sql = "SELECT item, SUM(qtd) AS qtd FROM vw_lista_compras GROUP BY item";
                                $sql = mysqli_query($link, $sql);
                                $cont = 0;
                                while ($item = mysqli_fetch_array($sql)) {
                                    $cont++;
                                ?>

                                    <div class="accordion" id="accordionExample<?php echo $cont; ?>">
                                        <div class="card  bg-info">
                                            <div class="card-header" id="headingOne<?php echo $cont; ?>">
                                                <h5 class="mb-0">
                                                    <button class="btn col-12" type="button" data-toggle="collapse" data-target="#collapseOne<?php echo $cont; ?>" aria-expanded="true" aria-controls="collapseOne">
                                                        <?php echo $item['item'] . '<br>Contidade: ' . $item['qtd']; ?>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapseOne<?php echo $cont; ?>" class="collapse " aria-labelledby="headingOne<?php echo $cont; ?>" data-parent="#accordionExample<?php echo $cont; ?>">
                                                <div class="card-body bg-red">
                                                    <!-- corpo -->
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr class="text-center">
                                                                    <th scope="col">Lista</th>
                                                                    <th scope="col">Unidade Entrega</th>
                                                                    <th scope="col">QTD.S</th>
                                                                    <th scope="col">QTS.C</th>
                                                                    <th scope="col">Fornecedor</th>
                                                                    <th scope="col">V. Unitário</th>
                                                                    <th scope="col">Entrega</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $sql1 = "SELECT* FROM vw_lista_compras WHERE item = '$item[item]';";
                                                                $sql1 = mysqli_query($link, $sql1);

                                                                while ($list = mysqli_fetch_array($sql1)) {
                                                                ?>
                                                                    <input type="hidden" name="id_item_lista[]" value="<?php echo $list['id_item_lista']; ?>">
                                                                    <tr class="text-center">
                                                                        <th scope="row" style="font-size: 15px;"><?php echo $list['lista']; ?></th>
                                                                        <td>
                                                                            <select class="select2" name="entrega[]">
                                                                                <option selected="selected" value="<?php echo $list['id_unidade']; ?>"><?php echo $list['unidade']; ?></option>
                                                                                <?php
                                                                                $sql21 = "SELECT * FROM cad_unidade";
                                                                                $sql21 = mysqli_query($link, $sql21);
                                                                                while ($sqlItem11 = mysqli_fetch_array($sql21)) {
                                                                                ?>
                                                                                    <option value="<?php echo $sqlItem11['id'] ?>"><?php echo $sqlItem11['nome'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td style="font-size: 15px;"><?php echo $list['qtd']; ?></td>
                                                                        <td style="width: 120px;"><input type="number" name="qtd_compra[]" class="form-control form-control-sm col-sm-12"></td>
                                                                        <td>
                                                                            <select class="select2" name="fornecedor[]">

                                                                                <?php
                                                                                $sql21 = "SELECT * FROM cad_fornecedor";
                                                                                $sql21 = mysqli_query($link, $sql21);
                                                                                while ($sqlItem11 = mysqli_fetch_array($sql21)) {
                                                                                ?>
                                                                                    <option value="<?php echo $sqlItem11['id'] ?>"><?php echo $sqlItem11['nome'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td style="width: 120px;"><input type="number" class="form-control form-control-sm" name="unitario[]"></td>
                                                                        <td style="width: 120px;"><input type="date" class="form-control form-control-sm" name="data_entrega[]"></td>
                                                                    </tr>
                                                                    <tr class="text-center">
                                                                        <th></th>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>fsfs</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>

                                                        <!-- fim corpo -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     
                                    <?php } ?>

                                        <button type="submit">enviar</button>
                                        
                                    </div>

                        </form>
                        <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
                        <!-- ./wrapper -->

                        <?php require_once('footer.php'); ?>
                        <!-- Select2 -->
                        <script src="plugins/select2/js/select2.full.min.js"></script>
                        <!-- Toastr -->
                        <script src="plugins/toastr/toastr.min.js"></script>
                        <!-- jquery-validation -->
                        <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
                        <script src="plugins/jquery-validation/additional-methods.min.js"></script>

                        <script>
                            $('.select2').select2()

                            //Initialize Select2 Elements
                            $('.select2bs4').select2({
                                theme: 'bootstrap4'
                            })

                            $("#form_fornecedor").validate({
                                rules: {

                                },
                                messages: {

                                },
                                errorElement: "span",
                                errorPlacement: function(error, element) {
                                    error.addClass("invalid-feedback");
                                    element.closest(".form-group").append(error);
                                },
                                highlight: function(element, errorClass, validClass) {
                                    $(element).addClass("is-invalid");
                                },
                                unhighlight: function(element, errorClass, validClass) {
                                    $(element).removeClass("is-invalid");
                                },
                                submitHandler: function(form) {

                                    var form = $('#form_fornecedor')[0];
                                    var data = new FormData(form);
                                    $.ajax({
                                        type: "POST",
                                        enctype: 'multipart/form-data',
                                        url: "validar/validar.php?id=31",
                                        beforeSend: function() {

                                        },
                                        complete: function() {

                                        },
                                        data: data,
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        timeout: 600000,
                                        success: function(data) {
                                            console.log(data);
                                        } /* FIM função success */
                                    });

                                    alert("Form successful submitted!");

                                },
                            });
                        </script>
</body>

</html>