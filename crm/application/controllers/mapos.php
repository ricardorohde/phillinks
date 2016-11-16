<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mapos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mapos_model');
    }

    public function index()
    {

        if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))){
            redirect('mapos/login');
        }

        $this->load->model("caixasinternos_model");
        $this->data['caixa_diario'] = $this->caixasinternos_model->getCaixaAberto();

        $this->data['ordens'] = $this->mapos_model->getOsAbertas();
        $this->data['produtos'] = $this->mapos_model->getProdutosMinimo();
        $this->data['os'] = $this->mapos_model->getOsEstatisticas();
        $this->data['menuPainel'] = 'Painel';
        $this->data['view'] = 'mapos/painel';
        $this->load->view('tema/topo',  $this->data);

    }

    public function abrirCaixa()
    {

        $logado = $this->session->userdata('logado');

        if(!$logado){
            redirect('mapos/login');
        }

        $mensagem = array();

        if($this->input->post())
        {
            $this->load->library("form_validation");
            $this->form_validation->set_rules("caixa_valor", "VALOR EM CAIXA", "required");

            if($this->form_validation->run() === TRUE)
            {
                $this->load->model("caixasinternos_model");

                $caixa_aberto = $this->caixasinternos_model->getCaixaAberto();

                if(!$caixa_aberto)
                {
                  $caixa = array(
                    "saldo_inicial" => $this->input->post("caixa_valor")
                  );

                  $abriu = $this->caixasinternos_model->abrirCaixa($caixa);

                  if($abriu){
                    $mensagem = array(
                      "classe"    => "success",
                      "mensagem"  => "Operação realizada com sucesso."
                    );
                  }
                  else
                  {
                    $mensagem = array(
                      "classe"    => "danger",
                      "mensagem"  => "Atenção, não é possivel realizar esta operação no momento. Tente novamente mais tarde."
                    );
                  }
                }
                else
                {
                  $mensagem = array(
                    "classe"    => "warning",
                    "mensagem"  => "Atenção, o caixa já está aberto..."
                  );
                }


            }
            else
            {
                $mensagem = array(
                    "classe"    => "danger",
                    "mensagem"  => "<strong>Atenção</strong>!<br>" . validation_errors()
                );
            }
        }

        $this->session->set_flashdata($mensagem);
        redirect();

    }

    public function fecharCaixa()
    {
        if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))){
            redirect('mapos/login');
        }

        $mensagem = NULL;

        $this->load->library("form_validation");

        $update = array(
            "status"  => 0,
        );

        $where = array(
            "id"  => (int) $this->uri->segment(3)
        );

        // Atualiza os dados no banco
        $this->db->update("caixas_internos", $update, $where);

        $mensagem = array(
            "classe"    => "success",
            "mensagem"  => "Operação realizada com sucesso!"
        );

        $this->session->set_flashdata($mensagem);
        redirect();

    }

    public function minhaConta()
    {

        if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))){
            redirect('mapos/login');
        }

        $this->data['usuario'] = $this->mapos_model->getById($this->session->userdata('id'));
        $this->data['view'] = 'mapos/minhaConta';
        $this->load->view('tema/topo', $this->data);

    }

    public function alterarSenha()
    {

        if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))){
            redirect('mapos/login');
        }

        $oldSenha = $this->input->post('oldSenha');
        $senha = $this->input->post('novaSenha');
        $result = $this->mapos_model->alterarSenha($senha,$oldSenha,$this->session->userdata('id'));
        if($result){
            $this->session->set_flashdata('success','Senha Alterada com sucesso!');
            redirect(base_url() . 'mapos/minhaConta');
        }
        else
        {
            $this->session->set_flashdata('error','Ocorreu um erro ao tentar alterar a senha!');
            redirect(base_url() . 'mapos/minhaConta');
        }

    }

    public function pesquisar()
    {

        if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado')))
        {
            redirect('mapos/login');
        }

        $termo = $this->input->get('termo');

        $data['results'] = $this->mapos_model->pesquisar($termo);
        $this->data['produtos'] = $data['results']['produtos'];
        $this->data['servicos'] = $data['results']['servicos'];
        $this->data['os'] = $data['results']['os'];
        $this->data['clientes'] = $data['results']['clientes'];
        $this->data['view'] = 'mapos/pesquisa';
        $this->load->view('tema/topo',  $this->data);

    }

    public function login()
    {
        $this->load->view('mapos/login');
    }

    public function sair()
    {
        $this->session->sess_destroy();
        redirect('mapos/login');
    }


    public function verificarLogin()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email','valid_email|required|xss_clean|trim');
        $this->form_validation->set_rules('senha','Senha','required|xss_clean|trim');
        $ajax = $this->input->get('ajax');

        if($this->form_validation->run() === FALSE)
        {

            if($ajax == true)
            {
                $json = array('result' => false);
                echo json_encode($json);
            }
            else
            {
                $this->session->set_flashdata('error','Os dados de acesso estão incorretos.');
                redirect($this->login);
            }

        }
        else
        {

            $email = $this->input->post('email');
            $senha = $this->input->post('senha');

            $this->load->library('encrypt');
            $senha = $this->encrypt->sha1($senha);

            $this->db->where('email', $email);
            $this->db->where('senha', $senha);
            $this->db->where('situacao', 1);
            $query = $this->db->get('usuarios');

            if($query->num_rows())
            {
                $usuario = $query->row();

                $dados = array(
                  'nome'            => $usuario->nome,
                  'id'              => $usuario->idUsuarios,
                  'nivel'           => $usuario->nivel,
                  'logado'          => TRUE,
                  'telefone'        => $usuario->telefone,
                  'caixa_valor'     => $usuario->caixa_valor,
                  'caixa_situacao'  => $usuario->caixa_situacao
                 );
                $this->session->set_userdata($dados);

                if($ajax == true)
                {
                    $json = array('result' => true);
                    echo json_encode($json);
                }
                else
                {
                    redirect(base_url('mapos'));
                }

            }
            else
            {

                if($ajax == true){
                    $json = array('result' => false);
                    echo json_encode($json);
                }
                else
                {
                    $this->session->set_flashdata('error','Os dados de acesso estão incorretos.');
                    redirect($this->login);
                }

            }

        }

    }

    public function backup()
    {
        if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado')))
        {
            redirect('mapos/login');
        }

        $this->load->dbutil();
        $prefs = array(
            'format'      => 'zip',
            'filename'    => 'backup'.date('d-m-Y').'.sql'
        );

        $backup =& $this->dbutil->backup($prefs);

        $this->load->helper('file');
        write_file(base_url().'backup/backup.zip', $backup);

        $this->load->helper('download');
        force_download('backup'.date('d-m-Y H:m:s').'.zip', $backup);

    }


    public function emitente()
    {

        $data['menuConfiguracoes'] = 'Configuracoes';
        $data['dados'] = $this->mapos_model->getEmitente();
        $data['view'] = 'mapos/emitente';
        $this->load->view('tema/topo',$data);
        $this->load->view('tema/rodape');

    }

    function do_upload()
    {

        $this->load->library('upload');
        $image_upload_folder = FCPATH . 'assets/uploads';

        if (!file_exists($image_upload_folder)) {
            mkdir($image_upload_folder, DIR_WRITE_MODE, true);
        }

        $this->upload_config = array(
            'upload_path'   => $image_upload_folder,
            'allowed_types' => 'png|jpg|jpeg|bmp',
            'max_size'      => 2048,
            'remove_space'  => TRUE,
            'encrypt_name'  => TRUE,
        );

        $this->upload->initialize($this->upload_config);

        if (!$this->upload->do_upload())
        {
            $upload_error = $this->upload->display_errors();
            print_r($upload_error);
            exit;
        }
        else
        {
            $file_info = array($this->upload->data());
            return $file_info[0]['file_name'];
        }

    }


    public function cadastrarEmitente()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome','Razão Social','required|xss_clean|trim');
        $this->form_validation->set_rules('cnpj','CNPJ','required|xss_clean|trim');
        $this->form_validation->set_rules('ie','IE','required|xss_clean|trim');
        $this->form_validation->set_rules('logradouro','Logradouro','required|xss_clean|trim');
        $this->form_validation->set_rules('numero','Número','required|xss_clean|trim');
        $this->form_validation->set_rules('bairro','Bairro','required|xss_clean|trim');
        $this->form_validation->set_rules('cidade','Cidade','required|xss_clean|trim');
        $this->form_validation->set_rules('uf','UF','required|xss_clean|trim');
        $this->form_validation->set_rules('telefone','Telefone','required|xss_clean|trim');
        $this->form_validation->set_rules('email','E-mail','required|xss_clean|trim');

        if ($this->form_validation->run() == false)
        {
            $this->session->set_flashdata('error','Campos obrigatórios não foram preenchidos.');
            redirect(base_url().'mapos/emitente');
        }
        else
        {

            $nome = $this->input->post('nome');
            $cnpj = $this->input->post('cnpj');
            $ie = $this->input->post('ie');
            $logradouro = $this->input->post('logradouro');
            $numero = $this->input->post('numero');
            $bairro = $this->input->post('bairro');
            $cidade = $this->input->post('cidade');
            $uf = $this->input->post('uf');
            $telefone = $this->input->post('telefone');
            $email = $this->input->post('email');
            $image = $this->do_upload();
            $logo = base_url().'assets/uploads/'.$image;

            $retorno = $this->mapos_model->addEmitente($nome, $cnpj, $ie, $logradouro, $numero, $bairro, $cidade, $uf,$telefone,$email, $logo);

            if($retorno)
            {
                $this->session->set_flashdata('success','As informações foram inseridas com sucesso.');
                redirect(base_url().'mapos/emitente');
            }
            else
            {
                $this->session->set_flashdata('error','Ocorreu um erro ao tentar inserir as informações.');
                redirect(base_url().'mapos/emitente');
            }

        }
    }


    public function editarEmitente()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome','Razão Social','required|xss_clean|trim');
        $this->form_validation->set_rules('cnpj','CNPJ','required|xss_clean|trim');
        $this->form_validation->set_rules('ie','IE','required|xss_clean|trim');
        $this->form_validation->set_rules('logradouro','Logradouro','required|xss_clean|trim');
        $this->form_validation->set_rules('numero','Número','required|xss_clean|trim');
        $this->form_validation->set_rules('bairro','Bairro','required|xss_clean|trim');
        $this->form_validation->set_rules('cidade','Cidade','required|xss_clean|trim');
        $this->form_validation->set_rules('uf','UF','required|xss_clean|trim');
        $this->form_validation->set_rules('telefone','Telefone','required|xss_clean|trim');
        $this->form_validation->set_rules('email','E-mail','required|xss_clean|trim');

        if ($this->form_validation->run() == false)
        {
            $this->session->set_flashdata('error','Campos obrigatórios não foram preenchidos.');
            redirect(base_url().'mapos/emitente');
        }
        else
        {

            $nome = $this->input->post('nome');
            $cnpj = $this->input->post('cnpj');
            $ie = $this->input->post('ie');
            $logradouro = $this->input->post('logradouro');
            $numero = $this->input->post('numero');
            $bairro = $this->input->post('bairro');
            $cidade = $this->input->post('cidade');
            $uf = $this->input->post('uf');
            $telefone = $this->input->post('telefone');
            $email = $this->input->post('email');
            $id = $this->input->post('id');

            $retorno = $this->mapos_model->editEmitente($id, $nome, $cnpj, $ie, $logradouro, $numero, $bairro, $cidade, $uf,$telefone,$email);
            if($retorno)
            {
                $this->session->set_flashdata('success','As informações foram alteradas com sucesso.');
                redirect(base_url().'mapos/emitente');
            }
            else
            {
                $this->session->set_flashdata('error','Ocorreu um erro ao tentar alterar as informações.');
                redirect(base_url().'mapos/emitente');
            }

        }

    }

    public function editarLogo()
    {

        $id = $this->input->post('id');
        if($id == null || !is_numeric($id)){
           $this->session->set_flashdata('error','Ocorreu um erro ao tentar alterar a logomarca.');
           redirect(base_url().'mapos/emitente');
        }

        $this->load->helper('file');
        delete_files(FCPATH .'assets/uploads/');

        $image = $this->do_upload();
        $logo = base_url().'assets/uploads/'.$image;

        $retorno = $this->mapos_model->editLogo($id, $logo);

        if($retorno)
        {
            $this->session->set_flashdata('success','As informações foram alteradas com sucesso.');
            redirect(base_url().'mapos/emitente');
        }
        else
        {
            $this->session->set_flashdata('error','Ocorreu um erro ao tentar alterar as informações.');
            redirect(base_url().'mapos/emitente');
        }

    }

}
