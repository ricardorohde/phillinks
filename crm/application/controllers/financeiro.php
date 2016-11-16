<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financeiro extends CI_Controller {

  public $bancos;

	public function __construct()
	{
		parent::__construct();
		if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))){
      redirect('mapos/login');
    }
    $this->load->model('financeiro_model','',TRUE);
    $this->data['menuFinanceiro'] = 'financeiro';
    $this->load->helper(array('codegen_helper'));
	}
	public function index(){
		$this->lancamentos();
	}

	public function lancamentos(){

		$where = '';
		$periodo = $this->input->get('periodo');
		$situacao = $this->input->get('situacao');

		// Busca das contas...
		$this->data["contas"] = $this->financeiro_model->get_contas();

		// busca lançamentos do dia
		if($periodo == null || $periodo == 'dia'){
			$where = array('data_vencimento' => date('Y-m-d'));

		} // fim lançamentos dia

		else
    {

			// busca lançamentos da semana
			if($periodo == 'semana'){
				$semana = $this->getThisWeek();

				if(! isset($situacao) || $situacao == 'todos'){

					$where = 'data_vencimento BETWEEN "'.$semana[0].'" AND "'.$semana[1].'"';

				}
				else{
					if($situacao == 'previsto'){
						$where = 'data_vencimento BETWEEN "'.date('Y-m-d').'" AND "'.$semana[1].'" AND baixado = "0"';
					}
					else{
						if($situacao == 'atrasado'){
							$where = 'data_vencimento BETWEEN "'.$semana[0].'" AND "'.date('Y-m-d').'" AND baixado = "0"';
						}
						else{
							$where = 'data_vencimento BETWEEN "'.$semana[0].'" AND "'.$semana[1].'" AND baixado = "1"';
						}
					}
				}

			} // fim lançamentos dia
			else{

				// busca lançamento do mês


				if($periodo == 'mes'){

					$mes = $this->getThisMonth();

					if(! isset($situacao) || $situacao == 'todos'){

						$where = 'data_vencimento BETWEEN "'.$mes[0].'" AND "'.$mes[1].'"';

					}
					else{
						if($situacao == 'previsto'){
							$where = 'data_vencimento BETWEEN "'.date('Y-m-d').'" AND "'.$mes[1].'" AND baixado = "0"';
						}
						else{
							if($situacao == 'atrasado'){
								$where = 'data_vencimento BETWEEN "'.$mes[0].'" AND "'.date('Y-m-d').'" AND baixado = "0"';
							}
							else{
								$where = 'data_vencimento BETWEEN "'.$mes[0].'" AND "'.$mes[1].'" AND baixado = "1"';
							}
						}
					}
				}

				// busca lançamentos do ano
				else{
					$ano = $this->getThisYear();

					if(! isset($situacao) || $situacao == 'todos'){

						$where = 'data_vencimento BETWEEN "'.$ano[0].'" AND "'.$ano[1].'"';

					}
					else{
						if($situacao == 'previsto'){
							$where = 'data_vencimento BETWEEN "'.date('Y-m-d').'" AND "'.$ano[1].'" AND baixado = "0"';
						}
						else{
							if($situacao == 'atrasado'){
								$where = 'data_vencimento BETWEEN "'.$ano[0].'" AND "'.date('Y-m-d').'" AND baixado = "0"';
							}
							else{
								$where = 'data_vencimento BETWEEN "'.$ano[0].'" AND "'.$ano[1].'" AND baixado = "1"';
							}
						}
					}
				}
			}
		}

		$this->load->library('pagination');
    $config['base_url'] = base_url().'financeiro/lancamentos';
    $config['total_rows'] = $this->financeiro_model->count('lancamentos');
    $config['per_page'] = 100;
    $config['next_link'] = 'Próxima';
    $config['prev_link'] = 'Anterior';
    $config['full_tag_open'] = '<div class="pagination alternate"><ul>';
    $config['full_tag_close'] = '</ul></div>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li><a style="color: #2D335B"><b>';
    $config['cur_tag_close'] = '</b></a></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $this->pagination->initialize($config);

    $this->data['results'] = $this->financeiro_model->get('lancamentos','id_conta, contas.nome, idLancamentos,descricao,valor,data_vencimento,data_pagamento,baixado,cliente_fornecedor,tipo,forma_pgto',$where,$config['per_page'],$this->uri->segment(3));
    $this->load->model("contas_model");
    $this->load->model("caixasinternos_model");
    $this->data['contas'] = $this->contas_model->get();
    $this->data['caixa_interno'] = $this->caixasinternos_model->getCaixaAberto();
    $this->data['view'] = 'financeiro/lancamentos';
    $this->load->view('tema/topo',$this->data);

	}

	function adicionarReceita() {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');
        if ($this->form_validation->run('receita') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {


            $vencimento = $this->input->post('vencimento');
            $recebimento = $this->input->post('recebimento');

            try {

                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2].'-'.$vencimento[1].'-'.$vencimento[0];

                $recebimento = explode('/', $recebimento);
                $recebimento = $recebimento[2].'-'.$recebimento[1].'-'.$recebimento[0];

            } catch (Exception $e) {
               $vencimento = date('Y/m/d');
            }

            $data = array(
              'descricao' => set_value('descricao'),
              'valor' => set_value('valor'),
              'data_vencimento' => $vencimento,
              'data_pagamento' => $recebimento,
              'baixado' => $this->input->post('recebido'),
              'cliente_fornecedor' => set_value('cliente'),
              'forma_pgto' => $this->input->post('formaPgto'),
              'id_conta' => $this->input->post('id_conta'),
              'tipo' => set_value('tipo')
            );

            if ($this->financeiro_model->add('lancamentos',$data) == TRUE) {
                if($this->input->post('id_conta') == 1)
                {
                  $this->load->model("caixasinternos_model");
                  $caixa_aberto = $this->caixasinternos_model->getCaixaAberto();

                  if($caixa_aberto)
                  {
                    if($this->input->post("recebido")){
                      $this->caixasinternos_model->incrementarSaldo($caixa_aberto["id"], $this->input->post('valor'));
                    }
                  }
                }
                else
                {
                  $this->load->model("contas_model");
                  $this->contas_model->incrementarSaldo($this->input->post('id_conta'), $this->input->post('valor'));
                }
                $this->session->set_flashdata('success','Receita adicionada com sucesso!');
                redirect($urlAtual);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->session->set_flashdata('error','Ocorreu um erro ao tentar adicionar receita.');
        redirect($urlAtual);

    }


    function adicionarDespesa() {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');
        if ($this->form_validation->run('despesa') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {

            $vencimento = $this->input->post('vencimento');
            $pagamento = $this->input->post('pagamento');

            try {

                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2].'-'.$vencimento[1].'-'.$vencimento[0];

                $pagamento = explode('/', $pagamento);
                $pagamento = $pagamento[2].'-'.$pagamento[1].'-'.$pagamento[0];

            } catch (Exception $e) {
               $vencimento = date('Y-m-d');
               $pagamento = date('Y-m-d');
            }

            $data = array(
              'descricao' => $this->input->post('descricao'),
              'valor' => $this->input->post('valor'),
              'data_vencimento' => $vencimento,
              'data_pagamento' => $pagamento,
              'baixado' => $this->input->post('pago'),
              'cliente_fornecedor' => $this->input->post('fornecedor'),
              'id_conta' => $this->input->post('id_conta'),
              'forma_pgto' => $this->input->post('formaPgto'),
              'tipo' => $this->input->post('tipo')
            );

            // Verificar o saldo antes de dar baixa..
            if($this->input->post('pago')){

                // Caixa Interno?
                if($this->input->post('id_conta') == 1)
                {
                  $this->load->model("caixasinternos_model");
                  $caixa_aberto = $this->caixasinternos_model->getCaixaAberto();

                  if($caixa_aberto)
                  {
                    $caixa_aberto["saldo"] = $caixa_aberto["saldo_inicial"] + $caixa_aberto["saldo_corrente"];

                    if($caixa_aberto["saldo"] >= $this->input->post('valor'))
                    {
                        $this->caixasinternos_model->decrementarSaldo($caixa_aberto["id"], $this->input->post('valor'));

                        if ($this->financeiro_model->add('lancamentos', $data) == TRUE) {
                          $this->session->set_flashdata('success','Despesa adicionada com sucesso!');
                          redirect($urlAtual);
                        }

                    }
                    else
                    {
                      $this->session->set_flashdata('error','Saldo insuficiente no Caixa Interno.');
                      redirect($urlAtual);
                    }
                  }
                  else
                  {
                    $this->session->set_flashdata('error','O Caixa Interno encontra-se fechado.');
                    redirect($urlAtual);
                  }
                }
                else
                {
                  $this->load->model("contas_model");
                  $conta = $this->contas_model->getById($this->input->post('id_conta'));

                  if($conta["saldo"] >= $this->input->post('valor'))
                  {
                    $this->contas_model->decrementarSaldo($this->input->post('id_conta'), $this->input->post('valor'));
                    if ($this->financeiro_model->add('lancamentos', $data) == TRUE) {
                      $this->session->set_flashdata('success','Despesa adicionada com sucesso!');
                      redirect($urlAtual);
                    }
                  }
                  else
                  {
                    $this->session->set_flashdata('error','Saldo insuficiente na conta <strong>'. $conta["nome"] .'</strong>.');
                    redirect($urlAtual);
                  }
                }
            }
            else
            {
              if ($this->financeiro_model->add('lancamentos', $data) == TRUE) {
                $this->session->set_flashdata('success','Despesa adicionada com sucesso!');
                redirect($urlAtual);
              }
            }
        }

        $this->session->set_flashdata('error','Ocorreu um erro ao tentar adicionar despesa.');
        redirect($urlAtual);


    }


    public function editar(){

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');

        $this->form_validation->set_rules('descricao', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('fornecedor', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('valor', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('vencimento', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('id_conta', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pagamento', '', 'trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {

            $vencimento = $this->input->post('vencimento');
            $pagamento = $this->input->post('pagamento');

            try {

                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2].'-'.$vencimento[1].'-'.$vencimento[0];

                $pagamento = explode('/', $pagamento);
                $pagamento = $pagamento[2].'-'.$pagamento[1].'-'.$pagamento[0];

            } catch (Exception $e) {
               $vencimento = date('Y/m/d');
            }

            $data = array(
                'descricao' => $this->input->post('descricao'),
                'valor' => $this->input->post('valor'),
                'data_vencimento' => $vencimento,
                'data_pagamento' => $pagamento,
                'baixado' => $this->input->post('pago'),
                'cliente_fornecedor' => $this->input->post('fornecedor'),
                'forma_pgto' => $this->input->post('formaPgto'),
                'id_conta' => $this->input->post('id_conta'),
                'tipo' => $this->input->post('tipo')
            );

            if ($this->financeiro_model->edit('lancamentos',$data,'idLancamentos',$this->input->post('id')) == TRUE) {
                $this->session->set_flashdata('success','lançamento editado com sucesso!');
                redirect($urlAtual);
            } else {
                $this->session->set_flashdata('error','Ocorreu um erro ao tentar editar lançamento!');
                redirect($urlAtual);
            }
        }

        $this->session->set_flashdata('error','Ocorreu um erro ao tentar editar lançamento.');
        redirect($urlAtual);

        $data = array(
                'descricao' => $this->input->post('descricao'),
                'valor' => $this->input->post('valor'),
                'data_vencimento' => $this->input->post('vencimento'),
                'data_pagamento' => $this->input->post('pagamento'),
                'baixado' => $this->input->post('pago'),
                'cliente_fornecedor' => set_value('fornecedor'),
                'forma_pgto' => $this->input->post('formaPgto'),
                'id_conta' => $this->input->post('id_conta'),
                'tipo' => $this->input->post('tipo')
            );
        print_r($data);

    }

    public function excluirLancamento()
    {
    	$id = $this->input->post('id');

    	if($id == null || ! is_numeric($id)){
    		$json = array('result'=>  false);
    		echo json_encode($json);
    	}
    	else{

    		$result = $this->financeiro_model->delete('lancamentos','idLancamentos',$id);
    		if($result){
    			$json = array('result'=>  true);
    			echo json_encode($json);
    		}
    		else{
    			$json = array('result'=>  false);
    			echo json_encode($json);
    		}

    	}
    }




	protected function getThisYear() {

        $dias = date("z");
        $primeiro = date("Y-m-d", strtotime("-".($dias)." day"));
        $ultimo = date("Y-m-d", strtotime("+".( 364 - $dias)." day"));
        return array($primeiro,$ultimo);

    }

    protected function getThisWeek(){

        return array(date("Y/m/d", strtotime("last sunday", strtotime("now"))),date("Y/m/d", strtotime("next saturday", strtotime("now"))));
    }

    protected function getLastSevenDays() {

        return array(date("Y-m-d", strtotime("-7 day", strtotime("now"))), date("Y-m-d", strtotime("now")));
    }

    protected function getThisMonth(){

        $mes = date('m');
        $ano = date('Y');
        $qtdDiasMes = date('t');
        $inicia = $ano."-".$mes."-01";

        $ate = $ano."-".$mes."-".$qtdDiasMes;
        return array($inicia, $ate);
    }

}
