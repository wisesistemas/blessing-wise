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
$_SESSION['token_cad_extra'] = $token;

//--Query Funcionario
$sql = "SELECT * from cad_funcionario where situacao = 1 ORDER BY `nome`";
$query_funcionario = mysqli_query($link, $sql);

//--Query Funcionario substituído
$sql = "SELECT * from cad_funcionario where situacao = 1 ORDER BY `nome`";
$query_substituido = mysqli_query($link, $sql);

//--Query Função do Extra
$sql = "SELECT a.id_funcao as 'id_funcao', b.funcao as 'funcao' from valor_extras a 
INNER JOIN cad_funcao b 
where a.id_funcao = b.id AND b.id in(1,20,33,37,38,40,44,57)";
$query_funcaoextra = mysqli_query($link, $sql);

//--Query Unidade do Extra
if(  1 == intval($_SESSION['usuario_admin_master']) ){
    $sql1 = "SELECT *
    FROM cad_unidade where ativo = 1";
  }else{
    $sql1 = "SELECT c.nome as nome, c.id as id, c.central as central
    FROM usuario_unidade r 
    INNER JOIN usuarios u 
    ON r.usuario = u.id
    INNER JOIN cad_unidade c 
    ON r.unidade = c.id
    where r.ativo = 1 && u.id = $_SESSION[usuario_id]";
  }
$query_unidadeextra = mysqli_query($link, $sql1);

//--Query Escala do Extra
$sql = "SELECT id, escala, tipo from cad_escala";
$query_escalaoextra = mysqli_query($link, $sql);

