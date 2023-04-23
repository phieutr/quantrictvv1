<?php 

	class Register_model extends CI_Model {


        public function insert_register($data)
        {
            return $this->db->insert('register', $data);
             
        }

        

}