<?php 

	class Pcn_model extends CI_Model {

         public function get_assigNment()
        {
                $query = $this->db->get('keyword');
                 if (count( $query->result() ) > 0) {
                      return $query->result();
                    }
        }
              public function get_users()
        {
                $query = $this->db->get('users');
                
                 if (count( $query->result() ) > 0) {
                      return $query->result();
                    }
        }
      }