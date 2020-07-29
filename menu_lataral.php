<?php
require_once('validar/class/db.class.php');
$objDb = new db();
$link = $objDb->conecta_mysql();

?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-6 sidebar-dark-pink">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="WISE - SISTEMAS" class="brand-image img-circle elevation-5" style="opacity: .8">
        <span class="brand-text font-weight-light">WISE - SISTEMAS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar elevation-3">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image my-1">
                <img src="dist/img/<?php echo$_SESSION['usuario_avatar'];?>.png" class="img-circle elevation-3" alt="User Image" id="avatar">
            </div>
            <div class="info ">
                <a href="#" class="d-block elevation"><?php echo $_SESSION['usuario_nome']; ?></a>
                <a href="#" class="d-block" style="font-size: 9px;" ><span><?php echo $_SESSION['usuario_nome_unidade']; ?></span></a>
            </div>
          
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 elevation-3">
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
                <!-- MENU ESTOQUE -->
                <li class="nav-item has-treeview estoque estoque_acertos estoque_admin ">
                    <a href="#" class="nav-link active  bg-info ">
                        <i class="fas fa-cubes " style="font-size: 20px"></i>
                        <p style="margin-left: 5px;">
                            ESTOQUE
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                     <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="etq_inventario.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="set_invenratio"></i>
                                <p>Inventário</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview estoque_acertos">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit "></i>
                                <p>
                                    Acertos
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="etq_acerto_entrada.php" class="nav-link">
                                    <i class="far fa-circle nav-icon" id="set_etq_acerto_entrada"></i>
                                        <p> Entrada</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="etq_acerto_saida.php" class="nav-link">
                                    <i class="far fa-circle nav-icon" id="set_etq_acerto_saida"></i>
                                        <p> Saída</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="etq_saldo.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="set_etq_saldo"></i>
                                <p>Saldo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="etq_baixa.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="set_etq_baixa"></i>
                                <p>Baixa</p>
                            </a>
                        </li>
                        <!-- Administrativo Eetoque -->
                        <li class="nav-item has-treeview estoque_admin">
                            <a href="#" class="nav-link <?php if(intval($_SESSION['usuario_admin_estoque']) == 1){}else{echo 'disabled';} ?>"  >
                                <i class="fas fa-user-lock text-danger"></i>
                                <p>
                                    Administrativo
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="etq_admin_relatorio_transacoes.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="rel_admin_rensacao_unidade"></i>
                                        <p>Relatório Transações</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="etq_admin_saldo_unidades.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="rel_saldo_unidade"></i>
                                        <p> Saldo Unidade</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- FIM MENU ESTOQUE -->
                <!-- MENU REQUISIÇÃO -->
            <li class="nav-item has-treeview active atender_pedido requisicao_usuario req_adm">
                    <a href="#" class="nav-link bg-warning elevation-3">
                        <i class="fas fa-file-alt" style="font-size: 20px"></i>
                        <p style="margin-left: 8px;">
                            REQUISIÇÃO
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="etq_requisicao.php" class="nav-link" >
                                <i class="far fa-circle nav-icon" id="set_requisicao_usuario"></i>
                                <p>Nova Requisição</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="etq_consulta_req.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="req_saldo"></i>
                                <p>Consultar Requisição</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview atender_pedido req_adm">
                            <a href="#" class="nav-link <?php if(intval($_SESSION['usuario_unidade_central']) == 1 && intval($_SESSION['usuario_admin_estoque']) == 1){}else{echo 'disabled';} ?>">
                                <i class="fas fa-user-lock text-danger"></i>
                                <p>Administrativo</p> 
                                <i class="fas fa-angle-left right text-danger"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="folha_transporte.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="folha_transporte"></i>
                                        <p>Folha de Transporte</p>
                                        <i class="fas fa-level-down-alt right text-danger"></i>
                                    </a>
                                </li>
                                <li class="nav-item has-treeview atender_pedido">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit text-danger"></i>
                                <p>
                                    Atender Requisição
                                    <i class="fas fa-angle-left right text-danger"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                               <!-- Tipo de requisições -->
                               <?php
                                    $sql = mysqli_query($link, "SELECT * FROM cad_familia");
                                    while ( $familia = mysqli_fetch_array($sql)) {
                               ?>
                                <li class="nav-item">
                                    <a href="req_atender.php<?php echo'?id='.$familia['id'].'&class=CLASS_'.$familia['familia']; ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger <?php echo'CLASS_'.$familia['familia']; ?>"></i>
                                        <p><?php echo ucfirst(strtolower($familia['familia'])); ?></p>
                                        <i class="fas fa-level-down-alt right text-danger"></i>
                                    </a>
                                </li>
                                <?php } ?>
                                <!-- Final Tipo de requisições -->
                                <li class="nav-item">
                                    <a href="req_atender.php?id=0&class=0" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="em-espera"></i>
                                        <p>Em Espera</p>
                                        <i class="fas fa-level-down-alt right text-danger"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                            </ul>
                            <ul class="nav nav-treeview">
                        <li class="nav-item has-treeview  text-danger req_adm_relatorio" style="padding-left: 5px;">
                            <a href="#" class="nav-link ">
                                <i class="far fa-file-alt  text-danger" style="font-size: 18px;"></i>
                                <p style="padding-left: 9px;">
                                    Relatório
                                    <i class="fas fa-angle-left right  text-danger"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="req_admin_rel_ver_requisicoes.php" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-danger" id="req_admin_rel_ver_requisicoes"></i>
                                        <p>Requisições</p>
                                        <i class="fas fa-level-down-alt right  text-danger"></i>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="req_admin_rel_item_unidade.php" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-danger" id="req_admin_rel_item_unidade"></i>
                                        <p>Item X Unidade</p>
                                        <i class="fas fa-level-down-alt right  text-danger"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="rel_unidade_x_item.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="rel_unidade_x_item"></i>
                                        <p>Unidade X Item</p>
                                        <i class="fas fa-level-down-alt right text-danger"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="req_saldo_unidades.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger req_adm" id="req_saldo_unidades"></i>
                                        <p>Saldo Unidade</p>
                                        <i class="fas fa-level-down-alt right text-danger"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                        </li>
                    </ul>
                </li>

           
            <!-- FIM MENU REQUISIÇÃO -->
                <!-- MENU ESTOQUE -->
                <!-- MENU COMPRAS -->
                <li class="nav-item has-treeview menu_compras">
                    <a href="#" class="nav-link active  bg-orange elevation-3 ">
                        <i class="fas fa-shopping-cart" style="font-size: 20px"></i>
                        <p>
                            COMPRAS
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ">
                            <a href="cpr_lista_compra.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="cpr_lista_compra"></i>
                                <p>Lista de Compras</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item has-treeview menu_compras">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit "></i>
                                <p>
                                    Administrativo
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="cpr_gerenciar_lista_compra.php" class="nav-link">
                                        <i class="far fa-circle nav-icon" id="cad_nfe_manual"></i>
                                        <p>Pedido de Compras</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="cpr_enviar_pedido.php" class="nav-link">
                                        <i class="far fa-circle nav-icon" id=""></i>
                                        <p>Enviar Pedido Fornecedor</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link">
                                        <i class="far fa-circle nav-icon" id=""></i>
                                        <p>Consulta Pedido</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item has-treeview menu_compras">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit "></i>
                                <p>
                                    Cadastro NF
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="cpr_cad_manual.php" class="nav-link">
                                        <i class="far fa-circle nav-icon" id="cad_nfe_manual"></i>
                                        <p>Manual</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="cpr_cad_xml.php" class="nav-link">
                                        <i class="far fa-circle nav-icon" id=""></i>
                                        <p>Com XML</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link">
                                        <i class="far fa-circle nav-icon" id=""></i>
                                        <p>Consulta XML</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- FIM MENU COMPRAS -->
                <!-- MENU COMPRAS -->
                <li class="nav-item has-treeview rh_dp">
                    <a href="#" class="nav-link active  bg-pink elevation-3">
                        <i class="fas fa-people-arrows" style="font-size: 20px"></i>
                        <p>
                            DEP. PESSOAL
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <!-- menu  dp -->
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="rh_dp_cad_extra.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="rh_dp_cad_extra"></i>
                                <p>Novo Extra</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview rh_dp_relatorio">
                            <a href="#" class="nav-link ">
                                <i class="far fa-file-alt f15c" style="font-size: 20px"></i>
                                <p>
                                    Relatórios
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="rh_dp_relatorio_extras.php" class="nav-link">
                                    <i class="far fa-circle nav-icon" id="rh_do_relatorio_extras"></i>
                                        <p> Relatório de Extras</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Administrativo Eetoque -->
                        <li class="nav-item has-treeview rh_dp_admin">
                            <a href="#" class="nav-link <?php if(intval($_SESSION['usuario_admin_master']) == 1 || intval($_SESSION['usuario_admin_rh_dp']) == 1){}else{echo 'disabled';} ?>">
                                <i class="fas fa-user-lock text-danger"></i>
                                <p>
                                    Administrativo
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview ">
                                <li class="nav-item">
                                    <a href="rh_dp_gerenciar_extras.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="rh_dp_gerenciar_extras"></i>
                                        <p>Aprovar Extras</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="rh_dp_gerar_arquivo_sispag.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="rh_dp_gerar_arquivo_sispag"></i>
                                        <p>Gerar Arquivo Pagamento</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="rh_dp_atualizar_extras.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="rh_dp_atualizar_exreas"></i>
                                        <p>Atualizar Extras</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="rh_dp_conf_dt_min.php" class="nav-link">
                                    <i class="far fa-circle nav-icon text-danger" id="rh_dp_config_dt_min"></i>
                                        <p>Configurar Data Minima</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                    </ul>
                    <!-- menu dp -->
                   
                </li>
                <!-- FIM MENU COMPRAS -->
                 <!-- MENU COMPRAS -->
                 <li class="nav-item has-treeview financeiro">
                    <a href="#" class="nav-link active  bg-lightblue  elevation-3 ">
                        <i class="fas fa-file-invoice-dollar" style="font-size: 20px"></i>
                        <p style="margin-left: 8px;">
                            FINANCEIRO
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                            <a href="fin_conta_fixa.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="fin_conta_fixa"></i>
                                <p>Conta Fixa</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="fin_conta_varial.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="fin_conta_varial"></i>
                                <p>Conta Variável</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="contas_pagar.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="contas_pagar"></i>
                                <p>Controle de Pagamento</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- FIM MENU COMPRAS -->
                <!-- MENU COMPRAS -->
                <li class="nav-item has-treeview faturamento">
                    <a href="#" class="nav-link active  bg-blue elevation-3 ">
                        <i class="fas fa-hand-holding-usd " style="font-size: 20px"></i>
                        <p>
                            FATURAMENTO
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ">
                            <a href="fat_registro.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="fat_registro"></i>
                                <p>Registrar Faturamento</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="fat_controle.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="fat_controle"></i>
                                <p>Controle de Recebimento</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- FIM MENU COMPRAS -->
                <!-- MENU COMPRAS -->
                <li class="nav-item has-treeview cmd_chamado">
                    <a href="#" class="nav-link active   bg-warning elevation-3 ">
                        <i class="fas fa-headset" style="font-size: 20px"></i>
                        <p>
                            CHAMADO TI
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                            <a href="cmd_chamados.php" class="nav-link">
                                <i class="far fa-circle nav-icon" id="cmd_chamados"></i>
                                <p>Chamados</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item has-treeview cmd_chamado_adm">
                            <a href="#" class="nav-link <?php if(intval($_SESSION['usuario_admin_master']) == 1 || intval($_SESSION['usuario_admin_ti']) == 1){}else{echo 'disabled';} ?>">
                                <i class="fas fa-user-lock text-danger"></i>
                                <p class="text-danger">
                                    Administrativo
                                    <i class="fas fa-angle-left right text-danger"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview ">
                                <li class="nav-item ">
                                    <a href="cmd_adm_atender.php" class="nav-link">
                                        <i class="far fa-circle nav-icon text-danger" id="cmd_adm_atender"></i>
                                        <p class="text-danger">Atender Chamado</p>
                                        <i class="fas fa-level-down-alt right text-danger"></i>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="cmd_adm_todos_chamados.php" class="nav-link">
                                        <i class="far fa-circle nav-icon text-danger" id="cmd_adm_todos_chamados"></i>
                                        <p class="text-danger">Todos Chamado</p>
                                        <i class="fas fa-level-down-alt right text-danger"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- FIM MENU COMPRAS -->
                <!-- MENU COMPRAS -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link active bg-indigo elevation-3">
                        <i class="fas fa-barcode" style="font-size: 20px"></i>
                        <p>
                            PATRIMÔNIO
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                   
                </li>
                <!-- FIM MENU COMPRAS -->
                <!-- MENU COMPRAS -->
                <!-- MENU COMPRAS -->
                <li class="nav-item has-treeview util-relatorio">
                    <a href="#" class="nav-link active bg-olive elevation-3">
                        <i class="fas fa-hands-helping" style="font-size: 20px"></i>
                        <p>
                            ÚTEIS
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item has-treeview util-relatorio">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit "></i>
                                <p>
                                    Relatórios
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="util_rel_tabela_exames.php" class="nav-link">
                                        <i class="far fa-circle nav-icon" id="util_rel_tabela_exames"></i>
                                        <p>Tabelas de Exames</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- FIM MENU COMPRAS -->
                 <!-- MENU COMPRAS -->
                 <li class="nav-item has-treeview parametrizacao ">
                    <a href="#" class="nav-link active  bg-red elevation-3 ">
                        <i class="fas fa-user-lock" style="font-size: 20px"></i>
                        <p>
                            PARAMETRIZAÇÃO
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <!-- menu  dp -->
                    <ul class="nav nav-treeview ">
                        <li class="nav-item has-treeview cadastros ">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Cadastros
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="cad_unidade.php" class="nav-link">
                                    <i class="far fa-circle nav-icon" id=""></i>
                                        <p>Unidade</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="cad_item.php" class="nav-link">
                                    <i class="far fa-circle nav-icon" id="prmt_cad_item"></i>
                                        <p>Item Estoque</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                                </li>
                            </ul>
                          <!--  -->
                        <li class="nav-item has-treeview prmt_estoque">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit "></i>
                                <p>
                                    Estoque
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="prmt_ativar_item_unidade.php" class="nav-link">
                                    <i class="far fa-circle nav-icon" id="prmt_ativar_item_unidade"></i>
                                        <p>Ativar Item em Unidade</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                            <!--  -->
                             <!--  -->
                        <li class="nav-item has-treeview cadastros_rd_do">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-edit "></i>
                                <p>
                                    DEP. PESSOAL
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="prmt_cad_funcionario.php" class="nav-link">
                                    <i class="far fa-circle nav-icon" id="prmt_cad_funcionario"></i>
                                        <p>Cadastro de Funcionario</p>
                                        <i class="fas fa-level-down-alt right"></i>
                                    </a>
                                </li>
                            </ul>
                            <!--  -->
                            
                        </li>
                </li>
                <!-- FIM MENU COMPRAS -->

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
                              
<script>
    $(document).ready( function(){
        $("#avatar").click( function(){
            $('#mudar_avatar').modal("show")
        });
        $("#atualiza_avatar").click( function(){
           var form = $("#form_avatar").serialize();
           $.ajax({
               url: "validar/validar.php?id=17",
               type: "POST",
               data: form,
               success: function(data){
                location.reload();
               }
           })
        });

        
    });
</script>

