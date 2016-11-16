<a href="<?php echo base_url('contas/adicionar');?>" class="btn btn-success pull-right">
  <i class="icon-plus icon-white"></i> Adicionar Conta Bancária
</a>
<a href="<?php echo base_url();?>" class="btn btn-default">
  <i class="icon-home"></i> Página Inicial
</a>
<div class="widget-box">
  <div class="widget-title">
    <span class="icon">
      <i class="icon-cog"></i>
     </span>
    <h5>Contas Bancárias</h5>
  </div>

  <div class="widget-content nopadding">
    <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nome do Banco</th>
            <th>Saldo</th>
            <th>Status</th>
            <!-- <th>Data Cadastro</th> -->
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <!-- Caixa Interno -->
          <tr>
            <td>
              Caixa Interno
            </td>
            <td>
              R$
              <?php if($caixa_interno){ ?>
               <?php echo number_format($caixa_interno["saldo_inicial"] + $caixa_interno["saldo_corrente"], 2, ',', '.'); ?>
              <?php } else { echo '0,00';}?>
            </td>
            <td>
              <?php echo $caixa_interno ? "Caixa Aberto" : "Caixa Fechado"; ?>
            </td>
            <!-- <td>
              --
            </td> -->
            <td>
              <center>

                --

              </center>
            </td>
          </tr>
          <?php if($resultados){ ?>
            <?php foreach ($resultados as $item) { ?>
              <?php if($item["id"] != 1){ ?>
                <tr>
                  <td>
                    <?php echo $item["nome"]; ?>
                  </td>
                  <td>
                    R$ <?php echo $item["saldo"]; ?>
                  </td>
                  <td>
                    <?php echo $item["status"] == 0 ? "Caixa Fechado" : "Caixa Aberto"; ?>
                  </td>
                  <!-- <td>
                    <?php echo date('d/m/Y à\s m:i', strtotime($item["data_cadastro"])); ?>
                  </td> -->
                  <td>
                    <center>

                      <!-- <a href="<?php echo base_url('contas/visualizar/'. $item["id"]); ?>" class="btn tip-top" title="Ver mais detalhes">
                        <i class="icon-eye-open"></i>
                      </a> -->
                      <a href="<?php echo base_url('contas/editar/'. $item["id"]); ?>" class="btn btn-info tip-top" title="Editar">
                        <i class="icon-pencil icon-white"></i>
                      </a>
                      <a href="#modal-excluir" role="button" data-toggle="modal" item="<?php echo $item["id"]; ?>" class="btn btn-danger tip-top excluir" title="Excluir">
                        <i class="icon-remove icon-white"></i>
                      </a>

                    </center>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade">
  <form action="<?php echo base_url('contas/excluir') ?>" method="post" >
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h5 id="myModalLabel">Excluir</h5>
  </div>
  <div class="modal-body">
    <input type="hidden" id="item_excluir" name="id" value="" />
    <h5 style="text-align: center">Por favor, confirme a sua ação.</h5>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
    <button class="btn btn-danger">Excluir</button>
  </div>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $(".excluir").click(function(){
      var item = $(this).attr('item');
      $("#item_excluir").val(item);
    });
  });
</script>
