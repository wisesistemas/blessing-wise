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
unset($_SESSION['nf_array_item']);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WISE - SISTEMAS</title>
    <?php require_once('head.php'); ?>
    <!-- Select2 -->
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
    <!-- teste -->
    <link rel="stylesheet" href="plugins/autocomplete/autocomplete.css" type="text/css">
    <script src="plugins/autocomplete/autocomplete.js"></script>
    <!--  -->
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
    <!-- WISE - JS -->
    <script src="plugins/wise_js/cpr_cad_manual.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    
   <script>
    $(document).ready(function() {
        $(".menu_compras").addClass('menu-open');
        $("#cad_nfe_manual").addClass('fas fa-circle nav-icon'); 
        $(".real").maskMoney({
            prefix: "R$:",
            decimal: ",",
            thousands: "."
        });
      
        $("#destinatario_nome").change(function() {
            var destinatario = $(this).val();
            $("#destinatario_cnpj").val(destinatario).focus();
        })

        $("#emitente_cnpj").keyup(function(e) {
            var tecla = e.keyCode ? e.keyCode : e.which;
            if (tecla == 13) {
                var cnpj = $(this).val();
                $.ajax({
                    url: "validar/validar.php?id=11",
                    type: "POST",
                    data: {
                        cnpj: cnpj
                    },
                    success: function(data) {

                        $(function() {
                            var obj = JSON.parse(data)
                            console.log(obj);
                            $("#emitente_nome").val(obj.nome);
                            $("#emitente_escricao_estadual").val(obj.es);
                            $("#emitente_uf").val(obj.uf);
                        });
                    },
                });
            }
        })

        $("#emitente_nome").keyup(function(e) {
            var tecla = e.keyCode ? e.keyCode : e.which;
            if (tecla == 13) {
                var nome = $(this).val();
                $.ajax({
                    url: "validar/validar.php?id=12",
                    type: "POST",
                    data: {
                        nome: nome
                    },
                    success: function(data) {
                        $(function() {
                            var obj = JSON.parse(data)
                            console.log(obj);
                            $("#emitente_cnpj").val(obj.cnpj);
                            $("#emitente_escricao_estadual").val(obj.es);
                            $("#emitente_uf").val(obj.uf);
                        });
                    },
                });
            }
        })
       
        $("#novocad").click(function(){
            location.reload(true);
        });
    })
    </script>
    <style>
        .box {
            width: 100%;
            border: 1px solid #c7a460;
            background-color: #f4edd5;
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
        }

        .box label {
            display: block;
            text-transform: none;
            padding: 4px 0px 0px 6px;
            font-family: Trebuchet MS, Arial, Verdana, Helvetica, sans-serif;
            font-size: 12px;
            min-height: 15px;
            font-weight: normal;
            color: #6f5e39;
            text-align: left;
            padding: 1px;
            margin-bottom: 1px;
        }

        .box input {
            height: auto !important;
            height: 15px;
            min-height: 15px;
            display: block;
            background-position: center;
            border: solid 1px #d6c39e;
            background-color: #fbfbf5;
            padding: 1px 1px 1px 1px;
        }

        .text1 {
            height: auto !important;
            height: 15px;
            min-height: 15px;
            display: block;
            background-position: center;
            border: solid 1px #d6c39e;
            background-color: #fbfbf5;
            padding: 1px 1px 1px 1px;
        }

        .legend {
            text-align: left;
            border: none;
            font-size: 14px;
            height: 15px;
            font-weight: bold;
            width: 98%;
            display: block;
            color: #b27235;
            background: none;
            border: none;
            /* text-transform: capitalize; */
            font-family: Trebuchet MS, Arial, Verdana, Helvetica, sans-serif;
        }

        .form-group {
            margin-bottom: 3px;
        }

        .GeralXslt table {
            /* width: 100%; */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            /* padding-top: 5px; */
        }

        .toggle {
            background: url(../imagens/ico_mais.gif) no-repeat 8px 0px;
            background-color: #ece8df;
            cursor: pointer;
            background-position: 6px 6px;
            padding-left: 16px;
            text-indent: 0px;
            border: solid 1px rgb(236, 236, 236);
        }


        .table tr:nth-child(odd) {
            background-color: #fff;
        }

        .table tr:nth-child(even) {
            background-color: #ccc;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
   
    <div class="overlay-wrapper">
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Carregando dados...</div>
        </div> 
    </div> 

    <?php require_once('menu_superior.php'); ?>
    <?php require_once('menu_lataral.php'); ?>


    <!-- body -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Cadastro de NF-e manual</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Cadastro de NF-e</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card collapsed-card" id="cod_nfe">
                <div class="card-header">
                    <div class="col-12" id="input_nf_hide">
                        <div class="form-group" >
                            <label for="cod_nfe_input">Código NF-e:</label>
                            <input type="text"  class="form-control form-control-sm" name="cod_nfe_input" id="cod_nfe_input"
                                placeholder="Código NF-e">
                        </div>
                    </div>
                    <div class="col-12" id="novocad" style="display: none;">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary col-12" >Novo Cadastro</button>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="
    padding-left: 0px;
    padding-top: 0px;
    padding-right: 0px;
">
                    <!-- body card -->
                    <form role="form" id="fileUploadForm" enctype="multipart/form-data" method="post" >
                        <fieldset class="border m-2 " style="background: #FAFAFA;">
                            <legend class="w-auto">Dados da NF-e</legend>
                            <div class="card-body"  style="
    padding-left: 2px;
    padding-right: 2px;
    padding-top: 0px;"
 >
                                <legend class="w-auto legend">Dados Gerais</legend>
                                <div class="form-row box ">
                                    <div class="form-group col-2">
                                        <label for="modelo">Modelo:</label>
                                        <input type="text" name="modelo" class="form-control pula" id="modelo"
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="serie">Série:</label>
                                        <input type="text" name="serie" class="form-control pula" id="serie"
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="numero">Número:</label>
                                        <input type="text" name="numero" class="form-control pula" id="numero"
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="dt_hr_emissao">Data Emissão:</label>
                                        <input type="date" name="dt_hr_emissao"
                                            class="form-control form-control-sm pula" id="dt_hr_emissao" placeholder="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="dt_hr_entrada">Data Entrada:</label>
                                        <input type="date" name="dt_hr_entrada"
                                            class="form-control form-control-sm pula" id="dt_hr_entrada" placeholder="">
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="valor_total">Valor Total da Nota Fiscal:</label>
                                        <input type="text" name="valor_total" class="form-control real pula" id="valor_total"
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="chave">Chave de Acesso:</label>
                                        <input type="text" name="chave" class="form-control pula" id="chave"
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="v_xml">Versão XML:</label>
                                        <input type="text" name="v_xml" class="form-control pula" id="v_xml" placeholder="">
                                    </div>
                                </div>
                                <legend class="w-auto legend">Destinatário</legend>
                                <div class="form-row box ">
                                    <div class="form-group col-6">
                                        <label for="destinatario_nome" style="
    padding-bottom: 0px;
    margin-bottom: 0px;
">Nome / Razão Social:</label>
                                        <select name="destinatario_nome" class="form-control form-control-sm pula"
                                            id="destinatario_nome">
                                            <option selected="selected" value="">...</option>
                                            <?php
                                            $sql = "SELECT * FROM cad_empresa";
                                            $sql = mysqli_query($link, $sql);
                                            while ($sqlItem = mysqli_fetch_array($sql)) {
                                            ?>
                                            <option value="<?php echo $sqlItem['cnpj'] ?>">
                                                <small><?php echo $sqlItem['empresa'] ?></small>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="destinatario_cnpj">CNPJ:</label>
                                        <input type="text" name="destinatario_cnpj" class="form-control pula"
                                            id="destinatario_cnpj" placeholder="">
                                    </div>
                                </div>
                                <legend class="w-auto legend">Emitente</legend>
                                <div class="form-row box ">
                                    <div class="form-group col-6">
                                        <label for="emitente_cnpj">CNPJ:</label>
                                        <input type="text" name="emitente_cnpj" class="form-control teste pula"
                                            id="emitente_cnpj" placeholder="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="emitente_nome">Nome / Razão Social:</label>
                                        <input type="text" name="emitente_nome" class="form-control " id="emitente_nome"
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="emitente_escricao_estadual">Inscrição Estadual:</label>
                                        <input type="number" name="emitente_escricao_estadual" class="form-control "
                                            id="emitente_escricao_estadual" placeholder="">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="emitente_uf" style="
    padding-bottom: 0px;
    margin-bottom: 0px;
">UF:</label>
                                        <select name="emitente_uf" class="form-control form-control-sm pula"
                                            id="emitente_uf">
                                            <option selected="selected" value="">...</option>
                                            <option value="RJ">RJ</option>
                                            <option value="SP">SP</option>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <!-- cadastro de pdf -->
                                <legend class="w-auto legend">Enexar Arquivo</legend>
                                <div class="form-row box ">
                                    <div class="form-group col-12">
                                        <label for="exampleFormControlFile1">Enviar arquivo</label>
                                        <input type="file" class="form-control-file" id="exampleFormControlFile1" name="exampleFormControlFile1">
                                    </div>
                                </div>
                                <!-- Cadastro de item -->
                                <legend class="w-auto legend">Casdastro de item</legend>
                                <div class="form-row box ">
                                    <div class="form-group col-2">
                                        <label for="item_forn_cod">COD do item Fornecedor:*</label>
                                        <input type="number" name="item_forn_cod" class="form-control "
                                            id="item_forn_cod" placeholder="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="item_forn_nome">Descrição do fornecedor:*</label>
                                        <input type="text" name="item_forn_nome" class="form-control "
                                            id="item_forn_nome" placeholder="">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="item_qtd">Quantidade:*</label>
                                        <input type="number" name="item_qtd" class="form-control " id="item_qtd"
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="item_forn_uni" style="
    padding-bottom: 0px;
    margin-bottom: 0px;
">Unidade Comercial:*</label>
                                        <select name="item_forn_uni" class="form-control form-control-sm pula"
                                            id="item_forn_uni">
                                            <option selected="selected" value="">...</option>
                                            <option value="UN">UN - Unidade</option>
                                            <option value="CT">CT - Cartela</option>
                                            <option value="CX">CX - Caixa</option>
                                            <option value="DZ">DZ - Duzia</option>
                                            <option value="GS">GS - Grosa</option>
                                            <option value="PÇ">PÇ - Peça</option>
                                            <option value="PT">PT - Pacote</option>
                                            <option value="RL">RL - Rolo</option>
                                            <option value="kg">kg - Kilograma</option>
                                            <option value="g">g - Grama</option>
                                            <option value="l">l - Litro</option>
                                            <option value="X">X - Não definida</option>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-8">
                                        <label for="destinatario_nome_cnpj" style="
    padding-bottom: 0px;
    margin-bottom: 0px;
">Item Wise:*</label>
                                        <select class="form-control form-control-sm  select2 " id="item_wise"
                                            name="item_wise">
                                            <option selected="selected" value="">...</option>
                                            <?php
                                            $sql = "SELECT 
                                                id, 
                                                nome, 
                                                IF(LENGTH(cad_item.referencia) > 0,CONCAT('- ',cad_item.referencia), '') AS referencia,
                                                IF(LENGTH(cad_item.marca) > 0,CONCAT('- ',cad_item.marca), '') AS marca
                                                FROM cad_item";
                                            $sql = mysqli_query($link, $sql);
                                            while ($sqlItem = mysqli_fetch_array($sql)) {
                                            ?>
                                            <option
                                                value="<?php echo $sqlItem['id'] . '*' . $sqlItem['nome'] . ' ' . $sqlItem['referencia'] . ' ' . $sqlItem['marca'] ?>">
                                                <small><?php echo $sqlItem['nome'] . ' ' . $sqlItem['referencia'] . ' ' . $sqlItem['marca'] ?></small>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="valor_unit">Valor unitário:*</label>
                                        <input type="text" name="valor_unit" class="form-control real" id="valor_unit"
                                            placeholder="">
                                    </div>

                                    <div class="form-group col-2 my-3">

                                        <button type="button" class="btn btn-xs btn-primary col-12 my-1"
                                            id="inserir"><i class="fas fa-arrow-down"></i> Inserir</button>
                                    </div>
                                </div>
                                <!-- tabela -->
                                <div id="tabela"></div>

                            </div>


                        </fieldset>


                  
                    <!-- fim body card -->
                </div>
                
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button  class="btn btn-app" id="btnSubmit"><i class="fas fa-save" style="color: green; font-size: 24px"></i>Salvar</button>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
            </form>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- fim body -->

    <?php require_once('footer.php'); ?>
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
    <script src="plugins/mask/jquery.mask.js"></script>
    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- teste -->
    <link rel="stylesheet" href="plugins/autocomplete/autocomplete.css" type="text/css">
    <script src="plugins/autocomplete/autocomplete.js"></script>
    <!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

    <script>
    $('.select2').select2({
        placeholder: 'Selecione o item...'
    })

    $('.select2').css('height', 'calc(28px + 0px');
    </script>

</body>

</html>