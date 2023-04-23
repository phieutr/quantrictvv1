<?php

class Project extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function project()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if ($role != 'admin' && $role != 'customer') {
            redirect(base_url());
        }
        if ($role == 'customer') {
            $session_user = row_select('users', ['username' => $username], []);
            $data['customer_id'] = $where_array['customer'] = $session_user['id'];
        }
        $data['folder'] = 'page';
        $data['file'] = 'project';

        $data['current_page'] = 'dashboard';
        $data['title'] = 'Dự án';
        $this->load->view('admin/index', $data);
    }

    //==============Chức năng==============//
    public function add_ctv()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $project    = $this->input->post('project');
        $ctv        = $this->input->post('ctv');

        foreach ($ctv as $key => $value) {
            $check = row_select('project_users', ['idProject' => $project, 'idUser' => $value], []);
            if (empty($check)) {
                insert('project_users', ['idProject' => $project, 'idUser' => $value]);
            }
        }
        redirect(base_url('project'));
    }
    public function add_project()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $name       = $this->input->post('name');
        $customer   = $this->input->post('customer');
        $dateBegin  = $this->input->post('dateBegin');
        $dateEnd    = $this->input->post('dateEnd');
        $note       = $this->input->post('note');

        $data = [
            'name'      => $name,
            'customer' => $customer,
            'dateBegin' => $dateBegin,
            'dateEnd'   => $dateEnd,
            'note'      => $note
        ];
        insert('project', $data);
        redirect(base_url('project'));
    }
    public function edit_project()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id         = $this->input->post('edit_id');
        $name       = $this->input->post('edit_name');
        $customer   = $this->input->post('edit_customer');
        $dateBegin  = $this->input->post('edit_dateBegin');
        $dateEnd    = $this->input->post('edit_dateEnd');
        $note       = $this->input->post('edit_note');
        $status     = $this->input->post('status');

        if ($status == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }

        $data = [
            'name'      => $name,
            'customer'  => $customer,
            'dateBegin' => $dateBegin,
            'dateEnd'   => $dateEnd,
            'note'      => $note,
            'status'    => $status
        ];
        update('project', $data, ['ID' => $id]);
        redirect(base_url('project'));
    }
    public function delete_project()
    {
        $role = $this->session->userdata('role');
        if (isset($role) && $role == 'admin') {
            $id  = $this->input->get('ID');
            delete('project', ['ID' => $id]);
            delete('keyword', ['project' => $id]);
        }
        redirect(base_url('project'));
    }
    public function project_delete_ctv()
    {
        $project  = $this->input->post('project');
        $ctv  = $this->input->post('ctv');
        delete('project_users', ['idProject' => $project, 'idUser' => $ctv]);
    }
}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
