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
            <h5>Cadastro de Conta Bancária</h5>
          </div>
            <div class="widget-content nopadding">

                <form action="<?php echo base_url('contas/adicionar'); ?>" id="formUsuario" method="post" class="form-horizontal" >
                  <div class="control-group">
                    <label for="nome" class="control-label">Nome do Banco: <span class="required">*</span></label>
                    <div class="controls">
                      <input id="nome" type="text" name="nome" value="<?php echo set_value('nome'); ?>">
                    </div>
                  </div>

                  <div class="control-group">
                    <label for="saldo" class="control-label">Saldo Inicial: <span class="required">*</span></label>
                    <div class="controls">
                      <input id="saldo" type="text" name="saldo" class="money" value="<?php echo set_value('saldo'); ?>" required>
                    </div>
                  </div>
                  <div class="control-group">
                    <div class="controls">
                      <a href="<?php echo base_url('contas');?>" class="btn btn-default">
                        <i class="icon-arrow-left"></i> Voltar
                      </a>
                      <button type="submit" class="btn btn-success" style="margin-left: 35px;">
                        <i class="icon-plus icon-white"></i> Adicionar
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
