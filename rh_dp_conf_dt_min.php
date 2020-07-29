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
$sql = "SELECT 
rh_data_extra_ativo.`data` AS data_min,
rh_data_extra_ativo.ativo AS ativo 
FROM rh_data_extra_ativo ORDER BY id DESC LIMIT 1";
$config = mysqli_fetch_array( mysqli_query($link, $sql) );

if(intval( $config['ativo'] ) == 1 ){
    $status = "Cadastro de novos extras está ativo. Data mínima: " . date('d/m/Y',strtotime($config['data_min']));
}else{
    $status = "Cadastro de novos extras está desativado";
} 

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
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })

            $(".rh_dp").addClass('menu-open');
            $(".rh_dp_admin").addClass('menu-open');
            $("#rh_dp_config_dt_min").addClass('fas fa-circle nav-icon');

            $("#salvar").click( function(){
                var data  = $("#_data").val();

                $.ajax({
                    url: "validar/validar.php?id=22",
                    type: "POST",
                    beforeSend: function(){

                    },
                    data: {data: data},
                    success: function(data){
                        location.reload();
                    }
                });
            });

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
                        <h1 class="m-0 text-dark"><strong>Configurar data mínima para cadastro de extra:</strong>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Configurar data mínima:</strong></li>
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
                        <h3 class="card-title">Configurar data mínima:</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="col-sm-12 my-2">
                        <form id="form" class="form">

                            <!-- area de campos do form -->

                            <div class="row">
                                <div class="form-group col-md-7">
                                    <div class="form-group">
                                        <label>Data mínima para cadastro de extra: <div class="text-danger">(Configuração sem data desativa o cadastro de extras)</div></label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" class="form-control float-right" id="_data">
                                        </div>
                                        <div class="text-danger">(<?php echo$status;?>)</div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="form-group col-md-12  my-3 text-right">
                                    <a class="btn btn-app my-2 text-success elevation-1" id="salvar">
                                        <i class="fas fa-save"></i> Salvar
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>


                    <div class="card-footer text-right text-info" style="display: block;">
                        <small>WISE - SISTEMAS</small>
                    </div>
                    <!-- ./wrapper -->

                    <?php require_once('footer.php'); ?>

                    <script src="plugins/toastr/toastr.min.js"></script>
                    <script>

                    </script>
</body>

</html>