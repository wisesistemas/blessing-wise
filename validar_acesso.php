<?php
    session_start();
    date_default_timezone_set('America/Sao_Paulo');
    require_once('validar/class/db.class.php');
    $objDb = new db();
    $link = $objDb->conecta_mysql();
    if(intval($_POST['action']) == 1){
        $usuario = $_POST['nome'];
        $senha = md5($_POST['senha']);
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha' ";
        $resultado_id = mysqli_query($link, $sql);
        if($resultado_id){
            $dados_usuario = mysqli_fetch_array($resultado_id);
    
            if(isset($dados_usuario['usuario'])){
                $nome = explode(' ',$dados_usuario['nome']);
                $_SESSION['usuario_nome'] =$nome[0];
                $_SESSION['usuario_id'] = $dados_usuario['id'];
                $_SESSION['usuario_avatar'] = $dados_usuario['avatar'];
                /* administrador do sistema */
                $_SESSION['usuario_admin_master'] = $dados_usuario['admin'];
                /* Administrador Estoque */
                $_SESSION['usuario_admin_estoque'] = $dados_usuario['adm_estoque'];
                /* Administrador RH_DP */
                $_SESSION['usuario_admin_rh_dp'] = $dados_usuario['rh_admin'];
                /* Administrador TI */
                $_SESSION['usuario_admin_ti'] = $dados_usuario['ti'];
                /* Acessa RH*/
                $_SESSION['usuario_rh'] = $dados_usuario['rh'];
                echo 1;
            /* 	header('Location: selecionar_unidade.php'); */
    
            } else {
            /* 	header('Location: index.php?erro=1'); */
                echo 0;
            }
        } else {
            /* echo 'Erro na execu��o da consulta, favor entrar em contato com o admin do site'; */
        }
    }else
    if(intval($_POST['action']) == 2){
    ?>
   
  <div class="input-group mb-3">
        <select class="form-control  select2 text-sm" id="unidade_select" >
                                                <option selected="selected" value="">...</option>
                                                <?php 
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
                                                    $sql1 = mysqli_query( $link, $sql1 );
                                                    while ( $sqlItem = mysqli_fetch_array( $sql1 ) ) {
                                                    ?>
                                                <option  class="text-sm" value="<?php echo $sqlItem['central'].'*'.$sqlItem['nome'].'*'.md5(rand()).'*'.md5((intval($sqlItem['id'])+1000)).'*'.(intval($sqlItem['id'])+1000); ?>"><?php echo$sqlItem['nome']?>
                                                </option>
                                                <?php } ?>
                                            </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-home"></span>
            </div>
          </div>
        </div>
        <div class="social-auth-links text-center mb-3">
        <a href="#" class="btn btn-block btn-primary " id="acesar">
            <i class="fas fa-sign-in-alt mr-2"></i></i> Acessar
        </a>
      </div>
        <!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>

<script>
                        $('.select2').select2()

                        //Initialize Select2 Elements
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })
                        const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 6000
        });
                        $(document).on('click','#acesar', function(){
            var unidade = $("#unidade_select").val();
            $.ajax({
                url: 'validar_acesso.php',
                type: "POST",
                data: {action: 3, unidade: unidade },
                success: function(data){
                   if(data == 1){
                    window.location.href = "home.php";
                   }else{
                    Toast.fire({
                            icon: 'error',
                            title: 'Por favor, selecione uma unidade!'
                        })
                   }
 
            }
            })
         })
                        </script>
                        
    <?php }else
    
            if(intval($_POST['action']) == 3){
               
                if(!empty($_POST['unidade']) == false ){/* Unidade não selecionada */
                    echo '0';
                }else{/* Unidade selecionada */
                    $res = explode('*',$_POST['unidade']);
                    $_SESSION['usuario_id_unidade'] = intval($res[4])-1000;
                    $_SESSION['usuario_nome_unidade'] = $res[1];
                    $_SESSION['usuario_unidade_central'] = $res[0];
                    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    $pegar_ip = $_SERVER["REMOTE_ADDR"];
                    $log = fopen("log/log_acesso.txt",'a+');
                    fwrite($log, "Data: (".date("Y-m-d  h:i:s").") | Usuário Nome: "
                    .$_SESSION['usuario_nome'].", id ".$_SESSION['usuario_id']
                    ." | Unidade: ".$_SESSION['usuario_nome_unidade'].", id ".$_SESSION['usuario_id_unidade']
                    ." | $hostname | $pegar_ip".get_client_ip()
                    ."\r\n");
                    fclose($log);
                    echo '1';
                }

            }

            function get_client_ip() {
              $ipaddress = '';
              if (isset($_SERVER['HTTP_CLIENT_IP']))
                  $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
              else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                  $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
              else if(isset($_SERVER['HTTP_X_FORWARDED']))
                  $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
              else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                  $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
              else if(isset($_SERVER['HTTP_FORWARDED']))
                  $ipaddress = $_SERVER['HTTP_FORWARDED'];
              else if(isset($_SERVER['REMOTE_ADDR']))
                  $ipaddress = $_SERVER['REMOTE_ADDR'];
              else
                  $ipaddress = 'UNKNOWN';
              return $ipaddress;
          }
?>
	
