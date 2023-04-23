<?php

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function index()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }
        if ($role == 'ctv') {
            redirect(base_url('keyword'));
        }

        $data['folder'] = 'page';
        $project_id = $this->input->get('project_id');
       
        $where_array = [];
        if ($role == 'customer') {
            $session_user = row_select('users', ['username' => $username], []);
            $data['customer_id'] = $where_array['customer'] = $session_user['id'];
        }
        if (!empty($project_id)) {
            $where_array['ID'] = $project_id;
            $data['project'] = $project = row_select('project', $where_array, []);
            $data['file'] = 'dashboard_project';
            $data['title'] = 'Dashboard ' . $project['name'];
        } else {

            $data['file'] = 'dashboard';
            $data['title'] = 'Dashboard tất cả dự án';
        }

        $data['current_page'] = 'dashboard';
        $this->load->view('admin/index', $data);
    }
}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
