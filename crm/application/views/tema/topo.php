
<!DOCTYPE html>
<html lang="en">
<head>
<title>Sistema Phillinks Empresa</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/matrix-style.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/matrix-media.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fullcalendar.css" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.min.js"></script>

</head>
<body>

<!--Header-part-->
<div id="header">
  <h1><a href="">Sistema OS</a></h1>
</div>
<!--close-Header-part-->

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li>
      <a title="Meu Perfil" href="<?php echo base_url(); ?>mapos/minhaConta">
        <i class="icon icon-star"></i> <span class="text">Meu Perfil</span>
      </a>
    </li>
    <li>
      <a title="Sair" href="<?php echo base_url(); ?>mapos/sair">
        <i class="icon icon-share-alt"></i> <span class="text">Sair</span>
      </a>
    </li>
  </ul>
</div>

<div id="search">
  <form action="<?php echo base_url(); ?>mapos/pesquisar">
    <input type="text" name="termo" placeholder="Pesquisar..."/>
    <button type="submit"  class="tip-bottom" title="Pesquisar"><i class="icon-search icon-white"></i></button>
  </form>
</div>

<?php
  // Configuração do menu atual...
  $menu = $this->uri->segment(1) ? $this->uri->segment(1) : "";
?>

<!--sidebar-menu-->
<div id="sidebar"> <a href="#" class="visible-phone"><i class="icon icon-list"></i> Menu</a>
  <ul>
    <li class="<?php if(!$menu){ echo 'active'; } ?>">
      <a href="<?php echo base_url(); ?>">
        <i class="icon icon-home"></i> <span>Página de início</span>
      </a>
    </li>
    <li class="<?php if($menu == "clientes"){ echo 'active'; } ?>">
      <a href="<?php echo base_url(); ?>clientes">
        <i class="icon icon-group"></i> <span>Cadastro de Clientes</span>
      </a>
    </li>
    <li class="<?php if($menu == "produtos"){ echo 'active'; } ?>">
      <a href="<?php echo base_url(); ?>produtos">
        <i class="icon icon-barcode"></i> <span>Cadastro de Produtos</span>
      </a>
    </li>
    <li class="<?php if($menu == "servicos"){ echo 'active'; } ?>">
      <a href="<?php echo base_url(); ?>servicos">
        <i class="icon icon-wrench"></i> <span>Tipo de Serviços</span>
      </a>
    </li>
    <li class="<?php if($menu == "os"){ echo 'active'; } ?>">
      <a href="<?php echo base_url(); ?>os">
        <i class="icon icon-tags"></i> <span>Ordens de Serviço</span>
      </a>
    </li>
    <li class="<?php if($menu == "vendas"){ echo 'active'; } ?>">
      <a href="<?php echo base_url(); ?>vendas">
        <i class="icon icon-shopping-cart"></i> <span>Cadastro de Vendas</span>
      </a>
    </li>
    <li class="submenu <?php if($menu == "financeiro"){ echo 'active open'; } ?>">
      <a href="#"><i class="icon icon-money"></i> <span>Financeiro</span> <span class="label"><i class="icon-chevron-down"></i></span></a>
      <ul>
        <li><a href="<?php echo base_url(); ?>financeiro/lancamentos">Lançamentos</a></li>
      </ul>
    </li>

    <?php
      // Área Restrita
      if($this->session->userdata('nivel') == 99) {
    ?>
        <!-- Relatórios -->
        <li class="submenu <?php if($menu == "relatorios"){ echo 'active open'; } ?>">
          <a href="#"><i class="icon icon-list-alt"></i> <span>Relatórios</span> <span class="label"><i class="icon-chevron-down"></i></span></a>
          <ul>
            <li><a href="<?php echo base_url(); ?>relatorios/clientes">Clientes</a></li>
            <li><a href="<?php echo base_url(); ?>relatorios/produtos">Produtos</a></li>
            <li><a href="<?php echo base_url(); ?>relatorios/servicos">Serviços</a></li>
            <li><a href="<?php echo base_url(); ?>relatorios/os">Ordens de Serviço</a></li>
            <li><a href="<?php echo base_url(); ?>relatorios/vendas">Vendas</a></li>
            <li><a href="<?php echo base_url(); ?>relatorios/financeiro">Financeiro</a></li>
          </ul>
        </li>

        <!-- Configurações -->
        <li class="submenu <?php if($menu == "mapos" || $menu == "usuarios" || $menu == "contas"){ echo 'active open'; } ?>">
          <a href="#"><i class="icon icon-cog"></i> <span>Configurações</span> <span class="label"><i class="icon-chevron-down"></i></span></a>
          <ul>
            <li><a href="<?php echo base_url(); ?>mapos/emitente">Emitente</a></li>
            <li><a href="<?php echo base_url(); ?>usuarios">Usuários</a></li>
            <li><a href="<?php echo base_url(); ?>contas">Contas Bancárias</a></li>
            <li><a href="<?php echo base_url(); ?>mapos/backup">Backup</a></li>
          </ul>
        </li>

    <?php
      }
    ?>
  </ul>
</div>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
      <a href="<?php echo base_url(); ?>" title="Dashboard" class="tip-bottom">
        <i class="icon-home"></i> Página de Início
      </a>
      <?php if($this->uri->segment(1) != null){ ?>
        <a href="<?php echo base_url($this->uri->segment(1)); ?>" class="tip-bottom" title="<?php echo ucfirst($this->uri->segment(1)); ?>">
          <?php echo ucfirst($this->uri->segment(1));?>
        </a>
        <?php if($this->uri->segment(2) != null){ ?>
          <a href="<?php echo base_url($this->uri->segment(1) . '/' . $this->uri->segment(2)); ?>" class="current tip-bottom" title="<?php echo ucfirst($this->uri->segment(2)); ?>">
            <?php echo ucfirst($this->uri->segment(2)); ?>
          </a>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
          <?php if($this->session->flashdata('error') != null){?>
            <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php echo $this->session->flashdata('error');?>
           </div>
          <?php }?>

          <?php if($this->session->flashdata('success') != null){?>
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php echo $this->session->flashdata('success');?>
           </div>
          <?php }?>

          <?php if(isset($view)){echo $this->load->view($view);}?>
      </div>
    </div>
  </div>
</div>
<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12">Sistema Phillinks Empresa</div>
</div>
<!--end-Footer-part-->


<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/matrix.js"></script>


</body>
</html>
