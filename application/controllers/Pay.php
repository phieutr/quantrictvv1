<?php

class Pay extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function pay()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if ($role != 'admin') {
            redirect(base_url());
        }       

        $data['folder'] = 'page';
        $data['file'] = 'pay';

        $data['current_page'] = 'dashboard';
        $data['title'] = 'Thống kê thanh toán';
        $this->load->view('admin/index', $data);
    }

}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
