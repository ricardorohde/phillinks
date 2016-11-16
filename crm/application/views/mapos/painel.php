<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?php echo base_url();?>js/dist/excanvas.min.js"></script><![endif]-->

<script language="javascript" type="text/javascript" src="<?php echo base_url();?>js/dist/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/dist/jquery.jqplot.min.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/dist/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/dist/plugins/jqplot.donutRenderer.min.js"></script>


<!--Action boxes>
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <li class="bg_lb"> <a href="<?php echo base_url()?>index.php/clientes"> <i class="icon-group"></i> Clientes</a> </li>
        <li class="bg_lg"> <a href="<?php echo base_url()?>index.php/produtos"> <i class="icon-barcode"></i> Produtos</a> </li>
        <li class="bg_ly"> <a href="<?php echo base_url()?>index.php/servicos"> <i class="icon-wrench"></i> Serviços</a> </li>
        <li class="bg_lo"> <a href="<?php echo base_url()?>index.php/os"> <i class="icon-tags"></i> OS</a> </li>
        <li class="bg_ls"> <a href="<?php echo base_url()?>index.php/vendas"><i class="icon-shopping-cart"></i> Vendas</a></li>

      </ul>
    </div>
  </div>
<End-Action boxes-->

<div class="row-fluid" style="margin-top: 0">

    <div class="span12">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-money"></i></span><h5>Situação do Caixa Interno</h5></div>
            <div class="widget-content">
                <?php if($this->session->flashdata('mensagem') != null){?>
                    <div class="alert alert-<?php echo $this->session->flashdata('classe') ?>">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <?php echo $this->session->flashdata('mensagem');?>
                    </div>
                <?php } ?>
                    <?php
                        if($caixa_diario)
                        {
                    ?>
                          <div class="alert alert-success">
                              <p>
                                Caixa Aberto!
                                <br><br>
                                <strong>DATA:</strong> <?php echo date('d/m/Y à\s H:i', strtotime($caixa_diario["data_abertura"])); ?><br>
                                <strong>SALDO INICIAL:</strong> R$ <?php echo number_format($caixa_diario["saldo_inicial"], 2, ',', '.'); ?><br>
                                <strong>SALDO CORRENTE:</strong> R$ <?php echo number_format($caixa_diario["saldo_corrente"], 2, ',', '.'); ?><br>
                                ---------<br>
                                <strong>SALDO TOTAL:</strong> R$ <?php echo number_format($caixa_diario["saldo_inicial"] + $caixa_diario["saldo_corrente"], 2, ',', '.'); ?><br>
                              </p>
                              <a href="<?php echo base_url("mapos/fecharCaixa/". $caixa_diario["id"]); ?>" class="btn btn-success" onclick="return confirm('Deseja realmente fechar o caixa? O caixa será fechado!');">FECHAR CAIXA</a>
                          </div>
                    <?php
                        }
                        else
                        {
                    ?>
                          <div class="alert alert-danger">
                              <p>
                                <strong>CAIXA FECHADO!</strong><br>
                                - Para efetuar vendas e lançamentos é preciso abrir o caixa interno primeiro.<br>
                              </p>
                          </div>

                          <form action="<?php echo base_url("mapos/abrirCaixa"); ?>" method="post">
                            <div class="row" style="margin-left: 0px !important;">
                              <div class="span2">
                                  <label for="caixa_valor">Valor em Caixa:<span class="required">*</span></label>
                                  <input id="caixa_valor" class="span12" type="text" name="caixa_valor" required>
                              </div>
                              <div class="span2" style="margin-top: 25px;">
                                  <button type="submit" class="btn btn-success">ABRIR CAIXA</button>
                              </div>
                            </div>
                          </form>
                    <?php
                        }
                    ?>
            </div>
        </div>
    </div>

    <div class="span12" style="margin-left: 0">

        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-signal"></i></span><h5>Produtos Com Estoque Mínimo</h5></div>
            <div class="widget-content">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produto</th>
                            <th>Preço de Venda</th>
                            <th>Estoque</th>
                            <th>Estoque Mínimo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($produtos != null){
                            foreach ($produtos as $p) {
                                echo '<tr>';
                                echo '<td>'.$p->idProdutos.'</td>';
                                echo '<td>'.$p->descricao.'</td>';
                                echo '<td>R$ '.$p->precoVenda.'</td>';
                                echo '<td>'.$p->estoque.'</td>';
                                echo '<td>'.$p->estoqueMinimo.'</td>';
                                echo '<td> <a href="'.base_url().'index.php/produtos/editar/'.$p->idProdutos.'" class="btn btn-info"> <i class="icon-pencil" ></i> </a></td>';
                                echo '</tr>';
                            }
                        }
                        else{
                            echo '<tr><td colspan="3">Nenhum produto com estoque baixo.</td></tr>';
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="span12" style="margin-left: 0">

        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-signal"></i></span><h5>Ordens de Serviço Em Aberto</h5></div>
            <div class="widget-content">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data Inicial</th>
                            <th>Data Final</th>
                            <th>Cliente</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($ordens != null){
                            foreach ($ordens as $o) {
                                echo '<tr>';
                                echo '<td>'.$o->idOs.'</td>';
                                echo '<td>'.date('d/m/Y' ,strtotime($o->dataInicial)).'</td>';
                                echo '<td>'.date('d/m/Y' ,strtotime($o->dataFinal)).'</td>';
                                echo '<td>'.$o->nomeCliente.'</td>';
                                echo '<td> <a href="'.base_url().'index.php/os/visualizar/'.$o->idOs.'" class="btn"> <i class="icon-eye-open" ></i> </a></td>';
                                echo '</tr>';
                            }
                        }
                        else{
                            echo '<tr><td colspan="3">Nenhuma OS em aberto.</td></tr>';
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="row-fluid" style="margin-top: 0">

    <div class="span12">

        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-signal"></i></span><h5>Estatísticas do Sistema</h5></div>
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="site-stats">
                            <li class="bg_lh"><i class="icon-group"></i> <strong><?php echo $this->db->count_all('clientes');?></strong> <small>Clientes</small></li>
                            <li class="bg_lh"><i class="icon-barcode"></i> <strong><?php echo $this->db->count_all('produtos');?></strong> <small>Produtos </small></li>
                            <li class="bg_lh"><i class="icon-tags"></i> <strong><?php echo $this->db->count_all('os');?></strong> <small>Ordens de Serviço</small></li>
                            <li class="bg_lh"><i class="icon-wrench"></i> <strong><?php echo $this->db->count_all('servicos');?></strong> <small>Serviços</small></li>

                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php /*if($os != null){ ?>
<div class="row-fluid" style="margin-top: 0">

    <div class="span12">

        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="icon-signal"></i></span><h5>Estatísticas de OS</h5></div>
            <div class="widget-content">
                <div class="row-fluid">
                    <div class="span12">
                      <div id="chart-os" style=""></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php }*/ ?>

<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/maskmoney.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
      $("#caixa_valor").maskMoney();
  });
</script>

<?php if($os != null) {?>
<script type="text/javascript">
    /*
    $(document).ready(function(){

      var data = [
        <?php foreach ($os as $o) {
            echo "['".$o->status."', ".$o->total."],";
        } ?>

      ];
      var plot1 = jQuery.jqplot ('chart-os', [data],
        {
          seriesDefaults: {
            // Make this a pie chart.
            renderer: jQuery.jqplot.PieRenderer,
            rendererOptions: {
              // Put data labels on the pie slices.
              // By default, labels show the percentage of the slice.
              showDataLabels: true
            }
          },
          legend: { show:true, location: 'e' }
        }
      );
    });
    */
</script>

<?php } ?>
