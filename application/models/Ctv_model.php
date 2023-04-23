<?php 

	class Ctv_model extends CI_Model {

     public function get_entries()
        {
                $query = $this->db->get('register');
               if (count( $query->result() ) > 0) {
                   
                      return $query->result();
                    
               }
                   
                 
        }
            public function details_entry($id){
        	$this->db->select("*");
        	$this->db->from("register");
        	$this->db->where("id", $id);
        	$query = $this->db->get();
        	if (count($query->result()) > 0) {
        		return $query->row();
        	}
        }
         public function browse_ctv($id)        	
        {
         return $this->db->delete('register', array('id' => $id));
        }
         public function del_ctv($id){
        	return $this->db->delete('register', array('id' => $id));
        }    

}