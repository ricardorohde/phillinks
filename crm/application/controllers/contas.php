<?php

class Contas extends CI_Controller {

  function __construct() {
    parent::__construct();
    if ((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))) {
      redirect('mapos/login');
    }
    if($this->session->userdata('nivel') < 99){
        $this->session->set_flashdata('error','Você não tem permissão para essa ação.');
        redirect('mapos');
    }

    $this->load->helper(array('form', 'codegen_helper'));
    $this->load->model('contas_model', '', TRUE);
  }

  function index(){
    redirect("contas/gerenciar");
	}

	function gerenciar(){
    $this->load->model('caixasinternos_model');
    $dados = array(
      "resultados"  => $this->contas_model->get(),
      "caixa_interno" => $this->caixasinternos_model->getCaixaAberto(),
      "view"        => "contas/gerenciar"
    );
   	$this->load->view('tema/topo', $dados);
  }

  function adicionar(){

    $alerta = array();

    if($this->input->post())
    {
      $this->load->library('form_validation');

      $this->form_validation->set_rules("nome", "NOME DO BANCO", "required|trim|xss_clean");
      $this->form_validation->set_rules("saldo", "SALDO INICIAL", "required|trim|xss_clean");
      $this->form_validation->set_error_delimiters("", "<br>");
      if($this->form_validation->run() === TRUE)
      {
        $conta = array(
          "nome" => $this->input->post("nome"),
          "saldo" => $this->input->post("saldo")
        );

        $cadastrou = $this->contas_model->create($conta);

        if ($cadastrou)
        {
          $this->session->set_flashdata('success','Usuário cadastrado com sucesso!');
          redirect(base_url('contas/adicionar'));
        }
        else
        {
          $alerta = array(
            "classe"  => "danger",
            "mensagem"  => "Ocorreu um erro."
          );
        }
      }
      else
      {
        $alerta = array(
          "classe"  => "danger",
          "mensagem"  => "<strong>Atenção!</strong><br>" .validation_errors()
        );

      }
    }

    $dados = array(
      "view"    => "contas/adicionar",
      "alerta"  => $alerta
    );

    $this->load->view('tema/topo', $dados);

  }

  function editar($id){
    $alerta = array();

    if($this->input->post())
    {
      $this->load->library('form_validation');

      $this->form_validation->set_rules("nome", "NOME DO BANCO", "required|trim|xss_clean");
      $this->form_validation->set_rules("status", "STATUS", "required|trim|xss_clean");
      $this->form_validation->set_error_delimiters("", "<br>");
      if($this->form_validation->run() === TRUE)
      {
        $conta = array(
          "nome" => $this->input->post("nome"),
          "status" => $this->input->post("status")
        );

        $atualizou = $this->contas_model->update($id, $conta);

        if ($atualizou)
        {
          $this->session->set_flashdata('success', 'Operação realizada com sucesso!');
          redirect(base_url('contas/editar/'. $id));
        }
        else
        {
          $alerta = array(
            "classe"  => "danger",
            "mensagem"  => "Ocorreu um erro."
          );
        }
      }
      else
      {
        $alerta = array(
          "classe"  => "danger",
          "mensagem"  => "<strong>Atenção!</strong><br>" .validation_errors()
        );
      }
    }

    $id = (int) $id;
    $conta = $this->contas_model->getById($id);
    $dados = array(
      "view"    => "contas/editar",
      "alerta"  => $alerta,
      "item"    => $conta
    );

    $this->load->view('tema/topo', $dados);

    /*

    $this->load->library('form_validation');
		$this->data['custom_error'] = '';
    $this->form_validation->set_rules('nome', 'Nome', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rg', 'RG', 'trim|required|xss_clean');
    $this->form_validation->set_rules('cpf', 'CPF', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rua', 'Rua', 'trim|required|xss_clean');
    $this->form_validation->set_rules('numero', 'Número', 'trim|required|xss_clean');
    $this->form_validation->set_rules('bairro', 'Bairro', 'trim|required|xss_clean');
    $this->form_validation->set_rules('cidade', 'Cidade', 'trim|required|xss_clean');
    $this->form_validation->set_rules('estado', 'Estado', 'trim|required|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
    $this->form_validation->set_rules('telefone', 'Telefone', 'trim|required|xss_clean');
    $this->form_validation->set_rules('situacao', 'Situação', 'trim|required|xss_clean');
    $this->form_validation->set_rules('nivel', 'Nível', 'trim|required|xss_clean');

    if ($this->form_validation->run() == false)
    {
      $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">'.validation_errors().'</div>' : false);
    }
    else
    {

      if ($this->input->post('idUsuarios') == 1 && $this->input->post('situacao') == 0)
      {
        $this->session->set_flashdata('error','O usuário super admin não pode ser desativado!');
        redirect(base_url().'index.php/usuarios/editar/'.$this->input->post('idUsuarios'));
      }

      $senha = $this->input->post('senha');
      if($senha != null){
        $this->load->library('encrypt');
        $senha = $this->encrypt->sha1($senha);

        $data = array(
          'nome' => $this->input->post('nome'),
          'rg' => $this->input->post('rg'),
          'cpf' => $this->input->post('cpf'),
          'rua' => $this->input->post('rua'),
          'numero' => $this->input->post('numero'),
          'bairro' => $this->input->post('bairro'),
          'cidade' => $this->input->post('cidade'),
          'estado' => $this->input->post('estado'),
          'email' => $this->input->post('email'),
          'senha' => $senha,
          'telefone' => $this->input->post('telefone'),
          'celular' => $this->input->post('celular'),
          'situacao' => $this->input->post('situacao'),
          'nivel' => $this->input->post('nivel')
        );
      }
      else
      {
        $data = array(
          'nome' => $this->input->post('nome'),
          'rg' => $this->input->post('rg'),
          'cpf' => $this->input->post('cpf'),
          'rua' => $this->input->post('rua'),
          'numero' => $this->input->post('numero'),
          'bairro' => $this->input->post('bairro'),
          'cidade' => $this->input->post('cidade'),
          'estado' => $this->input->post('estado'),
          'email' => $this->input->post('email'),
          'telefone' => $this->input->post('telefone'),
          'celular' => $this->input->post('celular'),
          'situacao' => $this->input->post('situacao'),
          'nivel' => $this->input->post('nivel')
        );
      }

			if ($this->contas_model->edit('usuarios',$data,'idUsuarios',$this->input->post('idUsuarios')) == TRUE)
			{
        $this->session->set_flashdata('success','Usuário editado com sucesso!');
				redirect(base_url().'index.php/usuarios/editar/'.$this->input->post('idUsuarios'));
			}
			else
			{
				$this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
			}
    }

    $this->data['result'] = $this->contas_model->getById($this->uri->segment(3));
		$this->data['view'] = 'usuarios/editarUsuario';
    $this->load->view('tema/topo', $this->data);
    */
  }

  function excluir(){
    $id = (int) $this->input->post("id");
    $this->contas_model->delete($id);
    redirect('contas/gerenciar');
  }
}
