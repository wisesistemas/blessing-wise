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

$sqlCompetencia = "SELECT * FROM `custo_competencia`  where ativo = 1 ORDER BY id";
$sqlCompetencia = mysqli_query($link, $sqlCompetencia);

$sqlUnidade = "SELECT * FROM `cad_unidade` where ativo = 1";
$sqlUnidade = mysqli_query($link, $sqlUnidade);

$sqlEmpresa = "SELECT * FROM cad_empresa";
$sqlEmpresa = mysqli_query($link, $sqlEmpresa);

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
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- Mask -->
    <script src="plugins/mask/jQuery-Mask-Plugin-master/dist/jquery.mask.js"></script>
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

            $("#cnpj").mask("00.000.000/0000-00");

            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })

            $(".faturamento").addClass('menu-open');
            $("#fat_registro").addClass('fas fa-circle nav-icon');

            var dinheiro = document.getElementsByClassName("valor");
            VMasker(dinheiro).maskMoney({
                precision: 2,
                separator: ',',
                delimiter: '.',
                unit: 'R$',
                zeroCents: false
            });

            $("#loading1").hide();

            $('.auto').keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            });

            $("#form").validate({
                rules: {
                    inicio: {
                        required: true,
                    }
                }, //RULES
                messages: {
                    inicio: {
                        required: "Requerido...",
                    }
                }, //MESSAGES
                submitHandler: function(form) {
                    var form = $('#form')[0];
                    var data = new FormData(form);
                    $("#loading1").show();
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "validar/validar.php?id=38",
                        beforeSend: function() {
                            $("#sucesso").hide();
                            $("#erro").hide();
                            $("#form").hide();
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
                            console.log(data);
                        }
                    });
                } //HANDLER
            });

            $("#novo").click(function() {
                location.reload();
            })

            $("#adicionar").click(function() {
                AddTableRow();
            });

            AddTableRow = function() {

                var newRow = $("<tr>");
                var cols = '';
                cols += '<th> <input class="form-control form-control-sm  auto valid" type="date" name="inicio[]" required="" aria-invalid="false"> </th>';
                cols += '<td> <input class="form-control form-control-sm  auto valid" type="date" name="final[]"  required> </td>';
                cols += '<td> <input class="form-control form-control-sm  nf auto valid" type="text" name="nf[]" required> </td>';
                cols += '<td> <input class="form-control form-control-sm  somar auto valid" type="number" value="0.00" min="0" name="valor[]"  required> </td>';
                cols += '<td> <input type="file" class="form-control-file my-1" id="anexo" name="files[]"> </td>';
                cols += '<td> <button class="btn  btn-sm remover text-danger" onclick="RemoveTableRow(this)" type="button"><i class="fas fa-trash-alt" style="font-size: 18px"></i></button> </td>';
                cols += '</tr>';
                newRow.append(cols);
                $("#table").append(newRow);



                return false;
            };

            RemoveTableRow = function(handler) {
                $("#total").val('');
                var tr = $(handler).closest('tr');

                tr.fadeOut(400, function() {

                    tr.remove();
                });

                return false;
            };

            $(document).on('keyup', '.somar', function() {
                var total = 0.00;
                var total = 0.00;
                var totalDeCompos = $(".somar").length;

                for (i = 0; i < totalDeCompos; i++) {
                    var valor = parseFloat($('.somar')[i].value);
                    var total = total + valor;
                }
                $("#total").val(total.toFixed(2).replace('.', ','));
            })

            $(document).on('keyup', '.nf', function() {
                var nf = $(this).val();

                $.ajax({
                    url: 'validar/validar_chamado.php?res=',
                    type: 'POST',
                    data: {
                        nf: nf
                    },
                    success: function(data) {
                        console.log(data);

                        if (data == 1) {
                            $(".env").hide();
                            $("#nfCadastrada").show();
                        } else {
                            $(".env").show();
                            $("#nfCadastrada").hide();
                        }
                    }
                }); //ajax
            })


            $("#empresa").change(function() {
                var value = $(this).val().split("*");
                $("#cnpj").val(value[0]);
            });
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
                            <h1 class="m-0 text-dark">Cadastro de Faturamento:</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Cadastro de Faturamento:</strong></li>
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
                            <h3 class="card-title">Cadastro de Faturamento:</h3>
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
                                        <label for="funcao_extra">Competencia</label>
                                        <select class="form-control form-control-sm chosen-select" name="competencia" required>
                                            <option value="">...</option>
                                            <?php
                                            while ($competencia = mysqli_fetch_array($sqlCompetencia)) {
                                            ?>
                                                <option value="<?php echo $competencia['id']; ?>"><?php echo $competencia['mes'] . '/' . $competencia['ano'];  ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="funcao_extra">R$: TOTAL </label>
                                        <input type="text" class="form-control form-control-sm" name="total" id="total" value="" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="empresa">Empresa</label>
                                        <select class="form-control form-control-sm chosen-select" name="empresa" id="empresa">
                                            <option value="">...</option>
                                            <?php while ($empresa = mysqli_fetch_array($sqlEmpresa)) { ?>
                                                <option value="<?php echo $empresa['cnpj'] . '*' . $empresa['empresa']; ?>"><?php echo $empresa['empresa']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="funcao_extra">CNPJ</label>
                                        <input type="text" class="form-control form-control-sm" name="cnpj" id="cnpj" value="" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="funcao_extra">Unidade</label>
                                        <select class="form-control form-control-sm chosen-select" name="unidade">
                                            <option value="">...</option>
                                            <?php while ($unidade = mysqli_fetch_array($sqlUnidade)) { ?>
                                                <option value="<?php echo $unidade['id'].'*'.$unidade['oss']; ?>"><?php echo $unidade['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="qtd_exames">QTD Exames</label>
                                        <input type="number" class="form-control form-control-sm" name="qtd_exames" id="qtd_exames" value="">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="dt_envio">Data de Envio</label>
                                        <input type="date" class="form-control form-control-sm" name="dt_envio" id="dt_envio" value="">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="funcao_extra">Descrição</label>
                                        <textarea class="form-control form-control-sm" name="desc"></textarea>
                                    </div>

                                    <div class=" table-responsive">

                                        <table class="table table-sm" id="table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>DT INICIO</th>
                                                    <th>DT FINAL</th>
                                                    <th>NF</th>
                                                    <th>VALOR</th>
                                                    <th>ANEXO</th>
                                                    <th>REMOVER</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-center">
                                                    <th><input class="form-control form-control-sm auto" type="date" name="inicio[]" value="" required></th>
                                                    <td><input class="form-control form-control-sm auto" type="date" name="final[]" value="" required></td>
                                                    <td><input class="form-control form-control-sm nf auto" type="text" name="nf[]" value="" required></td>
                                                    <td><input class="form-control form-control-sm somar auto  " type="number" min="0" name="valor[]" value="0.00" required></td>
                                                    <td><input type="file" class="form-control-file my-1" id="anexo" name="files[]"></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" class="bbtn btn-app remover my-3 float-left env text-info" id="adicionar">
                                            <i class="fas fa-plus"></i>Adicionar NF
                                        </button>
                                    </div>

                                    <div class="alert alert-danger text-center col-12" id="nfCadastrada" role="alert" style="display: none">
                                        Sistema: <strong>NF já cadastrada!</strong>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-app  env text-success "><i class="fas fa-save"></i>Salvar</button>
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
                    <!-- Mask -->
                    <script src="plugins/mask/jQuery-Mask-Plugin-master/dist/jquery.mask.js"></script>
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
                        $('.chosen-select').select2({

                        })
                    </script>
</body>

</html>