<?php
date_default_timezone_set('America/Sao_Paulo');
require_once("class/class.estoque.php");
$objEstoque = new Estoque();
require_once("class/class.itemEstoque.php");
$objItemEstoque = new ItemEstoque();
require_once("class/class.requisicao.php");
$requisicao = new Requisicao();
require_once("class/class.fornecedor.php");
$objFornecedor = new Fornecedor();
require_once("class/class.nfe.php");
$obgNfe = new Nfe();
require_once("class/class.usuario.php");
$objUsuario = new Usuario();
require_once("class/class.extra.php");
$objExtra = new Extra();
require_once("class/class.prmtEstoque.php");
$objPrmtEstoque = new PrmtEstoque();
require_once("class/class.funcionario.php");
$objFuncionario = new Funcionario();
require_once("class/class.chamado.php");
$objChamado = new Chamado();
require_once("class/class.email.php");
$objEmail = new Email();
require_once("class/class.compra.php");
$objCompra = new Compra();
require_once("class/class.financeiro.php");
$objFinanceiro = new Financeiro();
require_once("class/class.faturamento.php");
$objFaturamento = new Faturamento();
session_start();
switch (intval($_GET['id'])) {
    case 1: /* Inseri item no array, telas: etq_inventario, etq_baixa */
        $token      = $_POST["token"];
        $item       = $_POST["item"];
        empty($_POST["qtd"]) ? $qtd = 0 : $qtd = $_POST["qtd"];
        $nomeItem   = $objItemEstoque->nomeItemParametrizado($item);
        $saldo      = $objEstoque->saldoAtual($_SESSION['usuario_id_unidade'], $item);
        if ($token == $_SESSION['token'] && !empty($item)) {
            $_SESSION['itens_insert_estoque'][$item] = array(
                "nome" => "$nomeItem",
                "qtd_insert" => "$qtd",
                "qtd_atual" => "$saldo",

            );
            echo 1;
        } else {
            echo 0;
        }
        break;
    case 2: /* Exclui item do array ou zera o array, telas: etq_inventario, etq_baixa */
        if (intval($_GET['prmt']) == 2) {
            unset($_SESSION['itens_insert_estoque']);
        } else {
            $item = $_POST['item'];
            unset($_SESSION['itens_insert_estoque'][$item]);
        }
        break;
    case 3: /* Valida formulario das telas: etq_inventario, etq_baixa */
        if ($_POST['token'] == $_SESSION['token'] && !empty($_SESSION['itens_insert_estoque'])) {
            if (intval($_GET['id']) == 3 &&  intval($_GET['prmt']) == 1) { /* Inventario */
                $objEstoque->registraInventario($_SESSION['itens_insert_estoque'], $_SESSION['usuario_id_unidade'], $_SESSION['usuario_id']);
                unset($_SESSION['itens_insert_estoque']);
            } else
            if (intval($_GET['id']) == 3 &&  intval($_GET['prmt']) == 2) { /* Baixa de material */
                $objEstoque->baixaDeMaterial($_SESSION['itens_insert_estoque'], $_SESSION['usuario_id_unidade'], $_SESSION['usuario_id']);
                unset($_SESSION['itens_insert_estoque']);
            } else
            if (intval($_GET['id']) == 3 &&  intval($_GET['prmt']) == 3) { /* Acerto de entrada */
                $objEstoque->acertoEntrada($_SESSION['itens_insert_estoque'], $_SESSION['usuario_id_unidade'], $_SESSION['usuario_id']);
                unset($_SESSION['itens_insert_estoque']);
            } else
            if (intval($_GET['id']) == 3 &&  intval($_GET['prmt']) == 4) { /* Acerto de saída */
                $objEstoque->acertoSaida($_SESSION['itens_insert_estoque'], $_SESSION['usuario_id_unidade'], $_SESSION['usuario_id']);
                unset($_SESSION['itens_insert_estoque']);
            }
            echo 1;
        } else {
            echo 0;
        }
        break;
    case 4: /* Atendimento de requisições para estoque central */
        $post = explode("&", $_POST['id']);
        $req = explode("=", $post[0]);
        $req = $req[1];
        $familia = explode("=", $post[1]);
        $familia = $familia[1];
        $genero = explode("=", $post[2]);
        $genero = $genero[1];
        $tipoDeReq = explode("_", $_POST['id']);
        $tipoDeReq = $tipoDeReq[0];
        !isset($_POST['obs']) ? $obs = '' : $obs = $_POST['obs'];

        if (intval($tipoDeReq) == 1) {/* Cancela item */
            foreach ($_SESSION['itens_insert_estoque']  as $key => $value) {
                $objEstoque->atualizaStatusItem($value, '592', $_SESSION['usuario_id'], $obs);
                $objEstoque->registraObsCentral($req, $obs);
            }
        } else
        if (intval($tipoDeReq) == 2) {/* Atualiza para impresso folha de separação */
            if (intval($_POST['confirmarImpressao']) == 1) { /* Se for reimpressão de item em espera (pendente) não atualiza */
                foreach ($_SESSION['itens_insert_estoque']  as $key => $value) {
                    $objEstoque->atualizaStatusItem($value, '560', $_SESSION['usuario_id']);
                }
            }
        } else
        if (intval($tipoDeReq) == 3) {/* Atende item */
            $form = explode("&", $_POST['form1']);
            $obs = $_POST['obs'];
            foreach ($form as $key => $value) {
                $qtd = explode("=", $value);
                $id = explode("_", $value);
                $array_gtd[] = [
                    "qtd" => $qtd[1],
                    "id" => $id[1]
                ];
            }
            foreach ($array_gtd  as $key => $value) {
                if ($value['qtd'] == '') {/* Itens Pendentes */
                    $objEstoque->atualizaStatusItem($value['id'], '555', $_SESSION['usuario_id'], $obs);
                    $objEstoque->registraObsCentral($_SESSION['req'], $obs);
                } else 
                if (intval($value['qtd']) == 0) {/*  Itens Negados*/
                    $objEstoque->atualizaStatusItem($value['id'], '592', $_SESSION['usuario_id'], $obs);
                    $objEstoque->registraObsCentral($_SESSION['req'], $obs);
                } else {/* Itens atendidos */
                    $objEstoque->atualizaStatusItem($value['id'], '563', $_SESSION['usuario_id'], $obs, $value['qtd']);
                    $objEstoque->baixaMaterialAtendimento($_SESSION['itens_req1'][$value['id']], $_SESSION['usuario_id_unidade'], $_SESSION['usuario_id'], $value['qtd']);
                    $objEstoque->registraObsCentral($_SESSION['req'], $obs);
                }
            }
        }
        unset($_SESSION['itens_insert_estoque']);
        unset($_SESSION['itens_req1']);
        unset($_SESSION['req']);
        echo 1;
        break;
    case 5: /* Inseri item no array, itens para requisição  */

        $token      = $_POST["token"];
        $item       = $_POST["item"];
        empty($_POST["qtd"]) ? $qtd = 0 : $qtd = $_POST["qtd"];
        empty($_POST["qtd_estoque"]) ? $saldo = 0 : $saldo = $_POST["qtd_estoque"];
        empty($_POST["ref"]) ? $ref = '' : $ref = $_POST["ref"];
        $nomeItem   = $objItemEstoque->nomeItemParametrizado($item);
        $referencia_obrigatoria = $objItemEstoque->requeridoReferencia($item);
        /*  echo var_dump( !empty($ref) );
        echo var_dump( !empty($referencia_obrigatoria) );
        echo var_dump( !empty($ref) == !empty($referencia_obrigatoria) ); */
        if ($token == $_SESSION['token'] && !empty($item) && $qtd > 0 && (!empty($ref) == !empty($referencia_obrigatoria))) {
            $_SESSION['itens_insert_estoque'][$item] = array(
                "nome" => "$nomeItem",
                "qtd_insert" => "$qtd",
                "qtd_atual" => "$saldo",
                "ref" => "$ref"

            );
            echo 1;
        } else {
            echo 0;
        }
        break;
    case 6: /* Cadastro de nova requisição */
        $token = $_POST['token'];
        $obs = $_POST['obs'];
        if ($_POST['token'] == $_SESSION['token'] && !empty($_SESSION['itens_insert_estoque'])) {
            echo $requisicao->cadastraRequisicao($_SESSION['usuario_id_unidade'], $_SESSION['usuario_id'], $obs, $_SESSION['itens_insert_estoque']);
        } else {
            echo "0";
        }
        unset($_SESSION['itens_insert_estoque']);
        break;
    case 7:
        echo $objItemEstoque->requeridoReferencia($_POST['id']);
        break;
    case 8: /* cADASTRO DE NF-E MANUDAL */

        $validar_form = true;
        foreach ($_POST as $key => $value) {
            if (!empty($value) == false) {
                $validar_form = false;
            }
        }
        if ($validar_form == true) {
            $item_forn_cod  = $_POST["item_forn_cod"];
            $item_forn_nome = strtoupper($_POST["item_forn_nome"]);
            $item_qtd       = $_POST["item_qtd"];
            $item_forn_uni  = $_POST["item_forn_uni"];
            $item_wise      = explode('*', $_POST["item_wise"]);
            $valor_unit     = $_POST["valor_unit"];
            $item_id_wise   = $item_wise[0];
            $item_nome_wise = strtoupper($item_wise[1]);
            $array_item[$item_id_wise] = [
                "item_forn_cod"     => $item_forn_cod,
                "item_forn_nome"    => $item_forn_nome,
                "item_qtd"          => $item_qtd,
                "item_forn_uni"     => $item_forn_uni,
                "valor_unit"        => $valor_unit,
                "item_id_wise"      => $item_id_wise,
                "item_nome_wise"    => $item_nome_wise
            ];
            $_SESSION['nf_array_item'][$item_id_wise] = $array_item;
            echo '1';
        } else {
            echo '0';
        }

        break;
    case 9: /* Busca fornecedor por nome: array para autocomplit */
        echo json_encode($objFornecedor->fonecedoresPorNome());
        break;
    case 10: /* Busca fornecedor por CNPJ: array para autocomplit */
        echo json_encode($objFornecedor->fonecedoresPorCnpj());
        break;
    case 11: /* Busca fornecedor por cnpj */
        echo json_encode($objFornecedor->fonecedoresPorCnpjUnico($_POST['cnpj']));;
        break;
    case 12: /* Busca fornecedor por cnpj */
        echo json_encode($objFornecedor->fonecedoresPorNomeUnico($_POST['nome']));;
        break;
    case 13: /* Cadatro da NF-e */

        if (!empty($_FILES["exampleFormControlFile1"]['name'])) {/* valida arquivo */
            if (isset($_SESSION['nf_array_item']) == true) {/* valida item */
                // matriz de entrada
                $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');
                // matriz de saída
                $by   = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');
                $file_name                  = str_replace($what, $by, $_FILES['exampleFormControlFile1']['name']);
                $file_tmp                   = $_FILES['exampleFormControlFile1']['tmp_name'];
                $ext                        = pathinfo($file_name, PATHINFO_EXTENSION);
                $nome                       = md5(date('dmYHis'));
                $nome_arquivo               = $nome . '.' . $ext;
                $modelo                     = $_POST["modelo"];
                $serie                      = $_POST["serie"];
                $codigo                     = $_POST["numero"];
                $dt_emissao                 = $_POST["dt_hr_emissao"];
                $dt_entrada                 = $_POST["dt_hr_entrada"];
                $valor_total                = str_replace('R$:', '', str_replace(',', '.', $_POST["valor_total"]));
                $chave                      = $_POST["chave"];
                $v_xml                      = $_POST["v_xml"];
                $destinatario_nome          = $_POST["destinatario_nome"];
                $destinatario_cnpj          = $_POST["destinatario_cnpj"];
                $emitente_cnpj              = $_POST["emitente_cnpj"];
                $emitente_nome              = $_POST["emitente_nome"];
                $emitente_escricao_estadual = $_POST["emitente_escricao_estadual"];
                $emitente_uf                = $_POST["emitente_uf"];
                $objFornecedor->verificaEcadastraFornecedor($emitente_nome, $emitente_cnpj, $emitente_escricao_estadual, $emitente_uf);
                if ($obgNfe->verificaNfeVSfonecedor($codigo, $emitente_cnpj) == 0) {
                    $caminho                    = move_uploaded_file($file_tmp, "nfe/" . $nome_arquivo);
                    $obgNfe->cadNfe($modelo, $serie, $codigo, $dt_emissao, $dt_entrada, $valor_total, $chave, $v_xml, $destinatario_cnpj, $emitente_cnpj, $nome_arquivo, $_SESSION['nf_array_item']);
                } else {
                    echo "erro_nfe_existe";
                }
            } else {
                echo "erro_sem_item";
            }
        } else {
            echo "erro_sem_arquivo";
        }



        break;
    case 14:
        $item = explode("*", $_POST['id']);
        unset($_SESSION['nf_array_item'][$item[1]]);
        echo $_POST['id'];
        break;
    case 15:/* Folha de impressão */
        foreach ($_SESSION['itens_impressao'] as $key => $value) {
            $requisicao->atualizaStatusItem($value, '565', $_SESSION['usuario_id'], '1');
        }
        echo 1;
        break;
    case 16: /* Confirmar recebimento e retificação de quantidade solicitada */
        $tipo = intval($_POST['tipo']);
        $form = str_replace('d5B5D', '', $_POST['form']);
        $form = str_replace('%5D', '', $form);
        $form = str_replace('%5B', '', $form);
        $form = str_replace('&', '', $form);
        $id = explode('id=', $form);
        unset($id[0]);
        if ($tipo == 1) { /* Recebimento */
            foreach ($id as $key => $value) {
                $valor      = explode('qtd=', $value);
                $itemID     = intval($valor[0]);
                $itemQtd    = intval($valor[1]);
                $requisicao->recebimentoEretificacao(1, $itemID, $_SESSION['usuario_id'], '590', $itemQtd);
                $objEstoque->entradaPorRequisicao($_SESSION['item_id_receber'][$itemID],  $_SESSION['unidade_id_receber'], $_SESSION['usuario_id'], $itemQtd);
            }
            unset($_SESSION['item_id_receber']);
            unset($_SESSION['unidade_id_receber']);
            echo "1";
        } else
        if ($tipo == 2) {/* Retificação */
            foreach ($id as $key => $value) {
                $valor      = explode('qtd=', $value);
                $itemID     = intval($valor[0]);
                $itemQtd    = intval($valor[1]);
                $requisicao->recebimentoEretificacao(2, $itemID, $_SESSION['usuario_id'], '552', $itemQtd);
            }
            echo "1";
        }
        break;
    case 17:
        $objUsuario->atualizaAvatar($_SESSION['usuario_id'], $_POST['avatar_radios']);
        $_SESSION['usuario_avatar'] = $_POST['avatar_radios'];
        break;
    case 18: /* Cadastro de item */
        $nome               = strtoupper($_POST["nome"]);
        $ref                = strtoupper($_POST["ref"]);
        $modelo             = strtoupper($_POST["modelo"]);
        $familia            = $_POST["familia"];
        $genero             = $_POST["genero"];
        $marca              = strtoupper($_POST["marca"]);
        $unidade_medida     = $_POST["uni_estoque"];
        $unidade_compra     = $_POST["uni_campra"];
        $validade           = $_POST["control_validade"];
        $armazenamento      = $_POST["armazenamento"];
        $solicitar_ref      = $_POST['solicitar_ref'];
        isset($_POST["ativo"]) ? $ativo = 1 : $ativo = 0;
        !empty($_POST['id_item']) ? $idItem = $_POST['id_item'] : $idItem = 0;
        if ($_SESSION['token_cad_item'] == $_POST["validador"]) {
            if ($idItem == 0) {
                $objItemEstoque->cadItem($nome, $ref, $marca, $modelo, $familia, $genero, $armazenamento, $unidade_compra, $unidade_medida, $validade, $solicitar_ref, $ativo);
            } else {
                $objItemEstoque->atualizaItem($idItem, $nome, $ref, $marca, $modelo, $familia, $genero, $armazenamento, $unidade_compra, $unidade_medida, $validade, $solicitar_ref, $ativo);
            }
        } else {
        }
        var_dump($idItem);
        break;
    case 19:/* Extra: Liberação, edição e negar */
        !isset($_POST["token"]) ? $token = $_POST["token1"] : $token = $_POST["token"];
        if ($token == $_SESSION['token_form_gerencia_extra']) {
            if (isset($_POST['token1'])) {
                $motivo = $_POST["motivo_negar"];
                $id = $_POST["id1"];
                $objExtra->extraNegar($id, $motivo, $_SESSION['usuario_id']);
            } else
           if (isset($_POST['editar'])) {
                $funcionario        = $_POST["funcionario"];
                $funcao_colaborador = $_POST["funcao_colaborador"];
                $unidade            = $_POST["unidade"];
                $funcao_extra       = $_POST["funcao_extra"];
                $escala             = $_POST["escala"];
                $data               = $_POST["data"];
                $hora_entrada       = $_POST["hora_entrada"];
                $hora_saida         = $_POST["hora_saida"];
                $motivo             = $_POST["motivo"];
                $substituto         = $_POST["substituto"];
                $valor_pagar        = $_POST["valor_pagar"];
                $editar             = $_POST["editar"];
                $objExtra->extraAtualizar(
                    $editar,
                    $funcionario,
                    $funcao_extra,
                    $unidade,
                    $escala,
                    $data,
                    $hora_entrada,
                    $hora_saida,
                    $motivo,
                    $substituto,
                    $_SESSION['usuario_id']
                );
                echo "editado";
            } else
           if (isset($_POST['liberar'])) {
                $id = $_POST['liberar'];
                if (empty($_POST['valor_pagar'])) {
                    echo "erro_sem_valor";
                } else {
                    $objExtra->extraLibera($id, $_POST['valor_pagar'], $_SESSION['usuario_id']);
                    echo "liberado";
                }
            }
        } else {
        }
        break;
    case 20:
        /*   var_dump($_POST); */
        $dataCad        = date('Y-m-d');
        $nome           = $_POST["nome"];
        $funcao_extra   = $_POST["funcao_extra"];
        $unidade_extra  = $_POST["unidade_extra"];
        $escala_extra   = $_POST["escala_extra"];
        $data_extra     = $_POST["data_extra"];
        $hora_ent       = $_POST["hora_ent"];
        $hora_sai       = $_POST["hora_sai"];
        $motivo_extra   = $_POST["motivo_extra"];
        $obs_extra      = $_POST["obs_extra"];
        $token          = $_POST["token"];
        if ($_POST["motivo_extra"] == "Outros" || $_POST["motivo_extra"] == "aumento quadro") {
            $substituido    = '';
        } else {
            $substituido    = $_POST["substituido"];
        }
        if ($token == $_SESSION['token_cad_extra']) {
            $objExtra->cad_extra(
                $nome,
                $funcao_extra,
                $unidade_extra,
                $escala_extra,
                $data_extra,
                $hora_ent,
                $hora_sai,
                $motivo_extra,
                $substituido,
                $obs_extra,
                $_SESSION['usuario_id'],
                $dataCad
            );
        } else {
            echo "sem_token";
        }
        break;
    case 21:
        isset($_POST['unidade']) ? $unidade = $_POST['unidade'] : $unidade = NULL;
        !empty($_POST['data']) ? $data = $_POST['data'] : $data = '9999-31-12';
        $statusAntigo = $_POST['statusAntigo'];
        $statusNovo = $_POST['statusNovo'];

        if ($unidade != null) {
            if (in_array('todas', $unidade)) {/* Todas unidades */
                $query = " > 0 ";
                echo $objExtra->extraAtualizarStatus($query, $statusAntigo, $statusNovo, $data);
            } else {/* Unidades */
                $unidades = implode(",", $_POST['unidade']);
                $query = "in(" . $unidades . ")";
                echo $objExtra->extraAtualizarStatus($query, $statusAntigo, $statusNovo, $data);
            }
        } else {
            echo "sem_unidade";
        }
        break;
    case 22:
        !empty($_POST['data']) ? $data = $_POST['data'] : $data = NULL;
        if ($data == NULL) {
            echo $objExtra->confDataMinExtra(NULL, $_SESSION['usuario_id'], 0);
        } else {
            echo $objExtra->confDataMinExtra($data, $_SESSION['usuario_id'], 1);
        }
        break;
    case 23: /* Carregar xml */
        $file_tmp   = $_FILES['xml_exemplo']['tmp_name'];
        $object     = simplexml_load_file($file_tmp);
        foreach ($object->NFe as $key => $xml) {
            $chave          = strval(str_replace('NFe', '', $xml->infNFe->attributes()["Id"]));
            $versaoXml      = strval(str_replace('NFe', '', $xml->infNFe->attributes()["versao"]));
            $codNfe         = strval($xml->infNFe->ide->nNF);
            $serie          = strval($xml->infNFe->ide->serie);
            $valorTotalNf   = strval($xml->infNFe->total->ICMSTot->vNF);
            /* Emitente */
            $cnpj           = strval($xml->infNFe->emit->CNPJ);
            $nomeEmpresa    = strval($xml->infNFe->emit->xNome);
            $escEstadual    = strval($xml->infNFe->emit->IEST);
            $uf             = strval($xml->infNFe->emit->enderEmit->UF);
            $dtEmissao      = strval(strstr($xml->infNFe->ide->dhEmi, "T", true));
            /* Destinatário */
            $cnpjDest           = strval($xml->infNFe->dest->CNPJ);
            $nomeDest           = strval($xml->infNFe->dest->xNome);
            /* Informações dos produtos da Nfe */
            foreach ($xml->infNFe->det as $keyProduto => $infoProduto) {
                $produtoCod[]            = strval($infoProduto->prod->cProd);   /* Código do produto */
                $produtoNome[]           = strval($infoProduto->prod->xProd);   /* Nome do Produto */
                $produtoUnidadeMedida[]  = strval($infoProduto->prod->uCom);    /* Unidade de venda */
                $produtoQtd[]            = strval($infoProduto->prod->qCom);    /* Quantidade */
                $produtoValorUnitario[]  = strval($infoProduto->prod->vUnCom);  /* Valor unitário */
                $produtoValorTotal[]     = strval($infoProduto->prod->vProd);   /* Valor total */
            }
            /* Fim - Informações dos produtos da Nfe */
        }

        $produto = array(
            "produtoCod" => $produtoCod,
            "produtoNome" => $produtoNome,
            "produtoUnidadeMedida" => $produtoUnidadeMedida,
            "produtoQtd" => $produtoQtd,
            "produtoValorUnitario" => $produtoValorUnitario,
            "produtoValorTotal" => $produtoValorTotal
        );

        $nf = [
            "chave"         =>  $chave,
            "versaoXml"     =>  $versaoXml,
            "codNfe"        =>  $codNfe,
            "serie"         =>  $serie,
            "cnpj"          =>  $cnpj,
            "nomeEmpresa"   =>  $nomeEmpresa,
            "escEstadual"   =>  $escEstadual,
            "uf"            =>  $uf,
            "dtEmissao"     =>  $dtEmissao,
            "valorTotalNf"  =>  $valorTotalNf,
            "cnpjDest"      =>  $cnpjDest,
            "nomeDest"      =>  $nomeDest
        ];

        $xmlReturn = [
            "produto"   => $produto,
            "nf"        => $nf
        ];

        echo  json_encode($xmlReturn);
        /* var_dump( $cnpj ); */
        break;
    case 24:
        $value = explode("*", $_POST["value"]);
        $unidade    = $value[0];
        $item       = $value[1];
        $acao       = $_POST['acao'];
        if (intval($acao) == 0) { /* desativar */
            $objPrmtEstoque->desativaItemRelacionamento($unidade, $item);
        } else { /* Ativa */
            $objPrmtEstoque->ativaItemRelacionamento($unidade, $item, $_SESSION['usuario_id']);
        }
        break;
    case 25: /* Cadastro e edição de funcionario */
        $nome       = strtoupper($_POST["nome"]);
        $cpf        = str_replace('-', '', str_replace('.', '', $_POST["cpf"]));
        !empty($_POST["pis"]) ? $pis = "'" . $_POST['pis'] . "'" : $pis = 'NULL';
        $matricula  = str_pad($_POST["matricula"], 6, "0", STR_PAD_LEFT);
        $nascimento = $_POST["nascimento"];
        $empresa    = $_POST["empresa"];
        $unidade    = $_POST["unidade"];
        $funcao     = $_POST["funcao"];
        $escala     = $_POST["escala"];
        !empty($_POST["banco"]) ? $banco = "'" . $_POST['banco'] . "'" : $banco = 'NULL';
        !empty($_POST["agencia"]) ? $agencia = "'" . str_pad($_POST['agencia'], 4, "0", STR_PAD_LEFT) . "'" : $agencia = 'NULL';
        !empty($_POST["conta"]) ? $conta = "'" . str_pad($_POST['conta'], 10, "0", STR_PAD_LEFT) . "'" : $conta = 'NULL';
        !empty($_POST["digito"]) ? $digito = "'" . $_POST['digito'] . "'" : $digito = 'NULL';
        !empty($_POST["senha"]) ? $senha = md5($_POST["senha"]) : $senha = NULL;
        $validador  = $_POST["validador"];
        !empty($_POST["id_item"]) ? $idFuncionario = $_POST['id_item'] : $idFuncionario = null;
        isset($_POST['ativo']) ? $situacao = 1 : $situacao = 0;

        if ($idFuncionario == null && $_SESSION['token_cad_funcionario'] == $_POST['validador']) {
            echo $objFuncionario->cadastroFuncionario($cpf, $pis, $matricula, $nome, $nascimento, $empresa, $unidade, $funcao, $escala, $banco, $agencia, $conta, $digito, $situacao, $senha);
        } else
        if ($idFuncionario > 0 && $_SESSION['token_cad_funcionario'] == $_POST['validador']) {
            echo $objFuncionario->atualizaFuncionario($idFuncionario, $cpf, $pis, $matricula, $nome, $nascimento, $empresa, $unidade, $funcao, $escala, $banco, $agencia, $conta, $digito, $situacao, $senha);
        }

        break;
    case 26: /* Cadastro de chamado e atualização via usuario - TI */
        $tipo       = intval($_POST["tipo"]);
        $falar       = $_POST["nome"];
        $contato    = $_POST["contato"];
        $unidade    = $_POST["unidade"];
        $assunto    = $_POST["assunto"];
        $descricao  = $_POST["descricao"];
        $validador  = $_POST["validador"];
        if ($tipo == 1) { /* atualixação via usuario */
            $desc       = $_POST["desc"];
            $chamado    = $_POST["chamado"];
            $status     = $_POST['status_atua'];
            $objChamado->atualizaChamadoSolicitante($_SESSION['usuario_id'], $chamado, $desc, $status);
        } else
        if ($tipo == 2) { /* Cadastro */
            $tipoArquivo    = $_FILES['files']['type'];
            $tamanhoArquivo    = $_FILES['files']['size'];
            if (!empty($_FILES['files']['name']) == false) { //FALSO

                echo $id_chamado = $objChamado->novoChamado($_SESSION['usuario_id'], $unidade, $falar, $contato, $assunto, $descricao, '*');
                //$objEmail->emailChamado(intval($id_chamado), $assunto, $contato, $falar, $descricao);
            } else { //VERDADEIRO


                if ($tipoArquivo !== 'image/jpeg' && $tipoArquivo !== 'image/png' && $tipoArquivo !== 'image/jpg') { /* EXTENÇÃO INVALIDA*/
                    echo "erro1";
                } else
                if ($tamanhoArquivo > 20000000) { /* TAMANHO NÃO PERMITIDO */
                    echo "erro2";
                } else {

                    $arq = $objChamado->enviar($_FILES['files']);
                    echo $id_chamado = $objChamado->novoChamado($_SESSION['usuario_id'], $unidade, $falar, $contato, $assunto, $descricao, $arq);
                    //$objEmail->emailChamado(intval($id_chamado), $assunto, $contato, $falar, $descricao);
                }
            }
        }
        break;
    case 27:
        $descricao      = $_POST["message"];
        $cont_desc      = strlen($_POST["message"]);
        $chamado        = $_POST["chamado_atual"];
        $tipo           = $_GET["tipo"];
        $status_atua    = $_POST["status_atua"];
        $validador      = $_POST["validador_atual"];
        $tipoArquivo    = $_FILES['files']['type'];
        $tamanhoArquivo    = $_FILES['files']['size'];

        /*  Verifica status */
        if (intval($tipo) == 0 && (intval($status_atua) == 20 || intval($status_atua) == 1)) {
            $status = 30;
        } else
        if (intval($tipo) == 0 && (intval($status_atua) == 40 || intval($status_atua) == 45)) {
            $status = 50;
        } else
        if (intval($tipo) == 1 && intval($status_atua) == 1) {
            $status = 20;
        } else
        if (intval($tipo) == 1 && intval($status_atua) > 1) {
            $status = intval($status_atua);
        }
        /* Fim Verifica status */
        if ($_SESSION['token_adm_atender_chamado'] ==  $validador) {
            if ($cont_desc > 2) {
                if (!empty($_FILES['files']['name']) == false) { //FALSO

                    echo $objChamado->evoluirChamado($chamado, $_SESSION['usuario_id'], $descricao, $status, '*');
                } else {

                    if (false) { /* EXTENÇÃO INVALIDA*/
                        echo "erro1";
                    } else
                    if ($tamanhoArquivo > 20000000) { /* TAMANHO NÃO PERMITIDO */
                        echo "erro2";
                    } else {
                        $arq = $objChamado->enviar($_FILES['files']);
                        $objChamado->evoluirChamado($chamado, $_SESSION['usuario_id'], $descricao, $status, $arq);
                    }
                }
            } else {
                echo "erro_menor_2";
            }
        } else {
        }

        break;
    case 28:

        var_dump($_FILES['file_atual']['name']);

        var_dump($_POST);
        break;
    case 29: /* Cadastro de listra para compra */
        $item       = $_SESSION['itens_insert_estoque'];
        $token      = $_POST["token"];
        $unidade    = $_POST["unidade"];
        $objCompra->listaCrompra($item, $unidade);
        /*  var_dump($_SESSION['itens_insert_estoque']); */
        break;
    case 30: /* Carregar itens da lista de compras */
        $item       = $_SESSION['itens_insert_estoque'];
        $token      = $_POST["token"];
        $unidade    = $_POST["unidade"];
        $objCompra->carregaListaCompraInicial($unidade);
        break;
    case 31:
        /* foreach ($_POST['id_reg'] as $key => $value) {
           $idRegistroTabela = $value;
           $idRegistroTabela1 = 'fornecedor'.$value;
            $qtd = $_POST['qtd'][$key]; 
            foreach($_POST[$idRegistroTabela1] as $key => $value){
                 ECHO $qtd.' '.$value."\n"; 
                $objCompra->criaListaDeCompraFornecedor($idRegistroTabela,$value,$qtd, 1);
            }

        } */
        foreach ($_POST['id_item_lista'] as $key => $value) {
            $id_item_lista    = $value;
            strlen($_POST['qtd_compra'][$key]) > 0 ?  $qtd_compra = intval($_POST['qtd_compra'][$key]) : $qtd_compra = 'NULL1';
            if ($qtd_compra ==  '0') {/* Cancela */
                $objCompra->cancelaItemPEdido($id_item_lista);
            } else
          if ($qtd_compra > 0) {/* Registra */
                $entrega          = $_POST['entrega'][$key];
                $fornecedor       = $_POST['fornecedor'][$key];
                $unitario         = $_POST['unitario'][$key];
                $data_entrega     = $_POST['data_entrega'][$key];
                $objCompra->criaListaDeCompraFornecedor($id_item_lista, $fornecedor, $qtd_compra, $unitario, $entrega, $data_entrega);
            } else {/* Deixa pendente */
                echo "null";
            }

            /*   $objCompra->criaListaDeCompraFornecedor($id_item_lista,$fornecedor,$qtd_compra,$unitario,$entrega,$data_entrega,4); */
        }
        /*  var_dump($_POST);  */
        break;
    case 32: /* converter lista de pedidos em lista de compras */
        $objCompra->gerarItemParaCompras();
        break;
    case 33: /* Cadastro de conta fixa */
        /* 
                Tipos de retorno:
                0: Erro de autenticação
                1: Registrado com sucesso
                2: Erro de query
            */
        $token      =    $_SESSION['_token'];
        $tokenForm  =    $_POST['_token'];

        $usu = $_SESSION['usuario_id'];
        $unidade = $_SESSION['usuario_id_unidade'];

        $competencia    = (int) $_POST["competencia"];
        $doc            = $_POST["num_doc"];
        $fornecedor     = $_POST["fornecedor"];
        $categoria      = $_POST["categoria"];
        $detalhe        = $_POST["detalhe_desp"];
        $diaVencimento  = $_POST["vencimento"];
        $valor          = str_replace(',', '.', str_replace(".", "", str_replace("R$ ", "", $_POST['valor'])));
        //$diaVencimento  = substr( $vencimento[0], -2);
        /* arq */
        if (empty($_FILES['arq_pg']['name'])) {/* Sem Anexo */
           
        } else
        if ($_FILES['arq_pg']['type'] == 'application/pdf' ||  $_FILES['arq_pg']['type'] == 'image/jpg' || $_FILES['arq_pg']['type'] == 'image/jpeg') {
            $arq_nome  = md5(date('Ymhis') . $id);
            $arq      = $arq_nome . '.' . pathinfo($_FILES['arq_pg']['name'], PATHINFO_EXTENSION);
            $arq_tmp   = $_FILES['arq_pg']['tmp_name'];
            move_uploaded_file($arq_tmp, "nf_pagar/" . $arq);
        } 
        /* fim arq */

        if ($competencia == 0) {
            $todasCompetencia =  $objFinanceiro->geraTodasCompetencia();
        } else {
            $todasCompetencia =  $objFinanceiro->geraCompetenciaApartir($competencia);
        }

        if ($token == $tokenForm) {

            foreach ($todasCompetencia as $key => $value) {
                $registro       = $objFinanceiro->geraRegistroConta();
                $dataValida     = checkdate($value['mes'], $diaVencimento, $value['ano']);

                if ($dataValida == true) {
                    $vencimento = $value['ano'] . '-' . $value['mes'] . '-' . $diaVencimento;
                    $returnRegistro[] =  $objFinanceiro->cadConta($registro, $usu, $unidade, $value['id'], $fornecedor, $doc, $categoria, $detalhe, 1, $arq);
                    $contaRegistro[] = $objFinanceiro->cadContaItem($registro, $vencimento, $valor);
                } else {
                    $ultimoDia = date("t", mktime(0, 0, 0, $value['mes'], '01', $value['ano']));
                    $vencimento = $value['ano'] . '-' . $value['mes'] . '-' . $ultimoDia;
                    $returnRegistro[] =  $objFinanceiro->cadConta($registro, $usu, $unidade, $value['id'], $fornecedor, $doc, $categoria, $detalhe, 1, $arq);
                    $contaRegistro[] = $objFinanceiro->cadContaItem($registro, $vencimento, $valor);
                }
            } //foreach

            $returnRegistro = in_array('0', $returnRegistro);
            $contaRegistro = in_array('0', $contaRegistro);

            if ($returnRegistro == false && $contaRegistro == false) {
                echo 1;
            } else {
                echo 2;
            }
        }
        break;
    case 34: /* Cadastro de conta variavel */
        /* 
                Tipos de retorno:
                0: Erro de autenticação
                1: Registrado com sucesso
                2: Erro de query
            */

        $token      =    $_SESSION['_token'];
        $tokenForm  =    $_POST['_token'];

        $usu = $_SESSION['usuario_id'];
        $unidade = $_SESSION['usuario_id_unidade'];

        $competencia = $_POST["competencia"];
        $doc = $_POST["num_doc"];
        $fornecedor = $_POST["fornecedor"];
        $categoria = $_POST["categoria"];
        $detalhe = $_POST["detalhe_desp"];
        $vencimento = $_POST["vencimento"];
        $valor = str_replace(',', '.', str_replace(".", "", str_replace("R$ ", "", $_POST['valor'])));
        $registro = $objFinanceiro->geraRegistroConta();
         /* arq */
         if (empty($_FILES['arq_pg']['name'])) {/* Sem Anexo */
           
         } else
         if ($_FILES['arq_pg']['type'] == 'application/pdf' ||  $_FILES['arq_pg']['type'] == 'image/jpg' || $_FILES['arq_pg']['type'] == 'image/jpeg') {
             $arq_nome  = md5(date('Ymhis') . $id);
             $arq      = $arq_nome . '.' . pathinfo($_FILES['arq_pg']['name'], PATHINFO_EXTENSION);
             $arq_tmp   = $_FILES['arq_pg']['tmp_name'];
             move_uploaded_file($arq_tmp, "nf_pagar/".$arq);
         } 
         /* fim arq */

        /*  if( $token == $tokenForm ){ */
        $returnRegistro =  $objFinanceiro->cadConta($registro, $usu, $unidade, $competencia, $fornecedor, $doc, $categoria, $detalhe, 2, $arq );
        $contaRegistro = $objFinanceiro->cadContaItem($registro, $vencimento, $valor);
        if ($returnRegistro == 1 && $contaRegistro == 1) {
            // echo 1;
        } else {
            //echo 2;
        }
        /*  }else{
                echo 0;
            } */
        break;
    case 35: /* financeiro baixa conta */
        $id =   intVal($_POST["id"]);
        !empty($_POST["valorPago"]) ==  true ? $valorPago = str_replace(',', '.', str_replace(".", "", str_replace("R$ ", "", $_POST['valorPago']))) : $valorPago = null;
        !empty($_POST["data"]) ==  true ? $data =  $_POST["data"] : $data = null;
        !empty($_POST["obs"]) ==  true ? $obs =  $_POST["obs"] : $obs = null;
        $tipoPagamento =  $_POST["tipoPagamento"];
        $cadUsu = $_SESSION['usuario_id'];

        if ($valorPago == null || $data == null) {
            echo "erro_01";
        } else {
            if (empty($_FILES['arq_pg']['name'])) {/* Sem Anexo */
                $objFinanceiro->regPagamento($id, $valorPago, $data, $obs, $cadUsu, $tipoPagamento, '*');
                if (intval($tipoPagamento) == 1) {
                    $rgValorTotal = $objFinanceiro->regPagamentoValorTotal($id);
                    $objFinanceiro->baixa($id, $rgValorTotal, $data, $obs);
                }
                echo 1;
            } else
            if ($_FILES['arq_pg']['type'] == 'application/pdf' ||  $_FILES['arq_pg']['type'] == 'image/jpg' || $_FILES['arq_pg']['type'] == 'image/jpeg') {
                $arq_nome  = md5(date('Ymhis') . $id);
                $arq      = $arq_nome . '.' . pathinfo($_FILES['arq_pg']['name'], PATHINFO_EXTENSION);
                $arq_tmp   = $_FILES['arq_pg']['tmp_name'];
                move_uploaded_file($arq_tmp, "comprovante_pg/" . $arq);
                $objFinanceiro->regPagamento($id, $valorPago, $data, $obs, $cadUsu, $tipoPagamento, "comprovante_pg/" . $arq);
                if (intval($tipoPagamento) == 1) {
                    $rgValorTotal = $objFinanceiro->regPagamentoValorTotal($id);
                    $objFinanceiro->baixa($id, $rgValorTotal, $data, $obs);
                }
                echo 1;
            } else {
                echo "erro_arquivo";
            }
        }
        break;
    case 36: /* Mudar valor da conta(fatura) a pagar */
        if (intval($_GET['tipo']) == 1) {
            $data      = $_POST['data'];
            $id         = $_POST["id"];
            $objFinanceiro->mudaDataContaApagar($id, $data);
            echo date('d/m/Y', strtotime($data));;
        } else
        if (intval($_GET['tipo']) == 2) {
            $valor      = str_replace(',', '.', str_replace(".", "", str_replace("R$ ", "", $_POST['valor'])));
            $id         = $_POST["id"];
            $objFinanceiro->mudarValorContaApagar($id, $valor);
            echo 'R$: ' . $valor;
        }


        break;
    case 37: /* Excluir conta(fatura) a pagar */
        echo $idExcluir = $_POST['id'];
        echo $objFinanceiro->excluir($idExcluir);
        break;
    case 38: /* Cadastro de Nota FAT */
        $competencia    = $_POST["competencia"];
        $empresa_post   = explode ("*", $_POST["empresa"]);
        $empresa        = $empresa_post[1];
        $cnpj           = $empresa_post[0];
        $unidade_post   = explode ("*", $_POST["unidade"]);
        $unidade        = $unidade_post[0];
        $oss            = $unidade_post[1];
        $desc           = $_POST["desc"];
        $dt_envio           = $_POST["dt_envio"];
        $qtd_exames     = $_POST["qtd_exames"];
        $valor           = $_POST["total"];
        $usu            = $_SESSION['usuario_id'];
        $idFatura       = $objFaturamento->cadFatura($competencia, $valor, $empresa, $cnpj, $desc, $usu, $unidade, $qtd_exames, $oss, $dt_envio );
        foreach ($_FILES['files']['name']  as $key => $value) {
            $inicio = $_POST["inicio"][$key];
            $final  = $_POST["final"][$key];
            $nf     = $_POST["nf"][$key];
            $valor  = $_POST["valor"][$key];
            if (empty($_FILES['files']['name'][$key])) {/* Sem Anexo  */
                $arq = '*';
            } else
            if ($_FILES['files']['type'][$key] == 'application/pdf' ||  $_FILES['files']['type'][$key] == 'image/jpg' || $_FILES['files']['type'][$key] == 'image/jpeg') {
                $arq_nome  = md5(date('Ymhis') . $key);
                $arq      = $arq_nome . '.' . pathinfo($_FILES['files']['name'][$key], PATHINFO_EXTENSION);
                $arq_tmp   = $_FILES['files']['tmp_name'][$key];
                move_uploaded_file($arq_tmp, "fat_arq/" .$arq);
            }
            $objFaturamento->cadFaturaItem($idFatura,$valor,$inicio,$final,$nf,"fat_arq/".$arq);
        }
        break;
    case 39:
        $id =   intVal($_POST["id"]);
        !empty($_POST["valorPago"]) ==  true ? $valorPago = str_replace(',', '.', str_replace(".", "", str_replace("R$ ", "", $_POST['valorPago']))) : $valorPago = null;
        !empty($_POST["data"]) ==  true ? $data =  $_POST["data"] : $data = null;
        !empty($_POST["obs"]) ==  true ? $obs =  $_POST["obs"] : $obs = null;
        $tipoPagamento =  $_POST["tipoPagamento"];
        $cadUsu = $_SESSION['usuario_id'];

        if ($valorPago == null || $data == null) {
            echo "erro_01";
        } else {
            if (empty($_FILES['arq_pg']['name'])) {/* Sem Anexo */
                $objFaturamento->registraPagamento($id, $valorPago, $data, $obs, $cadUsu, $tipoPagamento, '*');
                if (intval($tipoPagamento) == 1) {
                    $total = $objFaturamento->regPagamentoValorTotal($id);
                    $objFaturamento->baixa($id, $total);
                }
                echo 1;
            } else
            if ($_FILES['arq_pg']['type'] == 'application/pdf' ||  $_FILES['arq_pg']['type'] == 'image/jpg' || $_FILES['arq_pg']['type'] == 'image/jpeg') {
                $arq_nome  = md5(date('Ymhis') . $id);
                $arq      = $arq_nome . '.' . pathinfo($_FILES['arq_pg']['name'], PATHINFO_EXTENSION);
                $arq_tmp   = $_FILES['arq_pg']['tmp_name'];
                move_uploaded_file($arq_tmp, "faturamento/comprovante_nf/" . $arq);
                $objFaturamento->registraPagamento($id, $valorPago, $data, $obs, $cadUsu, $tipoPagamento, $arq);
                if (intval($tipoPagamento) == 1) {
                    $total = $objFaturamento->regPagamentoValorTotal($id);
                    $objFaturamento->baixa($id, $total);
                }
                echo 1;
            } else {
                echo "erro_arquivo";
            }
        }
        break;
    case 40:
        $objFaturamento->excluirNf($_POST['id'], $_POST['desc']);
        break;
}