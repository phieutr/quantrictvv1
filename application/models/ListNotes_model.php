<?php 

	class ListNotes_model extends CI_Model {

        public function get_entries()
        {
                $query = $this->db->get('listnotes');
                 if (count( $query->result() ) > 0) {
                   
                      return $query->result();
                    

                    }
                	
                 
        }
        public function get_entriess()
        {
                $query = $this->db->get('listnotes');
                 if (count( $query->result() ) > 0) {
                   
                      return $query->result();
                    

                    }
                	
                 
        }
         public function checked($id)
        {
           $this->db->select("*");
        	$this->db->from("listnotes");
        	$this->db->where("id", $id);
        	$query = $this->db->get();
        	
        		return $query->row();
        	
          
        }

        public function insert_entry($data)
        {
            return $this->db->insert('listnotes', $data);
        }

        public function delete_entry($id){
        	return $this->db->delete('listnotes', array('id' => $id));
        }      

        public function edit_entry($id){
        	$this->db->select("*");
        	$this->db->from("listnotes");
        	$this->db->where("id", $id);
        	$query = $this->db->get();
        	if (count($query->result()) > 0) {
        		return $query->row();
        	}
        }

        public function update_entry($data)
        {
            return $this->db->update('listnotes', $data, array('id' => $data['id']));
        }
        public function complete_note($id)
        {
         $checked = 1 ; 
			 return $this->db->update('listnotes',['checked' => $checked],['id' => $id]);
        }
         public function undoNotes($id)
        {
         $checkedd = 0 ; 
			 return $this->db->update('listnotes',['checked' => $checkedd],['id' => $id]);
        }
        

}