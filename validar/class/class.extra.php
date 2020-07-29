<?php
require_once("db.class.php");
date_default_timezone_set('America/Sao_Paulo');



class Extra extends db
{
    private $link;


    function __construct()
    {
        $objDb = new db();
        $this->link = $objDb->conecta_mysql();
    }

    function extraLibera($id, $valor, $usuario)
    {
        $sql = "UPDATE extras SET `valor_pagar` = '$valor', `status` = '20', `atualizado` = '$usuario' WHERE id = $id;";
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            return "1";
        } else {
            return '0';
        }
    }

    function extraNegar($id, $motivo, $usuario)
    {
        $date = date('Y-m-d');
        $sql = "UPDATE extras SET `status` = '19' WHERE id = $id;";
        $sql = mysqli_query($this->link, $sql);
        $sql = "INSERT INTO `acoes_extras`(`id`, `id_extra`, `data_acao`, `mensagem`, `usuario`) VALUES (NULL,'$id','$date','$motivo',' $usuario');";
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            echo "1";
        } else {
            echo '0';
        }
    }

    function extraAtualizar($id, $nome, $funcao, $unidade, $escala, $data, $horaEntrada, $horaSaida, $motivo, $substituto, $usuario)
    {
        $sql = "UPDATE `extras` SET 
        `nome`= '$nome',
        `funcao_extra`= '$funcao',
        `unidade_extra`= '$unidade',
        `escala_extra`= '$escala',
        `data`= '$data',
        `hora_ent`= '$horaEntrada',
        `hora_sai`= '$horaSaida',
        `motivo`= '$motivo',
        `substituido`= '$substituto',
        `status`= '10',
        `atualizado`= '$usuario' 
        WHERE id= $id";
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            return "1";
        } else {
            return '0';
        }
    }

    function cad_extra($nome, $funcao_extra, $unidade_extra, $escala_extra, $data_extra, $hora_ent, $hora_sai, $motivo_extra, $substituido, $obs_extra, $id_usuario, $dataCad)
    {
        $sql = "INSERT INTO `extras`(`id`, `nome`, `funcao_extra`, `unidade_extra`, `escala_extra`, `data`, `hora_ent`, `hora_sai`, `motivo`, `substituido`, `obs_extra`, `status`, `cadastrado`, `dt_cad`) VALUES 
        (
            NULL, 
            '$nome', 
            '$funcao_extra', 
            '$unidade_extra', 
            '$escala_extra', 
            '$data_extra', 
            '$hora_ent', 
            '$hora_sai', 
            '$motivo_extra', 
            '$substituido', 
            '$obs_extra', 
            5, 
            '$id_usuario', 
            '$dataCad'
        );";
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            echo "1";
        } else {
            echo '0';
        }
    }

    function extraAtualizarStatus($unidades, $statusNovo, $statusAntigo, $data)
    {
        $sql = "UPDATE extras SET `status` = $statusNovo where unidade_extra $unidades AND `status` = $statusAntigo AND `data` <= '$data';";
        $sqlMudarStatus = mysqli_query($this->link, $sql);
        if ($sqlMudarStatus) {
            return mysqli_affected_rows($this->link);
            mysqli_close($this->link);
        } else {
            return "erro_query";
            mysqli_close($this->link);
        }
    }

    function confDataMinExtra($data, $suario, $ativo)
    {
        $sql = "INSERT INTO `rh_data_extra_ativo`(`id`, `data`, `ativo`, `dt_atual`, `usu_atual`) VALUES 
        (
            NULL,
            '$data',
            '$ativo',
            NULL,
            '$suario'
        )";
        $sql = mysqli_query($this->link, $sql);
        if (intval($sql) == 1) {
            echo "1";
        } else {
            echo '0';
        }
    }

    function enviaEmailComprovante()
    {
        // Inclui o arquivo class.phpmailer.php localizado na mesma pasta do arquivo php
        include "email/PHPMailerAutoload.php";

        // Inicia a classe PHPMailer
        $mail = new PHPMailer();

        // Método de envio
        $mail->IsSMTP(); // Enviar por SMTP 
        $mail->Host = "smtp.gmail.com"; // Você pode alterar este parametro para o endereço de SMTP do seu provedor
        $mail->Port = 587;

        $mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório)
        $mail->Username = 'wise.sistema@gmail.com'; // Usuário do servidor SMTP (endereço de email)
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
        $mail->From = "wise.sistema@gmail.com"; // Seu e-mail
        $mail->FromName = "WISE"; // Seu nome

        // Define o(s) destinatário(s)

        $mail->AddAddress('wise.sistema@gmail.com', 'SAC');



        // CC
        //$mail->AddCC('joana@provedor.com', 'Joana'); 

        // BCC - Cópia oculta
        //$mail->AddBCC('roberto@gmail.com', 'Roberto'); 

        // Definir se o e-mail é em formato HTML ou texto plano
        $mail->IsHTML(true); // Formato HTML . Use "false" para enviar em formato texto simples.

        $mail->CharSet = 'UTF-8'; // Charset (opcional)

        // Assunto da mensagem
        $mail->Subject = "Teste Extra - 201";

        // Corpo do email
        $mail->Body = "Teste1sdsfsff";


        // Anexos (opcional)
        //$mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf"); 

        // Envia o e-mail
        $enviado = $mail->Send();


        // Exibe uma mensagem de resultado
    }
}
/* 
$Extra = new Extra();
echo $Extra->enviaEmailComprovante(); */
