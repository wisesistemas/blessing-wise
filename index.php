<?php
session_start();
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
unset($_SESSION['itens_insert_estoque']);
$token = md5(date("d-m-Y-s"));
$_SESSION['token'] = $token;


?>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>WISE - SISTEMAS</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <style>
    input {
      font-family: 'Noto Sans JP';
      font-style: normal;
      font-size: 12px;
      font-display: swap;
    }
  </style>
  <script>
    $(document).ready(function() {

      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 6000
      });
  
      $(".envia").click(function() {
        var nome = $("#nome").val();
        var senha = $("#senha").val();
        $.ajax({
          url: 'validar_acesso.php',
          type: "POST",
          data: {
            nome: nome,
            senha: senha,
            action: 1
          },
          success: function(data) {
            console.log(data);
            if (data == 0) {
              Toast.fire({
                icon: 'error',
                title: 'Usuário ou Senha inválidos!'
              })
            } else
            if (data == 1) {
              $("#envia").hide();
              Toast.fire({
                icon: 'success',
                title: 'Por favor, Selecione a unidade!'
              });
              $.ajax({
                url: 'validar_acesso.php',
                type: "POST",
                data: {
                  action: 2
                },
                success: function(data1) {
                  $("#unidade").html(data1);
                }
              })
            } else {
              Toast.fire({
                icon: 'error',
                title: 'ERRO: 555'
              })
            }
          }
        })
      })

      <?php
      if (isset($_GET['erro'])) {
        if ($_GET['erro'] == 1) {
          echo "
            Toast.fire({
              icon: 'error',
              title: 'Sua sessão foi encerrada!'
            });
            ";
        } else {
        }
      }

      ?>

    })
  </script>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo ">
      <a href="#"><b>WISE</b> - SISTEMAS</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body elevation-5">
        <p class="login-box-msg">Faça login para iniciar sua sessão</p>

        <div class="input-group mb-3">
          <input type="text" class="form-control  " value="BASE: WISE_PRODUCAO" readonly>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-database elevation-3"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Usuário" id="nome">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user-tie elevation-3"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Senha" id="senha">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock elevation-3"></span>
            </div>
          </div>
        </div>
        <div id="unidade"></div>
        <div class="row">
          <div class="col-8">

          </div>
          <!-- /.col -->
          <div class="col-4">

          </div>
          <!-- /.col -->
        </div>


        <div class="social-auth-links text-center mb-3">
          <a href="#" class="btn btn-block btn-primary envia elevation-3" id="envia">
            <i class="fas fa-sign-in-alt mr-2"></i></i> Acessar
          </a>
        </div>
        <!-- /.social-auth-links -->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <script>

  </script>
</body>

</html>