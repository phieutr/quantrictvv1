<?php

class Customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function customer()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $data['folder'] = 'page';
        $data['file'] = 'customer';

        $data['current_page'] = 'dashboard';
        $data['title'] = 'Khách hàng';
        $this->load->view('admin/index', $data);
    }

    //==============Chức năng==============//

    public function delete_user()
    {
        $role = $this->session->userdata('role');

        if (isset($role) && $role == 'admin') {
            $id  = $this->input->get('id');
            delete('users', ['id' => $id]);
        }
        redirect(base_url('customer'));
    }
    public function edit_user()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('customer'));
        }

        $id         = $this->input->post('edit_id');
        $name       = $this->input->post('edit_name');
        $password      = $this->input->post('edit_password');

        $data = [
            'fullname'      => $name,
            'password'  => md5($password),
        ];
        update('users', $data, ['id' => $id]);
        redirect(base_url('customer'));
    }
}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
