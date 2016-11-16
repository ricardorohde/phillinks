<?php if ($alerta) {
  echo '<div class="alert alert-'.$alerta["classe"].'">' . $alerta["mensagem"] . '</div>';
} ?>

<a href="<?php echo base_url('contas');?>" class="btn btn-default">
  <i class="icon-arrow-left"></i> Voltar
</a>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon">
              <i class="icon-cog"></i>
            </span>
            <h5>Editar Conta Bancária</h5>
          </div>
            <div class="widget-content nopadding">
                <form action="<?php echo base_url('contas/editar/' . $this->uri->segment(3)); ?>" id="formUsuario" method="post" class="form-horizontal" >
                  <input type="hidden" name="id" value="<?php $item["id"]; ?>">
                  <div class="control-group">
                    <label for="nome" class="control-label">Nome do Banco: <span class="required">*</span></label>
                    <div class="controls">
                      <input id="nome" type="text" name="nome" value="<?php echo set_value('nome') ? set_value("nome") : $item["nome"]; ?>" required>
                    </div>
                  </div>

                  <div class="control-group">
                    <label for="saldo" class="control-label">Status:</label>
                    <div class="controls">
                      <select name="status" required id="saldo">
                        <option value="1" <?php if($item["status"] == 1) echo "selected"; ?>>Caixa Aberto</option>
                        <option value="0" <?php if($item["status"] == 0) echo "selected"; ?>>Caixa Fechado</option>
                      </select>
                    </div>
                  </div>

                  <!-- <div class="control-group">
                    <label for="saldo" class="control-label">Saldo:</label>
                    <div class="controls">
                      <input id="saldo" disabled="disabled" type="text" name="saldo" class="money" value="<?php echo $item["saldo"]; ?>">
                    </div>
                  </div> -->

                  <!-- <div class="control-group">
                    <label for="data_cadastro" class="control-label">Data de Cadastro:</label>
                    <div class="controls">
                      <input id="data_cadastro" disabled="disabled" type="text" name="data_cadastro" value="<?php echo date('d/m/Y à\s m:i', strtotime($item["data_cadastro"])); ?>">
                    </div>
                  </div> -->
                  <div class="control-group">
                    <div class="controls">
                      <a href="<?php echo base_url('contas');?>" class="btn btn-default">
                        <i class="icon-arrow-left"></i> Voltar
                      </a>
                      <button type="submit" class="btn btn-success" style="margin-left: 43px;">
                        <i class="icon-ok"></i> Concluir
                      </button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>js/maskmoney.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $(".money").maskMoney();
  });
</script>
