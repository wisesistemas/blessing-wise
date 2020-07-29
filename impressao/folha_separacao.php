<?php
require('fpdf.php');
session_start();
class PDF extends FPDF
{
// Page header
function Header()
{   
    
    $req = $_GET['req'];
    require_once('db.class.php');
    $objDb = new db();
    $link = $objDb->conecta_mysql();
    $req = $_GET['req'];
    $usuarioUnidade = $_SESSION['usuario_id_unidade'];
    date_default_timezone_set('America/Sao_Paulo');
    // Logo
    $this->Image('blessing.png',14,10,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Title
    $this->Cell(190,10,utf8_decode('FOLHA DE SEPARAÇÃO'),1,0,'C');
    // Line break
    $this->Ln();

    $this->SetFont('Arial','B',9);
    $this->Cell(30,4,utf8_decode('Nº NOTA:  '),1,0,'L');
    $this->Cell(160,4,utf8_decode('PEDIDO:  REQ'.$req),1,0,'L');
    $this->Ln();

    /* ** INICIO DAS INFORMAÇÕES DO REMETENTE ** */
    $sql = "SELECT nome, logradouro, bairro, cep, uf, cidade, fone, numero
    FROM cad_unidade 
    WHERE id = $usuarioUnidade";
    $sql = mysqli_query( $link, $sql );
    $remetente = mysqli_fetch_array( $sql );

    $this->SetFont('Arial','B',9);
    $this->Cell(190,4,utf8_decode('ESTOQUE CENTRAL:'),1,0,'L');
    $this->Ln();

    $this->SetFont('Arial','',4);
    $this->Cell(110,3,utf8_decode('NOME / RAZÃO SOCIAL:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('CNPJ / CPF:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('DATA DA EMISSÃO:'),'LR',0,'L');
    $this->Ln();
    $this->SetFont('Arial','B',6);
    $this->Cell(110,3,utf8_decode('LABORATORIO BIOMEDIC / '.$remetente['nome']),'LRB',0,'L');//VARIAVEL DE NOME / RAZÃO SOCIAL
    $this->Cell(40,3,utf8_decode('26.429.351/0001-06'),'BR',0,'L');//VARIAVEL DE CNPJ / CPF
    $this->Cell(40,3,utf8_decode(date('d').'/'.date('m').'/'.date('Y'))." ".date('H:i').'h','BR',0,'L');
    $this->Ln();

    $this->SetFont('Arial','',4);
    $this->Cell(80,3,utf8_decode('ENDEREÇO:'),'LR',0,'L');
    $this->Cell(30,3,utf8_decode('BAIRRO:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('CEP:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('DT SAÍDA MATERIAL:'),'LR',0,'L');
    $this->Ln();
    $this->SetFont('Arial','B',6);
    $this->Cell(80,3,utf8_decode($remetente['logradouro'].", Nº ".$remetente['numero']),'BLR',0,'L');//VARIAVEL DO ENDEREÇO
    $this->Cell(30,3,utf8_decode($remetente['bairro']),'BR',0,'L');//VARIAVEL BAIRRO
    $this->Cell(40,3,utf8_decode($remetente['cep']),'BR',0,'L');//VARIAVEL CEP
    $this->Cell(40,3,utf8_decode(''),'BR',0,'L');
    $this->Ln();

    $this->SetFont('Arial','',4);
    $this->Cell(50,3,utf8_decode('MUNICÍPIO:'),'LR',0,'L');
    $this->Cell(30,3,utf8_decode('TEL:'),'LR',0,'L');
    $this->Cell(30,3,utf8_decode('UF:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('INSCRIÇÃO ESTUTAL:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('TEMPERATURA:'),'LR',0,'L');
    $this->Ln();
    $this->SetFont('Arial','',6);
    $this->Cell(50,3,utf8_decode($remetente['cidade']),'BLR',0,'L');//VARIAVEL DO MUNICÍPIO
    $this->Cell(30,3,utf8_decode($remetente['fone']),'BR',0,'L');//VARIAVEL TEL
    $this->Cell(30,3,utf8_decode($remetente['uf']),'BR',0,'L');//VARIAVEL UF
    $this->Cell(40,3,utf8_decode(''),'BR',0,'L');//VARIAVEL INSCRIÇÃO ESTADUAL
    $this->Cell(40,3,utf8_decode(''),'BR',0,'L');
    $this->Ln();
    /* ** FIM DAS INFORMAÇÕES DO REMETENTE ** */

    /* ** INICIO DAS INFORMAÇÕES DO DESTINATÁRIO ** */
    $sql = "SELECT u.nome as nome, u.logradouro as logradouro, u.bairro as bairro, u.cep as cep, u.uf as uf, u.cidade as cidade, u.fone as tel, u.numero as numero, r.DATA AS cad_data
    FROM estoque_registro_requisicao r 
    INNER JOIN cad_unidade u 
    ON r.unidade = u.id
    WHERE r.requisicao = $req";
    $sql = mysqli_query( $link, $sql );
    $destinatario = mysqli_fetch_array( $sql );

    $this->SetFont('Arial','B',9);
    $this->Cell(190,4,utf8_decode('SOLICITANTE:'),1,0,'L');
    $this->Ln();

    $this->SetFont('Arial','',4);
    $this->Cell(110,3,utf8_decode('NOME / RAZÃO SOCIAL:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('CNPJ / CPF:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('DATA DA REQUISIÇÃO::'),'LR',0,'L');
    $this->Ln();
    $this->SetFont('Arial','B',6);
    $this->Cell(110,3,utf8_decode('LABORATORIO BIOMEDIC / '.$destinatario['nome']),'LRB',0,'L');//VARIAVEL DE NOME / RAZÃO SOCIAL
    $this->Cell(40,3,utf8_decode('26.429.351/0001-06'),'BR',0,'L');//VARIAVEL DE CNPJ / CPF
    $this->Cell(40,3,utf8_decode(date("d/m/Y", strtotime($destinatario['cad_data']))),'BR',0,'L');
    $this->Ln();

    $this->SetFont('Arial','',4);
    $this->Cell(80,3,utf8_decode('ENDEREÇO:'),'LR',0,'L');
    $this->Cell(30,3,utf8_decode('BAIRRO:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('CEP:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('DT SAÍDA MATERIAL:'),'LR',0,'L');
    $this->Ln();
    $this->SetFont('Arial','B',6);
    $this->Cell(80,3,utf8_decode($destinatario['logradouro'].", Nº ".$destinatario['numero']),'BLR',0,'L');//VARIAVEL DO ENDEREÇO
    $this->Cell(30,3,utf8_decode($destinatario['bairro']),'BR',0,'L');//VARIAVEL BAIRRO
    $this->Cell(40,3,utf8_decode($destinatario['cep']),'BR',0,'L');//VARIAVEL CEP
    $this->Cell(40,3,utf8_decode(''),'BR',0,'L');
    $this->Ln();

    $this->SetFont('Arial','',4);
    $this->Cell(50,3,utf8_decode('MUNICÍPIO:'),'LR',0,'L');
    $this->Cell(30,3,utf8_decode('TEL:'),'LR',0,'L');
    $this->Cell(30,3,utf8_decode('UF:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('INSCRIÇÃO ESTUTAL:'),'LR',0,'L');
    $this->Cell(40,3,utf8_decode('TEMPERATURA:'),'LR',0,'L');
    $this->Ln();
    $this->SetFont('Arial','B',6);
    $this->Cell(50,3,utf8_decode($destinatario['cidade']),'BLR',0,'L');//VARIAVEL DO MUNICÍPIO
    $this->Cell(30,3,utf8_decode($destinatario['tel']),'BR',0,'L');//VARIAVEL TEL
    $this->Cell(30,3,utf8_decode($destinatario['uf']),'BR',0,'L');//VARIAVEL UF
    $this->Cell(40,3,utf8_decode(''),'BR',0,'L');//VARIAVEL INSCRIÇÃO ESTADUAL
    $this->Cell(40,3,utf8_decode(''),'BR',0,'L');
    $this->Ln();
    /* ** FIM DAS INFORMAÇÕES DO DESTINATÁRIO ** */

}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-16);
   
  
    $this->SetFont('Arial','',6);
    $this->Cell(90,5,utf8_decode('DADOS ADICIONAIS'),'TRL',0,'L');
    $this->Cell(50,5,utf8_decode('ASSINATURA RESPONSÁVEL ENVIO'),'TR',0,'L');
    $this->Cell(50,5,utf8_decode('ASSINATURA RESPONSÁVEL RECEBIMENTO'),'TR',0,'L');
    $this->Ln();

    $this->Cell(90,5,utf8_decode(''),'BL',0,'L');
    $this->Cell(50,5,utf8_decode(''),'B',0,'L');
    $this->Cell(50,5,utf8_decode(''),'BR',0,'L');
    $this->Ln();
    $this->SetFont('Arial','',6);
    $this->Cell(190,5,utf8_decode('SISTEM WISE: FOLHA DE SEPARAÇÃO.'),0,0,'L');



    $this->SetFont('Arial','I',8);
    $this->Cell(0,8,'Pag: '.$this->PageNo().'/{nb}',0,0,'R');
}
}
        
        // CORPO DO DOCUMENTO
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->Ln();

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(15,6,utf8_decode('COD'),1,0,'C');
        $pdf->Cell(25,6,utf8_decode('UND'),1,0,'C');
        $pdf->Cell(105,6,utf8_decode('ITEM EM SEPARAÇÃO'),1,0,'C');
        $pdf->Cell(20,6,utf8_decode('QTD.S'),1,0,'C');
        $pdf->Cell(25,6,utf8_decode('QTD'),1,0,'C');
        $pdf->Ln();

         require_once('nome.item.class.php');
        $nomeItem = new nomeItem();
        $idUsuario = $_SESSION['usuario_id'];
        require_once('db.class.php');
        $objDb = new db();
        $link = $objDb->conecta_mysql();
        $familia = $_GET['familia'];
        $genero = $_GET['genero'];
        $req = $_GET['req'];
        date_default_timezone_set('America/Sao_Paulo');
        /* WHILE DOS ITENS */

         if( 2 == intval($familia) || 1 == intval($familia) ){
        $sql = "SELECT i.id as idItem, i.nome as item, r.id as atualizar, i.referencia as referencia, r.qtd_slt as qtds, m.descricao as medida, r.id as id_item_atualizar
            FROM estoque_item_requisicao r 
            INNER JOIN cad_item i 
            ON r.item = i.id
            INNER JOIN cad_medida m
            ON i.und_estoque = m.id
            where r.registro = $req && i.familia = $familia";
      }else{
      $sql = "SELECT i.id as idItem, i.nome as item, r.id as atualizar, i.referencia as referencia, r.qtd_slt as qtds, m.descricao as medida, r.id as id_item_atualizar
            FROM estoque_item_requisicao r 
            INNER JOIN cad_item i 
            ON r.item = i.id
            INNER JOIN cad_medida m
            ON i.und_estoque = m.id
            where r.registro = $req && i.familia = $familia && i.genero = $genero";
          }

        $sql = mysqli_query( $link, $sql );
        while ( $item = mysqli_fetch_array( $sql ) ) {
        $itemAtualizar[] = $item['id_item_atualizar'];
        $pdf->SetFont('Times','',9);
        $pdf->Cell(15,7,utf8_decode( $item['idItem'] ),1,0,'C');
        $pdf->Cell(25,7, utf8_decode( $item['medida'] ) ,1,0,'C');
        $pdf->Cell(105,7, $nomeItem->nome($item['idItem']) ,1,0,'C');
        $pdf->Cell(20,7,utf8_decode( $item['qtds'] ),1,0,'C');
        $pdf->Cell(25,7,utf8_decode( "" ),1,0,'C');
        $pdf->Ln();

        }
        /* FIM WHILE DOS ITENS */

        /* TOTAL DE ITENS ENVIADOS */
        $pdf->SetFont('Times','B',11);
        $pdf->Cell(165,8,utf8_decode('TOTAL'),1,0,'C');
        $pdf->Cell(25,8,'',1,0,'C');
        $pdf->Ln();
        /* FIM TOTAL DE ITENS ENVIADOS */

        /* ATUALIZAR STATUS DOS ITEM IMPRESSO */
        $data = date('Y-m-d');
        $_SESSION['itens_insert_estoque'] = $itemAtualizar;
        /* foreach ($itemAtualizar as $key => $idRegistro) {
            $sql = "UPDATE `estoque_item_requisicao` SET `status` = '560', `data` = '$data', `usuario` = $idUsuario WHERE `estoque_item_requisicao`.`id` = $idRegistro;";
            //$sql = mysqli_query( $link, $sql );
        } */
        
        /* FIM ATUALIZAR STATUS DOS ITEM IMPRESSO */
 
        $pdf->Output();
?>