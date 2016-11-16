<!DOCTYPE html>
<html lang="pt-br">    
    <head>
        <title>Sistema Phillinks Empresa</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo base_url('assets/bootstrap-3.3.6/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/css/matrix-login.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/font-awesome-4.6.3/css/font-awesome.min.css'); ?>" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' type='text/css'>
        <script src="<?php echo base_url('js/jquery-1.10.2.min.js'); ?>"></script>
    </head>
    <body>
        <div class="container">

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <center>
                        <img src="<?php echo base_url('assets/img/logo.png')?>" alt="Logo">
                    </center>
                    <hr>
                </div>
            </div>

            <?php if($this->session->flashdata('error') != null){?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo $this->session->flashdata('error');?>
                       </div>
                    </div>
                </div>
            <?php } ?>
            
            <div class="row">
                <div class="col-md-4 col-md-offset-4">                
                    <form action="<?php echo base_url('mapos/verificarLogin'); ?>" method="post" id="formLogin">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-addon" id="email">
                                  <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                </span>
                                <input id="email" name="email" type="email" class="form-control input-lg" placeholder="E-mail" aria-describedby="email" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <div class="input-group">
                                <span class="input-group-addon" id="senha">
                                  &nbsp;<i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                </span>
                                <input id="senha" name="senha" type="password" class="form-control input-lg" placeholder="Senha" aria-describedby="senha" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg pull-right">Logar</button>
                    </form> 
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <br><br>&nbsp;
                    <hr>
                    <p class="text-muted text-center">
                      Copyright &copy; <?php echo date('Y'); ?> - Phillinks
                    </p>
                </div>
            </div>
        </div> <!-- Fim do container -->
        
        
        <script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url()?>js/jquery.validate.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#email').focus();
                $("#formLogin").validate({
                    rules :{
                        email: { required: true, email: true},
                        senha: { required: true}
                    },
                    messages:{
                        email: { required: 'Campo Requerido.', email: 'Insira Email válido'},
                        senha: {required: 'Campo Requerido.'}
                    },
                    submitHandler: function( form ){       
                        var dados = $( form ).serialize();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('mapos/verificarLogin?ajax=true');?>",
                            data: dados,
                            dataType: 'json',
                            success: function(data)
                            {
                                if(data.result == true){
                                    window.location.href = "<?php echo base_url('mapos');?>";
                                }
                                else{
                                    $('#modal').modal();
                                }
                            }
                        });
                        return false;
                    },

                    errorClass: "help-inline",
                    errorElement: "span",
                    highlight:function(element, errorClass, validClass) {
                        $(element).parents('.input-group').addClass('has-error');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).parents('.input-group').removeClass('has-error');
                        $(element).parents('.input-group').addClass('has-success');
                    }
                });

            });
        </script>

        <div id="notification" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="myModalLabel">Sistema Speedy System</h4>
            </div>
            <div class="modal-body">
                <h5 style="text-align: center">Os dados de acesso estão incorretos, por favor tente novamente!</h5>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
        
        <div id="modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Sistema Speedy System</h4>
                    </div>
                    <div class="modal-body">
                        <p>Os dados de acesso estão incorretos, por favor tente novamente!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </body>

</html>









