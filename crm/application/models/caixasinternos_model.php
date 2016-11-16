<?php
class CaixasInternos_model extends CI_Model {

    public $tabela = "caixas_internos";

    function getCaixaAberto(){
      $this->db->where('status', '1');
      $query = $this->db->get($this->tabela);

      return $query->num_rows() ? $query->row_array() : NULL;
    }

    function abrirCaixa($dados){
        $this->db->insert($this->tabela, $dados);
        $id_caixa = $this->db->insert_id();

        $lancamento = array(
          "descricao"           => "Abertura de Caixa",
          "valor"               => $dados["saldo_inicial"],
          "data_vencimento"     => date('Y-m-d'),
          "data_pagamento"      => date('Y-m-d'),
          "baixado"             => 1,
          "cliente_fornecedor"  => "Abertura de Caixa",
          "forma_pgto"          => "Dinheiro",
          "tipo"                => "receita",
          "id_conta"            => 1
        );
        $this->db->insert("lancamentos", $lancamento);
        $this->db->where("id", 1);
        $conta = array("status" => 1);
        $this->db->update("contas", $conta);
        return $id_caixa;
    }

    function incrementarSaldo($id, $valor){
        $this->db->where("id", $id);
        $this->db->set('saldo_corrente', 'saldo_corrente+'. $valor, FALSE);
        $this->db->update($this->tabela);
        return $this->db->affected_rows();
    }

    function decrementarSaldo($id, $valor){
        $this->db->where("id", $id);
        $this->db->set('saldo_corrente', 'saldo_corrente-'. $valor, FALSE);
        $this->db->update($this->tabela);
        return $this->db->affected_rows();
    }


    function update($id, $dados){
        $this->db->where("id", $id);
        $this->db->update("contas", $dados);
    		return $this->db->affected_rows();
    }


    // Função que na verdade não exclui o registro, apenas altera o campo
    // "habilidado" para 0...
    function delete($id){
        $this->db->where('id', $id);
        $array = array("habilitado" => 0);
        $this->db->update("contas", $array);
        return $this->db->affected_rows();
    }

}