//Configuração de extra ativo e data min
$sql = "SELECT 
rh_data_extra_ativo.`data` AS data_min,
rh_data_extra_ativo.ativo AS ativo 
FROM rh_data_extra_ativo ORDER BY id DESC LIMIT 1";
$config = mysqli_fetch_array( mysqli_query($link, $sql) );
if(intval( $config['ativo'] ) == 1 ){
    $status = "Cadastro de novos extras está ativo. Data minima: " . date('d/m/Y',strtotime($config['data_min']));
    $extraLiberado = 1;
}else{
    $status = "Cadastro de novos extras está desativado";
    $extraLiberado = 0;
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
        $("#loading1").hide();
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(".rh_dp").addClass('menu-open');
        $("#rh_dp_cad_extra").addClass('fas fa-circle nav-icon');
       
        $('#form').validate({
                rules: {
                    nome: {
                        required: true,
                    },
                    funcao_extra: {
                        required: true,
                    },
                    unidade_extra: {
                        required: true,
                    },
                    escala_extra: {
                        required: true,
                    },
                    data_extra: {
                        required: true,
                        max: '<?php echo date('Y-m-d');?>',
                        min: '<?php echo$config['data_min'];?>'
                    },
                    hora_ent: {
                        required: true,
                    },
                    hora_sai: {
                        required: true,
                    },
                    motivo_extra: {
                        required: true,
                    },
                    
                },
                messages: {
                    nome: {
                        required: "Obrigatório",
                    },
                    funcao_extra: {
                        required: "Obrigatório",
                    },
                    unidade_extra: {
                        required: "Obrigatório",
                    },
                    escala_extra: {
                        required: "Obrigatório",
                    },
                    data_extra: {
                        required: "Obrigatório",
                        max: "Data maior que permitido.",
                        min: "Data menor que permitido."
                    },
                    hora_ent: {
                        required: "Obrigatório",
                    },
                    hora_sai: {
                        required: "Obrigatório",
                    },
                    motivo_extra: {
                        required: "Obrigatório",
                    },
                    substituido: {
                        required: "Obrigatório",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    var form = $(form).serialize();
                    $("#negar").addClass("disabled");
                    $("#_enviar").hide();
                    $("#_salvando").show();
                    $.ajax({
                        url: "validar/validar.php?id=20",
                        type: "POST",
                        beforeSend: function(){
                            limpaForm();
                        },
                        data: form,
                        success: function(data){
                            console.log(data);
                            $("#form").hide();
                            $(".novo_extra").show();
                            Toast.fire({
                                icon: 'success',
                                title: 'Extra cadastrado com sucesso!'
                            })
                          
                        }
                    })
                }
            });

        
            $("#negar").click( function(){
                limpaForm();
            })
        
            function limpaForm(){
                $('select').val("").change();
                $('textarea').val("");
                $('#data_extra').val("").change();
                $('#hora_ent').val("").change();
                $('#hora_sai').val("").change();
                $('time').val("");
            }
        
        $('#motivo_extra').on('change', function(){
            var selectValor = $(this).val();
            if(selectValor == 'aumento quadro' || selectValor == 'Outros'){

                $('#substituido').css({'display': 'none'});
                 $("#1substituido").removeAttr('required');
                 

              }else{
                   
              $("#1substituido").attr("required", "");
             
                $('#substituido').css({'display': ''});
             
            }
          

          });

          $("#_novo_extra").click( function(){
            _cadastroComSucesso();
          })
          _cadastroComSucesso();
            function _cadastroComSucesso(){
                limpaForm();
                $("#form").show();
                $(".novo_extra").hide();
                $("#negar").removeClass("disabled");
                $("#_enviar").show();
                $("#_salvando").hide();
            }
       
           
            window.alert = function() {};
    });
    </script>
    
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
    
    <div class="overlay-wrapper" >
        <div class="overlay" id="loading1"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Carregando...</div>
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
                        <h1 class="m-0 text-dark">Cadastro de extra</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><strong>Cadastro de extra</strong></li>
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
                        <h3 class="card-title">Cadastro de extra:</h3>

                        <div class="card-tools">
                            <button type="button"  class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>

                        </div>
                    </div>
                    <!-- /.card-header -->
                <div class="container my-2" <?php if(intval($config['ativo']) == 1 ){ echo"style='display: none'"; } ?>>
                    <div class="callout callout-danger">
                        <h5>Aviso!</h5>
                        <p>Cadastro de extra está desativado no momento.</p>
                    </div>
                </div>  
                    
            <div class="container my-2" <?php if(intval($config['ativo']) == 0 ){ echo"style='display: none'"; } ?>> 
            <form id="form" accept-charset="utf-8" >
               
               <!-- area de campos do form -->
               
               <div class="row"> 
                 <div class="form-group col-md-6"> 
                     <label for="nome">Nome</label> 
                      <select name="nome" class="chosen-select form-control " tabindex="3" id="nome" required="">
                        <option value="">...</option>
                       <?php while($row = mysqli_fetch_array($query_funcionario)) { ?>
                         <option value="<?php echo $row['cpf'] ?>"> <?php echo $row['nome'] ?> </option>
                      <?php } ?>
                     </select> 
                   </div>
                   <div class="form-group col-md-6"> 
                     <label for="funcao_extra">Função do Extra</label> 
                       <select name="funcao_extra" class="chosen-select form-control" tabindex="3" id="funcao_extra" required>
                       <option value="">...</option>
                       <?php while($row = mysqli_fetch_array($query_funcaoextra)) { ?>
                         <option value="<?php echo $row['id_funcao'] ?>"> <?php echo $row['funcao'] ?> </option>
                      <?php } ?>
                     </select>
                   </div>
                   <div class="form-group col-md-8"> 
                     <label for="unidade_extra">Unidade do Extra</label> 
                       <select name="unidade_extra" class="chosen-select form-control" tabindex="3" id="unidade_extra" required>
                       <option value="">...</option>
                       <?php while($row = mysqli_fetch_array($query_unidadeextra)) { ?>
                         <option value="<?php echo $row['id'] ?>"> <?php echo $row['nome'] ?> </option>
                      <?php } ?>
                     </select>
                   </div>
                   <div class="form-group col-md-4"> 
                     <label for="escala_extra">Escala do Extra</label> 
                       <select name="escala_extra" class="chosen-select form-control" tabindex="3" id="escala_extra" required>
                       <option value="">...</option>
                       <?php while($row = mysqli_fetch_array($query_escalaoextra)) { ?>
                         <option value="<?php echo $row['id'] ?>"> <?php echo $row['escala'] ." - ". $row['tipo'] ?> </option>
                      <?php } ?>
                     </select>
                   </div>
                   <div class="form-group col-md-3"> 
                     <label for="data_extra">Data do Extra</label> 
                     <input type="date" class="form-control form-control-sm" name="data_extra" id="data_extra" min="" value="" > 
                   </div>
                   <div class="form-group col-md-2"> 
                     <label for="hora_ent">Hora Entrada</label> 
                     <input type="time" class="form-control form-control-sm" name="hora_ent" id="hora_ent" value="" required> 
                   </div>
                   <div class="form-group col-md-2"> 
                     <label for="hora_sai">Hora Saída</label> 
                     <input type="time" class="form-control form-control-sm" name="hora_sai" id="hora_sai" value="" required> 
                   </div>
                   <div class="form-group col-md-5"> 
                     <label for="motivo_extra">Motivo do Extra</label> 
                       <select name="motivo_extra" class="chosen-select form-control" tabindex="3" id="motivo_extra" required>                         <option value="">...</option>
                         <option value="Atestado medico">Atestado Médico</option>
                         <option value="Atraso">Atraso</option>
                         <option value="Falta">Falta</option>
                         <option value="Licenca">Licença</option>
                         <option value="Licenca maternidade">Licença Maternidade</option>
                         <option value="Ferias">Cobertura de Férias</option>
                         <option value="Demissão">Demissão</option>
                         <option value="Folga">Folga</option>
                         <option value="Falecimento">Falecimento</option>
                         <option value="aumento quadro">Aumento de Quadro</option>
                         <option value="Transferencia">Transferencia</option>
                         <option value="Outros">Outros</option>
                       </select>
                   </div>
                   <div class="form-group col-md-6" id="substituido" > 
                     <label for="substituido">Funcionário substituído</label> 
                     <select id="1substituido" class="chosen-select form-control substituido"  tabindex="3" name="substituido" required>
                       <option value="">...</option>
                       <?php while($row = mysqli_fetch_array($query_substituido)) { ?>
                         <option value="<?php echo $row['cpf'] ?>"> <?php echo $row['nome'] ?> </option>
                      <?php } ?>
                     </select>
                   </div>
                   <div class="form-group col-md-12"> 
                     <label for="obs_extra">Observação:</label>
                     <textarea type="text-area" rows="3" class="form-control" name="obs_extra" id="obs_extra" value="" ></textarea>
                   </div>
                 
              </div>
 
              
               
                   <input type="hidden" name="token" value="<?php echo$token; ?>">
                
 
               <hr /> 
               <div class="row"> 
               <div class="form-group col-md-12 text-right">

                    <a class="btn btn-app bg-danger espera" id="negar" value="" name="btn-salvar1" style="display: show">
                    <i class="fas fa-user-minus"></i>
                    Cancelar
                    </a>
                    <button type="submit" class="btn btn-app bg-success espera" id="_enviar" style="display: show">
                    <i class="fas fa-plus-square"></i>
                    Cadastrar
                    </button>

                    <a class="btn btn-app" id="_salvando" style="display: none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>Salvando...
                    </a>
                </div> 
             </div>   
            <div>
            </form> 
            </div class="novo_extra" style="display: show">
                <div class="form-group col-md-12 text-right">
                        <a class="btn btn-app bg-success novo_extra" id="_novo_extra" value="" id="btn-salvar1" style="display: show">
                        <i class="fas fa-plus-square"></i>
                        Novo Extra
                        </a>
                </div>     
            </div>
            
                        <div class="card-footer text-right text-info" style="display: block;">
                            <small>WISE - SISTEMAS</small>
                        </div>
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
                            
                        $('.chosen-select').select2()

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })
                        </script>
</body>

</html>