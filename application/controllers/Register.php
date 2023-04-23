<?php
class Register extends CI_Controller
{
       public function __construct()
   {  
      parent::__construct();
      $this->load->helper(array('form', 'url'));

	    $this->load->library('form_validation');
      $this->load->model('Register_model');
   }
     public function index()
   {
      $this->load->view('register');
   }
    // Log in user
    public function insert_register()
    {
     
   if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('fullname', 'fullname', 'required');
			$this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('number_phone', 'number_phone', 'required');
            $this->form_validation->set_rules('experience', 'experience', 'required');
            $this->form_validation->set_rules('Forte', 'Forte', 'required');

			if ($this->form_validation->run() == FALSE)
          {
				$data = array('responce' => 'error', 'message' => validation_errors());
			 }
          else 
         {
           $ajax_data = $this->input->post();
          

				if ( $this->Register_model ->insert_register($ajax_data)) {
					$data = array('responce' => 'success', 'message' => 'Đăng ký thành công');
                   
				} else {
					$data = array('responce' => 'error', 'message' => 'Đăng ký không thành công');
				}
			}
			echo json_encode($data);
		} else {
			echo "No direct script access allowed";
		}




    }
    
}
