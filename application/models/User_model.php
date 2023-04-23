<?php
class User_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    // Log user in
    public function login($username, $password)
    {
        // Validate
        $this->db->where('username', $username);
        $this->db->where('password', $password);

        $result = $this->db->get('users');

        if ($result->num_rows() == 1) {
            return $result->row(0)->role;
        } else {
            return false;
        }
    }
}
