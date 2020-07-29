<?php
	session_start();

	if(!isset($_SESSION['usuario'])){
		header('Location: index.php?erro=1');
	}
	require_once('db.class.php');
	$objDb = new db();
	$link = $objDb->conecta_mysql();
	$usuarioNome = $_SESSION['nome'];
    $usuarioUnidade = $_SESSION['unidade'];
    $id_usuario = $_SESSION['id_usuario'];

    $sql ="SELECT * FROM `usuarios` where id = $id_usuario";
	$sql = mysqli_query( $link , $sql);
	$userUnidade = mysqli_fetch_array( $sql );
	$_SESSION['unidade'] = $userUnidade['unidade'];
	$_SESSION['idUsuario'] = $userUnidade['id'];
?>



<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Padrão</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>
    <body class="bg-light" >
       
       <!-- Static navbar - Inicio -->
       <!--Início do Menu-->
	    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <div class="container">
            <a class="nav-link active text-white" href="home.php"><strong>Wise</strong></a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarsExample07" style="">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                  <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="alterar_senha.php">Mudar Senha</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="modal" data-target="#modalSair">Sair</a>
                </li>
                 <li class="nav-item">
                  <a class="nav-link" href=""></a>
                </li>
                 <li class="nav-item">
                  <a class="nav-link" href=""></a>
                </li>
                 <li class="nav-item">
                  <a class="nav-link" href=""></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href=""></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href=""></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-light" data-toggle="modal" data-target="#modalGic" ><strong>GIC - Abertura de Chamados</strong></a>
                </li>
              </ul>
              <form class="form-inline my-2 my-md-0" action="" method="GET" accept-charset="utf-8" >
                <input class="form-control" type="text" name="chamado" placeholder="Localizar pedido..." aria-label="Search">
              </form>
            </div>
          </div>
        </nav><!--Fim do Menu-->
        <!-- Modal sair -->
            <div class="modal fade" id="modalSair" tabindex="-1" role="dialog" aria-labelledby="modalSair" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Encerrar Sessão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Clique em "Sair" para encerrar sua sessão.
                    Esperamos vê-lo novamente!

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                    <a class="btn btn-primary" href="sair.php">Sair</a>
                  </div>
                </div>
              </div>
            </div><!--Fim MOdal Sair-->
             <!-- Modal GIC -->
            <div class="modal fade" id="modalGic" tabindex="-1" role="dialog" aria-labelledby="modalGic" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">GIC - Gerenciamento Interno de Chamados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Você será redirecionado para o GIC.
                    Utilize seu usuário e senha para logar e abrir um chamado com a TI.

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                    <a class="btn btn-primary alert-link" href="http://laboratorioblessing.dyndns.org:8181/GCI/" target="_blank">Confirmar</a>
                  </div>
                </div>
              </div>
            </div><!--Fim MOdal GIC-->
        </nav><!--Fim do Menu-->
        <!--Static navbar - Fim -->
        
        <!--Barra status - Inicio-->
            <div class="alert alert-secondary bg-dark border-info rounded-0" >
        <div class="container">
            
            <di class="row">
                <div class="col-md-3">
                    <div class="text-primary"><strong> Usuario:</strong> <h5> <span class="badge badge-primary badge-pill"><?php echo $usuarioNome; ?></span></h5></div>
                </div>
                <div class="col-md-6">
                    <div class="text-primary"><strong>Unidade:</strong> <h5> <span class="badge badge-primary badge-pill"><?php echo $usuarioUnidade; ?></span></h5></div>
                </div>
                
                <div class="col-md-3">
                    <div class="text-primary"><strong>Pedidos em transporte:</strong> <h5> <span class="badge badge-primary badge-pill"></span></h5></div>
                </div>
                
            </di>
            </div>
        </div>
        <!--Barra status - Fim-->
         <!--Corpo - Inicio-->
        <div class="container ">
            <div class="card">
                <div class="card-header">
                    <p class="text-light bg-success text-center py-2 rounded border border-success"><strong>Padrão</strong></p>
                </div>
                <div class="card-body">
                   <div id="main" class="container-fluid">
                        <h3 class="page-header"></h3>
        
        <?php
          
        $sql = "SELECT c.id,c.nome 
        FROM `iten_unidade` i 
        right JOIN cad_item_novo c 
        ON i.item_id = c.id
        where i.id is null";
        $sql = mysqli_query($link,$sql);

        while($result = mysqli_fetch_array($sql)){

                 $sql1 = "SELECT * FROM `cad_unidade`";
                   $sql1 = mysqli_query($link,$sql1);

        while($unidade = mysqli_fetch_array($sql1)){

          $ativar = "INSERT INTO `iten_unidade` (`id`, `registro`, `item_nome`, `item_id`, `unidade_nome`, `unidade_id`, `usuario`, `data_hora`, `ativo`) VALUES (NULL, '100', '".$result['nome']."', '".$result['id']."', '".$unidade['nome']."', '".$unidade['id']."', '0', 'envio', 'Não');";

            $ativa = mysqli_query($link, $ativar);














          }

        }

        



        ?>                
        


      </div>
    </footer>   
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
