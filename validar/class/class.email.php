<?php
date_default_timezone_set('America/sao_paulo');
include "email/PHPMailerAutoload.php";

class Email extends PHPMailer
{

    function emailChamado($chamado, $motivo, $tel, $falar, $msg){

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
        $mail->FromName = "Suporte"; // Seu nome

        // Define o(s) destinatário(s)
        $mail->AddAddress('suporte@laboratorioblessing.com.br', 'WISE');
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
        $mail->Subject = "NOVO CHAMADO - ".$chamado;

        // Corpo do email
        $mail->Body = "
            <p>-- NOVO CHAMADO: ".$chamado."</p>
            <p>Falar Com: <strong>$falar</strong></p>
            <p>Telefone: <strong>$tel</strong></p>
            <p>Mensagem: <br>
                <strong>$msg</strong>
            </p>
            ";


        // Anexos (opcional)
        //$mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf"); 

        // Envia o e-mail
        $enviado = $mail->Send();
        //Exibe uma mensagem de resultado

    }
}

/* $email = new Email();
$chamado    =   "Chamado Teste";
$nome       =   "Michel reis";
$tel        =   "(21) 9 8654-9303";
$falar      =   "Michel";
$msg        =   "Teste";
$email->emailChamado($chamado,$nome,$tel,$falar,$msg); */