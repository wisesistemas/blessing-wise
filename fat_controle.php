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

$sqlFornecedor = "SELECT * FROM `cad_fornecedor` WHERE ativo = 1";
$sqlFornecedor = mysqli_query($link, $sqlFornecedor);

$sqlCategoria = "SELECT  id, nome FROM plano_contas";
$sqlCategoria = mysqli_query($link, $sqlCategoria);
$id           = base64_encode(4) . '/' . rand();
$idExcluir    = base64_encode(5) . '/' . rand();
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
    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 6000
            });
            /* window.alert = function() {}; */
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })

            $(".faturamento").addClass('menu-open');
            $("#fat_controle").addClass('fas fa-circle nav-icon');

            $("#loading1").hide();

            var dinheiro = document.getElementById("valorPago");
            VMasker(dinheiro).maskMoney({
                precision: 2,
                separator: ',',
                delimiter: '.',
                unit: 'R$',
                zeroCents: false
            });

            function geraTabela() {
                var venciInicio = $("#venciInicio").val();
                var venciFim = $("#venciFim").val();
                var valorMenor = $("#valorMenor").val();
                var valorMaior = $("#valorMaior").val();
                var fornecedor = $("#fornecedor").val();
                var status = $("#status").val();
                var categoria = $("#categoria").val();
                var unidade = $("#unidade").val();
                $("#loading1").show();
                $.ajax({
                    url: "tabelas/tb_nf_controle.php",
                    type: "POST",
                    complete: function() {
                        $("#loading1").hide();
                    },
                    data: {
                        categoria: categoria,
                        venciInicio: venciInicio,
                        venciFim: venciFim,
                        valorMenor: valorMenor,
                        valorMaior: valorMaior,
                        fornecedor: fornecedor,
                        status: status,
                        unidade: unidade
                    },
                    success: function(data) {
                        $("#tabela").html(data);

                    }
                }) //ajax
            }

            $("#gerar").click(function() {
                geraTabela();
            })

            $(document).on('click', '.editar', function() {
                $("#valorPago").val('');
                $("#data").val('');
                $("#arq_pg").val('');
                $("textarea").val('');
                var value = $(this).attr("id").split("*");
                var id = value[0]; /* finalizado_msg */
                var status = value[1];
                if (status == 1 || status == 2) {
                    $(".finalizado").show();
                    $(".finalizado_msg").hide();
                } else {
                    $(".finalizado").hide();
                    $(".finalizado_msg").show();
                }

                $("#id_pg").val(id);
                $.ajax({
                    url: 'modal/md_contorle_contas_receber.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    beforeSend: function() {
                        $("#loading1").show();
                    },
                    complete: function() {
                        $("#loading1").hide();
                        $("#exampleModal").modal('show');
                    },
                    success: function(data) {
                        $("#copor").html(data);
                    }
                });

            });

            $("#excluir_nf_modal").click(function() {
                $("#moda-excluir").modal("show");
            });

            $("#excluir_nf").click(function() {
                var id = $("#id_pg").val();
                var desc = $("#desc_excluir").val();
                $("#msg-desc").hide();
                if (desc.length > 5) {
                    $("#moda-excluir").modal("hide");
                    $("#exampleModal").modal('hide');
                    $.ajax({
                        url: "validar/validar.php?id=40",
                        type: "POST",
                        beforeSend: function() {

                        },
                        complete: function() {

                        },
                        data: {
                            id: id, desc: desc
                        },
                        success: function(data) {
                            console.log(data);
                            $("#desc_excluir").val('')
                            geraTabela();
                        }
                    });
                } else {
                    $("#msg-desc").show();
                }
            });

            $("#form_pg").validate({
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

                    var form = $('#form_pg')[0];
                    var data = new FormData(form);
                    $("#exampleModal").modal('hide');
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "validar/validar.php?id=39",
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
                            geraTabela();
                        } /* FIM função success  */
                    });

                },
            });

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
                            <h1 class="m-0 text-dark">Relatório de Fataramento</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><strong>Relatório de Fataramento</strong></li>
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
                            <h3 class="card-title">Relatório de Fataramento:</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="col-12">
                            <form style="display: show">
                                <div class="row my-2">

                                    <div class="form-group col-md-3 " style="display: none">
                                        <label for="venciInicio">VENCIMENTO INICIAL</label>
                                        <input type="date" class="form-control form-control-sm" id="venciInicio" name="venciInicio">
                                    </div>
                                    <div class="form-group col-md-3" style="display: none">
                                        <label for="venciFim">VENCIMENTO FINAL</label>
                                        <input type="date" class="form-control form-control-sm" id="venciFim" name="venciFim">
                                    </div>
                                    <div class="form-group col-md-3"  style="display: none">
                                        <label for="valorMenor">VALOR MENOR</label>
                                        <input type="number" class="form-control form-control-sm" id="valorMenor" name="valorMenor">
                                    </div>
                                    <div class="form-group col-md-3" style="display: none">
                                        <label for="valorMaior">VALOR MAIOR</label>
                                        <input type="number" class="form-control form-control-sm" id="valorMaior" name="valorMaior">
                                    </div>
                                    <div class="form-group col-md-6" style="display: none">
                                        <label for="funcao_extra">FORNECEDOR</label>
                                        <select class="form-control form-control-sm chosen-select" name="fornecedor[]" id="fornecedor" required multiple>
                                            <option value="">...</option>
                                            <?php
                                            while ($forncedor = mysqli_fetch_array($sqlFornecedor)) {
                                            ?>
                                                <option value="<?php echo $forncedor['id']; ?>"><?php echo $forncedor['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="status">UNIDADE</label>
                                        <select class="form-control form-control-sm chosen-select" name="unidade[]" id="unidade" required multiple>
                                        <?php
                                            $sqlUnidade = mysqli_query($link, "SELECT * FROM `cad_unidade` WHERE ativo = 1");
                                            while ($unidade = mysqli_fetch_array($sqlUnidade)) {
                                            ?>
                                                <option value="<?php echo $unidade['id']; ?>"><?php echo $unidade['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="status">STATUS</label>
                                        <select class="form-control form-control-sm chosen-select" name="status[]" id="status" required multiple>
                                            <option value="1">CADASTADO</option>
                                            <option value="4">EXCLUIDO</option>
                                            <option value="2">EM ANDAMENTO</option>
                                            <option value="3">FINALIZADO</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="display: none">
                                        <label for="categoria">CATEGORIA</label>
                                        <select class="form-control form-control-sm chosen-select" name="categoria" id="categoria" required>
                                            <option value="null">Todas</option>
                                            <?php
                                            while ($categoria = mysqli_fetch_array($sqlCategoria)) {
                                            ?>
                                                <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="form-group col-md-6" style="display: none">
                                        <label for="total">TOTAL</label>
                                        <input type="text" class="form-control form-control-sm" id="total" readonly>
                                    </div>
                                    <div class="form-group col-md-6" style="display: none">
                                        <label for="totalRegistro">TOTAL DE PARCELAS</label>
                                        <input type="text" class="form-control form-control-sm" id="totalRegistro" readonly>
                                    </div>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary btn-lg btn-block btn-sm" id="gerar">Gerar</button>
                        </div>
                        <div>
                            <div class="container my-3 col-12 ">
                                <div id="tabela" class="table-responsive"></div>

                            </div>
                        </div>
                        <!-- ./wrapper -->

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">WISE - SISTEMAS</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form_pg">
                                            <div id="copor"></div>
                                            <div class="callout callout-danger finalizado">
                                                <h5>Novo Registro de Pagamento</h5>
                                                <div class="row ">
                                                    <div class="form-group col-md-6">
                                                        <label for="valor_pg">Valor:</label>
                                                        <input type="text" class="form-control form-control-sm" name="valorPago" id="valorPago" value="" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="dt_pg">Data:</label>
                                                        <input type="date" class="form-control form-control-sm" name="data" id="data" value="" required>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="arq_pg">Anexar Comprovante: </label>
                                                        <input type="file" class="form-control-file" id="arq_pg" name="arq_pg" accept="image/jpg, image/jpeg, application/pdf">
                                                        <small class="text-danger">(.PDF .JPG .JPEG)*</small>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="desc_pg">Obs:</label>
                                                        <textarea class="form-control form-control-sm" id="obs" name="obs"></textarea>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <!-- radio -->
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio" id="tipoPagamento1" name="tipoPagamento" value="0" checked="">
                                                                <label for="tipoPagamento1" class="custom-control-label">Pagamento Parcial</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio" id="tipoPagamento2" value="1" name="tipoPagamento">
                                                                <label for="tipoPagamento2" class="custom-control-label">Pagamento Final</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="callout callout-danger finalizado_msg">
                                                Registros de pagamento finalizado.
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="container finalizado">
                                            <div class="row">
                                                <div class="col text-right">
                                                    <button type="button" id="excluir_nf_modal" class="btn btn-app text-danger"><i class="fas fa-trash-alt"></i>Excluir</button>
                                                    <button type="button" class="btn btn-app" data-dismiss="modal"><i class="fas fa-times"></i>Fechar</button>
                                                    <button type="submit" class="btn btn-app text-success"><i class="fas fa-stamp"></i>Baixa</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" id="id_pg" value="">
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!--  -->
                        <!-- Modal -->
                        <div class="modal fade " id="moda-excluir" tabindex="-1" role="dialog" aria-labelledby="moda-excluirLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="moda-excluirLabel">WISE - SISTEMAS</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="desc_excluir">Descrição do Motivo:</label>
                                            <textarea class="form-control" id="desc_excluir" rows="3" placeholder="Quantidade Caracteres Igual ou Maior que 5"></textarea>
                                            <small class="text-danger">Campo Obrigatório!*.</small>
                                            <br>
                                            <small class="text-danger" id="msg-desc" style="display: none;">Quantidade de Caracteres Insuficiente!*</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        <button type="button" class="btn btn-primary" id="excluir_nf">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php require_once('footer.php'); ?>
                        <!-- jquery-validation -->
                        <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
                        <script src="plugins/jquery-validation/additional-methods.min.js"></script>
                        <!-- Select2 -->
                        <script src="plugins/select2/js/select2.full.min.js"></script>
                        <!-- Toastr -->
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                        <script src="plugins/toastr/toastr.min.js"></script>
                        <script src="https://rawgit.com/BankFacil/vanilla-masker/master/lib/vanilla-masker.js"></script>

                        <script>
                            $('.chosen-select').select2()

                            //Initialize Select2 Elements
                            $('.select2bs4').select2({
                                theme: 'bootstrap4'
                            })
                        </script>


</body>

</html>