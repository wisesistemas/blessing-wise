<?php
require_once('../validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();
$file_tmp   = $_FILES['xml_exemplo']['tmp_name'];
$object     = simplexml_load_file($file_tmp);
foreach ($object->NFe as $key => $xml) {
    $chave          =  preg_replace("/(\d{4})(\d{4})(\d{4})(\d{4})(\d{4})(\d{4})(\d{4})(\d{4})(\d{4})(\d{4})(\d{4})/", "\$1 \$2 \$3 \$4 \$5 \$6 \$7 \$8 \$9 \$10 \$11", preg_replace("/\D/", '', strval(str_replace('NFe', '', $xml->infNFe->attributes()["Id"]))));
    $versaoXml      = strval(str_replace('NFe', '', $xml->infNFe->attributes()["versao"]));
    $codNfe         = strval($xml->infNFe->ide->nNF);
    $serie          = strval($xml->infNFe->ide->serie);
    $valorTotalNf   = "R$: " . number_format(strval($xml->infNFe->total->ICMSTot->vNF), 2, ",", ".");
    /* Emitente */
    $cnpj           = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", preg_replace("/\D/", '', strval($xml->infNFe->emit->CNPJ)));
    $nomeEmpresa    = strval($xml->infNFe->emit->xNome);
    $escEstadual    = strval($xml->infNFe->emit->IEST);
    $uf             = strval($xml->infNFe->emit->enderEmit->UF);
    $dtEmissao      = strval(strstr($xml->infNFe->ide->dhEmi, "T", true));
    /* Destinatário */
    $cnpjDest           = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", preg_replace("/\D/", '', strval($xml->infNFe->dest->CNPJ)));
    $nomeDest           = strval($xml->infNFe->dest->xNome);
    /* Informações dos produtos da Nfe */
    foreach ($xml->infNFe->det as $keyProduto => $infoProduto) {
        $produtoCod[]            = strval($infoProduto->prod->cProd);   /* Código do produto */
        $produtoNome[]           = strval($infoProduto->prod->xProd);   /* Nome do Produto */
        $produtoUnidadeMedida[]  = strval($infoProduto->prod->uCom);    /* Unidade de venda */
        $produtoQtd[]            = strval($infoProduto->prod->qCom);    /* Quantidade */
        $produtoValorUnitario[]  = "R$: " . number_format(strval($infoProduto->prod->vUnCom));  /* Valor unitário */
        $produtoValorTotal[]     =  "R$: " . number_format(strval($infoProduto->prod->vProd), 2, ",", ".");   /* Valor total */
    }
    /* Fim - Informações dos produtos da Nfe */
}

?>
<form role="form" id="fileUploadForm" enctype="multipart/form-data" method="post">
    <fieldset class="border m-2 " style="background: #FAFAFA;">
        <legend class="w-auto">Dados da NF-e</legend>
        <div class="card-body" style="
    padding-left: 2px;
    padding-right: 2px;
    padding-top: 0px;">
            <legend class="w-auto legend">Dados Gerais</legend>
            <div class="form-row box ">
                <div class="form-group col-2">
                    <label for="modelo">Modelo:</label>
                    <input type="text" name="modelo" class="form-control form-control-sm pula id=" modelo" readonly value="">
                </div>
                <div class="form-group col-2">
                    <label for="serie">Série:</label>
                    <input type="text" name="serie" class="form-control form-control-sm pula" id="serie" readonly value="<?php echo $serie ?>">
                </div>
                <div class="form-group col-2">
                    <label for="numero">Número:</label>
                    <input type="text" name="numero" class="form-control form-control-sm pula" id="numero" readonly value="<?php echo $codNfe ?>">
                </div>
                <div class="form-group col-3">
                    <label for="dt_hr_emissao">Data Emissão:</label>
                    <input type="date" name="dt_hr_emissao" class="form-control form-control-sm pula" id="dt_hr_emissao" value="<?php echo $dtEmissao ?>" readonly>
                </div>
                <div class="form-group col-3">
                    <label for="dt_hr_entrada">Data Entrada:</label>
                    <input type="date" name="dt_hr_entrada" class="form-control form-control-sm pula" id="dt_hr_entrada" placeholder="">
                </div>
                <div class="form-group col-4">
                    <label for="valor_total">Valor Total da Nota Fiscal:</label>
                    <input type="text" name="valor_total" class="form-control form-control-sm pula" id="valor_total" readonly value="<?php echo $valorTotalNf ?>">
                </div>
                <div class="form-group col-6">
                    <label for="chave">Chave de Acesso:</label>
                    <input type="text" name="chave" class="form-control form-control-sm pula" id="chave" readonly value="<?php echo $chave ?>">
                </div>
                <div class="form-group col-2">
                    <label for="v_xml">Versão XML:</label>
                    <input type="text" name="v_xml" class="form-control form-control-sm pula" id="v_xml" value="<?php echo $versaoXml ?>" readonly>
                </div>
            </div>
            <legend class="w-auto legend">Destinatário</legend>
            <div class="form-row box ">
                <div class="form-group col-6">
                    <label for="destinatario_nome">Nome / Razão Social:</label>
                    <input type="text" name="destinatario_nome" class="form-control form-control-sm pula" id="destinatario_nome" value="<?php echo $nomeDest ?>" readonly>
                </div>
                <div class="form-group col-6">
                    <label for="destinatario_cnpj">CNPJ:</label>
                    <input type="text" name="destinatario_cnpj" class="form-control form-control-sm pula" id="destinatario_cnpj" value="<?php echo $cnpjDest ?>" readonly>
                </div>
            </div>
            <legend class="w-auto legend">Emitente</legend>
            <div class="form-row box ">
                <div class="form-group col-6">
                    <label for="emitente_cnpj">CNPJ:</label>
                    <input type="text" name="emitente_cnpj" class="form-control form-control-sm pula" id="emitente_cnpj" value="<?php echo $cnpj ?>" readonly>
                </div>
                <div class="form-group col-6">
                    <label for="emitente_nome">Nome / Razão Social:</label>
                    <input type="text" name="emitente_nome" class="form-control form-control-sm pula" id="emitente_nome" value="<?php echo $nomeEmpresa ?>" readonly>
                </div>
                <div class="form-group col-3">
                    <label for="emitente_escricao_estadual">Inscrição Estadual:</label>
                    <input type="text" name="emitente_escricao_estadual" class="form-control form-control-sm pula" id="emitente_escricao_estadual" value="<?php echo $escEstadual ?>" readonly>
                </div>
                <div class="form-group col-2">
                    <label for="emitente_uf">UF:</label>
                    <input type="text" name="emitente_uf" class="form-control form-control-sm pula" id="emitente_uf" value="<?php echo $uf ?>" readonly>
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
        </div>
        <!-- Cadastro de item -->
        <!-- <legend class="w-auto legend">Casdastro de item</legend> -->
        <?php
        $num = 0;
        foreach ($xml->infNFe->det as $keyProduto => $infoProduto) {

            if ($num % 2 == 0) {
                $style = "style='background: #eadcae;'";
            } else {
                $style = "";
            }
            $num++;
        ?>
            <legend class="w-auto legend">Casdastro de item <?php echo $num; ?></legend>
            <div class="form-row box " <?php echo $style; ?>>
                <div class="form-group col-2">
                    <label for="item_forn_cod">COD do item Fornecedor:*</label>
                    <input type="text" name="item_forn_cod" class="form-control form-control-sm pula" id="item_forn_cod" readonly value="<?php echo strval($infoProduto->prod->cProd); ?>">
                </div>
                <div class="form-group col-6">
                    <label for="item_forn_nome">Descrição do fornecedor:*</label>
                    <input type="text" name="item_forn_nome" class="form-control form-control-sm pula" id="item_forn_nome" readonly value="<?php echo strval($infoProduto->prod->xProd); ?>">
                </div>
                <div class="form-group col-2">
                    <label for="item_qtd">Quantidade:*</label>
                    <input type="text" name="item_qtd" class="form-control form-control-sm pula" id="item_qtd" readonly value="<?php echo intval(strval($infoProduto->prod->qCom)); ?>">
                </div>
                <div class="form-group col-2">
                    <label for="item_qtd1">Quantidade Informada:*</label>
                    <input type="text" name="" class="form-control form-control-sm pula" id="qtd_<?php echo strval($infoProduto->prod->cProd);?>"  value="">
                </div>
                <div class="form-group col-2">
                    <label for="item_forn_uni" style="padding-bottom: 0px;margin-bottom: 0px;">Unidade Comercial:*</label>
                    <input type="text" name="item_forn_uni" class="form-control form-control-sm pula" readonly id="item_forn_uni" value="<?php echo strval($infoProduto->prod->uCom); ?>">
                </div>
                <div class="form-group col-8">
                    <label for="destinatario_nome_cnpj" style="padding-bottom: 0px;margin-bottom: 0px;">Item Wise:*</label>
                    <select class="form-control form-control-sm  select2 " id="item_wise" name="item_wise">
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
                            <option value="<?php echo $sqlItem['id'] . '*' . $sqlItem['nome'] . ' ' . $sqlItem['referencia'] . ' ' . $sqlItem['marca'] ?>">
                                <small><?php echo $sqlItem['nome'] . ' ' . $sqlItem['referencia'] . ' ' . $sqlItem['marca'] ?></small>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-2">
                    <label for="valor_unit">Valor Total:*</label>
                    <input type="text" name="valor_unit" class="<php >form-control form-control-sm pula" readonly id="valor_unit" value="<?php echo "R$: " . number_format(strval($infoProduto->prod->vProd), 2, ",", "."); ?>">
                </div>


                <div class="form-group col-2 my-3">
                    <button type="button" class="<?php echo strval($infoProduto->prod->cProd); ?> btn btn-xs btn-primary col-12 my-1 inserir" value="<?php echo strval($infoProduto->prod->cProd); ?>"><i class="fas fa-arrow-down"></i> Inserir Pedido</button>
                </div>

            </div>
            </div>


            <div id="<?php echo strval($infoProduto->prod->cProd); ?>" class="form-row box " <?php echo $style; ?>>
                <!--  <div class="form-group col-1 text-right" >
                    <i class="fas fa-long-arrow-alt-right text-info " style="font-size: 32px;"></i>
                </div>
                <div class="form-group col-5">
                    <label for="item_forn_cod">Número do Pedido:</label>
                    <input type="text" name="item_forn_cod" class="form-control " id="item_forn_cod1" value="">
                </div>
                <div class="form-group col-5">
                    <label for="item_forn_cod">Valor Untpario:</label>
                    <input type="text" name="item_forn_cod" class="form-control " id="item_forn_cod1" value="">
                </div>
                <div class="form-group col-1 my-4 text-center">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 22px;"></i>
                </div> -->
            </div>



            </div>


            <!-- tabela -->


            </div>
            <div class="form-group col-12 ">
                <hr>
            </div>
        <?php } ?>
    </fieldset>



    <!-- fim body card -->
    </div>

    <!-- /.card-body -->
    <div class="card-footer text-right">
        <button class="btn btn-app" id="btnSubmit"><i class="fas fa-save" style="color: green; font-size: 24px"></i>Salvar</button>
    </div>
    <!-- /.card-footer-->
    </div>
    <!-- /.card -->
</form>

<script>
    $(document).ready(function() {

        $(".inserir").click(function() {
            var id = $(this).attr("value");
            var idClass = Math.floor(Math.random() * 100) + id;
            var id_item = $(this).attr('class').split(' ')[0];
            $("#" + id).append("<div class='form-group col-1 text-right remove" + idClass + "'>" +
                "<svg width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-arrow-return-right my-4' fill='currentColor' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M10.146 5.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L12.793 9l-2.647-2.646a.5.5 0 0 1 0-.708z'/><path fill-rule='evenodd' d='M3 2.5a.5.5 0 0 0-.5.5v4A2.5 2.5 0 0 0 5 9.5h8.5a.5.5 0 0 0 0-1H5A1.5 1.5 0 0 1 3.5 7V3a.5.5 0 0 0-.5-.5z'/></svg>" +
                "</div>" +
                "<div class='form-group col-5 remove" + idClass + "'>" +
                    "<label for=''>Número do Pedido:</label>" +
                    "<input type='text'  class='form-control pula form-control-sm' value=''>" +
                "</div>" +
                "<div class='form-group col-5 remove" + idClass + "'>" +
                    "<label for=''>Quantidade:</label>" +
                    "<input type='number'    class='id_item_"+id_item+" form-control pula form-control-sm soma remove"+idClass+"'>" +
                "</div>" +
                "<div class='form-group col-1  text-left remove" + idClass + "'>" +
                    "<a class='deletar_item ' id='remove" + idClass + "' >" +
                        "<i class='fas fa-trash-alt text-danger my-4' style='font-size: 22px;'></i>" +
                     "</a>" +
                "</div>");
        })

        $(document).on('click', '.deletar_item', function() {
            var id = $(this).attr('id');
            $('.' + id).remove();
        })

        $(document).on('keyup', '.pula', function(e) {
            var tecla = e.keyCode ? e.keyCode : e.which;
            if (tecla == 13) {
                campo = $(".pula");
                indice = campo.index(this);
                e.preventDefault(e);
                if (campo[indice + 1] != null) {
                    proximo = campo[indice + 1];
                    proximo.focus();
                }
            }
        })

        $(document).on('keyup', '.soma', function(e) {
           var classVal = $(this).attr('class').split(' ')[0];
           var idQtdTotal = classVal.split('_')[2];
           var total = 0;
           $('.'+classVal).each(function(){
               total = total + Number( $(this).val() );   
            }); 
            //mostro o total no input Sub Total
            console.log();
         $("#qtd_"+idQtdTotal).val(total);
 
        })


    })
</script>