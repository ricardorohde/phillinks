<?php
class Contas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get(){
        $this->db->where("habilitado", "1");
        $query = $this->db->get("contas");
        return $query->num_rows() ? $query->result_array() : NULL;
    }


    function getById($id){
        $this->db->where('id', $id);
        $query = $this->db->get('contas');
        return $query->num_rows() ? $query->row_array() : NULL;
    }


    function create($dados){
        $this->db->insert("contas", $dados);
        return $this->db->insert_id();
    }


    function update($id, $dados){
        $this->db->where("id", $id);
        $this->db->update("contas", $dados);
    		return $this->db->affected_rows();
    }

    function incrementarSaldo($id, $valor){
        $this->db->where("id", $id);
        $this->db->set('saldo', 'saldo+'. $valor, FALSE);
        $this->db->update('contas');
        return $this->db->affected_rows();
    }

    function decrementarSaldo($id, $valor){
        $this->db->where("id", $id);
        $this->db->set('saldo', 'saldo-'. $valor, FALSE);
        $this->db->update('contas');
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
