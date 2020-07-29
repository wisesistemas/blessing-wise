<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['usuario_id'])  && !isset($_SESSION['usuario_id_unidade'])) {
    header('Location: index.php?erro=1');
}
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();

require_once('validar/class/class.financeiro.php');
$objFinanceiro = new Financeiro();
$competenciaAtual = $objFinanceiro->retornaCompetenciaAtual();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));

$_SESSION['token'] = $token;

$sqlCompetencia = "SELECT * FROM `custo_competencia`  where ativo = 1 ORDER BY id";
$sqlCompetencia = mysqli_query($link, $sqlCompetencia);

$sqlFornecedor = "SELECT * FROM `cad_fornecedor` WHERE ativo = 1";
$sqlFornecedor = mysqli_query($link, $sqlFornecedor);

$sqlCategoria = "SELECT * FROM `plano_contas` WHERE ativo = 1";
$sqlCategoria = mysqli_query($link, $sqlCategoria);

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
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://rawgit.com/BankFacil/vanilla-masker/master/lib/vanilla-masker.js"></script>
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

            $(".financeiro").addClass('menu-open');
            $("#fin_conta_fixa").addClass('fas fa-circle nav-icon');

            var dinheiro = document.getElementsByClassName("valor");
            VMasker(dinheiro).maskMoney({
                precision: 2,
                separator: ',',
                delimiter: '.',
                unit: 'R$',
                zeroCents: false
            });


            $("#form").validate({
                rules: {
                    vencimento: {
                        required: true,
                        max: 31,
                        min: 0,
                    }
                }, //RULES
                messages: {
                    vencimento: {
                        required: "Requerido...",
                        max: "Menor que 31",
                        mim: "Maior que 0",
                    }
                }, //MESSAGES
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

                    var form = $('#form')[0];
                    var data = new FormData(form);
                    $("#loading1").show();
                    $("#form").hide();
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "validar/validar.php?id=33",
                        beforeSend: function() {

                        },
                        complete: function() {
                            $("#loading1").hide();
                        },
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        success: function(data) {
                            $("#novo").show();

                        } /* FIM função success */
                    });

                },
            });

            $("#novo").click(function() {
                location.reload();
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
                            <h1 class="m-0 text-dark">Cadastro de Conta Fixa</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Cadastro de Conta Fixa</strong></li>
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
                            <h3 class="card-title">Cadastro de Conta Fixa:</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <!-- CORPO -->
                        <div class="container">
                            <div class="col-md-12 text-right" style="display: none;" id="novo">
                                <a class="btn btn-app my-1">
                                    <i class="fas fa-plus"></i> Novo
                                </a>
                            </div>
                            <form id="form">

                                <!-- area de campos do form -->
                                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] = base64_encode(rand()) . '/' . md5(rand()); ?>">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="competencia">Competencia a Partir</label>
                                        <select class="select2 col-md-12" name="competencia" id="competencia" required>
                                            <option value="<?php echo$competenciaAtual;?>">Do mês atual</option>
                                            <?php
                                            while ($competencia = mysqli_fetch_array($sqlCompetencia)) {
                                            ?>
                                                <option value="<?php echo $competencia['id']; ?>"><?php echo $competencia['mes'] . '/' . $competencia['ano'];  ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="num_doc">Nº Doc</label>
                                        <input type="text" class="form-control form-control-sm" id="num_doc" value="" name="num_doc">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="arq_pg">Anexar: </label>
                                        <input type="file" class="form-control-file" id="arq_pg" name="arq_pg" accept="image/jpg, image/jpeg, application/pdf">
                                        <small class="text-danger">(.PDF .JPG .JPEG)*</small>
                                    </div>

                                    <div class="form-group col-md-11">
                                        <label for="fornecedor">Fornecedor</label>
                                        <select class="select2 col-md-12" name="fornecedor" id="fornecedor" required>
                                            <option value="">...</option>
                                            <?php
                                            while ($forncedor = mysqli_fetch_array($sqlFornecedor)) {
                                            ?>
                                                <option value="<?php echo $forncedor['id']; ?>"><?php echo $forncedor['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-11">
                                        <label for="categoria">Categoria</label>
                                        <select class="select2 col-md-12" name="categoria" required>
                                            <option value="">...</option>
                                            <?php
                                            while ($categoria = mysqli_fetch_array($sqlCategoria)) {
                                            ?>
                                                <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="detalhe_desp">Detalhe da despesa </label>
                                        <textarea type="text" class="form-control" id="detalhe_desp" value="" name="detalhe_desp"></textarea>
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label for="vencimento">Dia do vencimento esperado</label>
                                        <input type="number" class="form-control form-control-sm" id="vencimento" name="vencimento" max="31" min="1" value="">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="valor">Valor Total</label>
                                        <input type="text" class="form-control form-control-sm valor" id="valor" name="valor" value="">
                                    </div>
                                </div>

                                <div class="col-md-12 text-right ">
                                    <button type="submit" class="btn btn-app my-1 text-success">
                                        <i class="fas fa-plus"></i>
                                        Salvar
                                    </button>
                                </div>

                            </form>
                        </div>
                        <!-- CORPO -->
                        <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
                    </div>
                    <!-- ./wrapper -->

                    <?php require_once('footer.php'); ?>
                    <!-- jquery-validation -->
                    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
                    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
                    <!-- Select2 -->
                    <script src="plugins/select2/js/select2.full.min.js"></script>
                    <!-- Toastr -->
                    <script src="plugins/toastr/toastr.min.js"></script>
                    <script src="https://rawgit.com/BankFacil/vanilla-masker/master/lib/vanilla-masker.js"></script>
                    <script>
                        $('.select2').select2()

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })
                    </script>
</body>

</html>