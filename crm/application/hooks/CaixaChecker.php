<?php
class CaixaChecker {

  public function __construct()
  {
      $this->CI = &get_instance();
  }

  public function check()
  {
      $rota = $this->CI->uri->segment(1);

      switch ($rota) {
        case 'os':
        case 'vendas':
        case 'financeiro':
          $this->CI->load->model("caixasinternos_model");
          $caixa_aberto = $this->CI->caixasinternos_model->getCaixaAberto();
          // Caixa fechado... Não é possível iniciar vendas...
          if(!$caixa_aberto)
          {
            redirect();
          }
          break;

        default:
          break;
      }

  }

}
 ?>
