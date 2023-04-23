<?php

class Ctv extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->load->model('Ctv_model');
    }

    public function ctv()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if ($role != 'admin' && $role != 'ctv') {
            redirect(base_url());
        }
        if ($role == 'ctv') {
            $session_user = row_select('users', ['username' => $username], []);
            $data['ctv_id'] = $where_array['customer'] = $session_user['id'];
        }

        $data['folder'] = 'page';
        $data['file'] = 'ctv';

        $data['current_page'] = 'dashboard';
        $data['title'] = 'Cộng tác viên';
        $this->load->view('admin/index', $data);
    }

    //==============Chức năng==============//

    public function add_user()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }

        $fullname  = $this->input->post('fullname');
        $username  = $this->input->post('username');
        $password  = $this->input->post('password');
        $email     = $this->input->post('email');
        $phone     = $this->input->post('phone');
        $role      = $this->input->post('role');


        $data = array(
            'fullname'  => $fullname,
            'username'  => $username,
            'password'  => md5($password),
            'email'     => $email,
            'phone'     => $phone,
            'role'      => $role
        );

        if ($role == 'ctv') {
            $data['stk'] =  $this->input->post('stk');
            $data['bank'] =  $this->input->post('bank');
            $data['cardOwner'] = $this->input->post('cardOwner');
            insert('users', $data);
            redirect(base_url('ctv'));
        }
        if ($role == 'customer') {
            insert('users', $data);
            redirect(base_url('customer'));
        }
    }
    public function delete_user()
    {
        $role = $this->session->userdata('role');

        if (isset($role) && $role == 'admin') {
            $id  = $this->input->get('id');
            delete('users', ['id' => $id]);
        }
        redirect(base_url('ctv'));
    }
   public function edit_user()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin' && $role != 'ctv') {
            redirect(base_url('ctv'));
        }

        $id         = $this->input->post('edit_id');
        $name       = $this->input->post('edit_name');
        $phone      = $this->input->post('edit_phone');
        $email      = $this->input->post('edit_email');
        $stk    = $this->input->post('edit_stk');
         $bank    = $this->input->post('edit_bank');
          $cardOwner    = $this->input->post('edit_cardOwner');
           

        $data = [
            'fullname'      => $name,
            'phone'     => $phone,
            'email' => $email,
             'stk' =>  $stk,
              'bank' =>  $bank,
               'cardOwner' =>  $cardOwner
        ];

        update('users', $data, ['id' => $id]);
        redirect(base_url('ctv'));
    }

    		public function browse(){
		if($this->input->is_ajax_request()){
			if($posts = $this->Ctv_model->get_entries()){
				$data = array('responce' => 'success','posts' => $posts);
				
			}else{
				$data = array('responce' => 'error','massage' => 'Không tìm thấy dữ liệu');
			}
			echo json_encode($data);
		}else{
			echo "không cho phép truy cập data";
		}
	}
    public function details()
	{
		if ($this->input->is_ajax_request()) {
			$details = $this->input->post('details');

			if ($post = $this->Ctv_model->details_entry($details)) {
				$data = array('responce' => 'success', 'post' => $post);
			} else {
				$data = array('responce' => 'error', 'message' => 'failed to fetch record');
			}
			echo json_encode($data);
		} else {
			echo "No direct script access allowed";
		}
	}
    	public function browse_ctv(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
           $register =  row_select('register', ['id' => $id], []);	   
           $data = array(
            'username' => $register['email'],
            'password' => '25f9e794323b453885f5181f1b624d0b',
            'role' => 'ctv',
            'fullname' => $register['fullname'],
            'email' => $register['email'],
            'address' => $register['number_phone'],
            'phone' => $register['number_phone'],
            'experience' => $register['experience'],
            'Forte' => $register['Forte'],
          
           );
           $this->db->insert('users', $data);
			if ($this->Ctv_model->browse_ctv($id)) {
				$data = array('responce' => 'success', 'message' => 'Ghi chú thành công');
			} else {
				$data = array('responce' => 'error', 'message' => 'Không thêm được bản ghi');
			}
			echo json_encode($data);
        }
			
	}
    	public function del_ctv(){
		if($this->input->is_ajax_request()){
			$del_id = $this->input->post('del_ctv');
			if($this->Ctv_model->del_ctv($del_id)){
				$data = array('responce' => 'success');
			}else{
				$data = array('responce' => 'error');
			}
			echo json_encode($data);
		}else{
			echo "không cho phép truy cập data";
		}
	}
}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
