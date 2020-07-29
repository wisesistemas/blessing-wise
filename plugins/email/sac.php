<?php

 date_default_timezone_set('America/sao_paulo');	
  require_once('db.class.php');
  $objDb = new db();
  $link = $objDb->conecta_mysql();

  $sql = "SELECT max(id_sac) as id_sac FROM `sac`";
  $sql = mysqli_query($link,$sql);
  $sql = mysqli_fetch_array($sql);
  $rest = intval(substr($sql['id_sac'], -4)) + 1;
  $parametro1 = date('Ym');
  $sac =  str_pad($rest, 4, '0', STR_PAD_LEFT);
  echo$sacNovo = $parametro1.$sac;

  $nome = strtoupper($_POST['nome']);
  $tel = $_POST['tel'];
  $email = strtoupper($_POST['email']);
  $setor = strtoupper($_POST['setor']);
  $msg = $_POST['msg'];

  $sql = "
  INSERT INTO `sac`(`id_sac`, `nome`, `email`, `tel`, `setor`, `msg`) VALUES 
  (
  $sacNovo,
  '$nome',
  '$email',
  '$tel',
  '$setor',
  '$msg'
  )";
  $sql = mysqli_query( $link, $sql );

// Inclui o arquivo class.phpmailer.php localizado na mesma pasta do arquivo php
include "PHPMailerAutoload.php";
 
// Inicia a classe PHPMailer
$mail = new PHPMailer();
 
// Método de envio
$mail->IsSMTP(); // Enviar por SMTP 
$mail->Host = "smtp.gmail.com"; // Você pode alterar este parametro para o endereço de SMTP do seu provedor
$mail->Port = 587; 
 
$mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório)
$mail->Username = 'suporte.ti.blessing@gmail.com'; // Usuário do servidor SMTP (endereço de email)
$mail->Password = 'bless1ng'; // Mesma senha da sua conta de email
 
// Configurações de compatibilidade para autenticação em TLS
$mail->SMTPOptions = array(
 'ssl' => array(
 'verify_peer' => false,
 'verify_peer_name' => false,
 'allow_self_signed' => true
 )
);
// $mail->SMTPDebug = 2; // Você pode habilitar esta opção caso tenha problemas. Assim pode identificar mensagens de erro.
 
// Define o remetente
$mail->From = "suporte.ti.blessing@gmail.com"; // Seu e-mail
$mail->FromName = "WISE"; // Seu nome
 
// Define o(s) destinatário(s)

$mail->AddAddress('sac@laboratorioblessing.com.br', 'SAC');
//$mail->AddAddress('luiz.maciel@laboratorioblessing.com.br', 'Luiz');

//$mail->AddAddress('fernando@email.com');
 
 
// CC
//$mail->AddCC('joana@provedor.com', 'Joana'); 
 
// BCC - Cópia oculta
//$mail->AddBCC('roberto@gmail.com', 'Roberto'); 
 
// Definir se o e-mail é em formato HTML ou texto plano
$mail->IsHTML(true); // Formato HTML . Use "false" para enviar em formato texto simples.
 
$mail->CharSet = 'UTF-8'; // Charset (opcional)
 
// Assunto da mensagem
$mail->Subject = "Novo SAC - $sacNovo"; 
 
// Corpo do email
$mail->Body = "
<p>-- Novo Registro de SAC --</p>
<p>Nome: <strong>$nome</strong></p>
<p>Telefone: <strong>$tel</strong></p>
<p>Email: <strong>$email</strong></p>
<p>Setor: <strong>$setor</strong></p>
<p>Mensagem: <br>
	<strong>$msg</strong>
</p>
";

 
// Anexos (opcional)
//$mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf"); 
 
// Envia o e-mail
$enviado = $mail->Send();
 
 
// Exibe uma mensagem de resultado

 
?>