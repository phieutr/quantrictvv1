<?php

class Keyword_type extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function keyword_type()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $data['folder'] = 'page';
        $data['file'] = 'keyword_type';

        $data['current_page'] = 'dashboard';
        $data['title'] = 'Dạng từ khóa';
        $this->load->view('admin/index', $data);
    }
    //==============Chức năng==============//
    public function add_keyword_type()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }

        $name  = $this->input->post('name');
        insert('keyword_type', ['name' => $name]);

        $id = row_select('keyword_type', ['name' => $name], ['ID'])['ID'];
        $n = 10;
        for ($i = 1; $i <= $n; $i++) {
            $word = $this->input->post('word' . $i);
            $price = $this->input->post('price' . $i);
            if (!empty($word) && !empty($price)) {
                insert('keyword_type_price', ['keyword_type_id' => $id, 'word' => $word, 'price' => $price]);
            }
        }
        redirect(base_url('keyword-type'));
    }
    public function edit_keyword_type()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id    = $this->input->post('edit_id');
        $name  = $this->input->post('edit_name');
        update('keyword_type', ['name' => $name], ['ID' => $id]);

        $n = 15;
        for ($i = 1; $i <= $n; $i++) {
            $word       = $this->input->post('word' . $i);
            $price      = $this->input->post('price' . $i);
            $connect_id = $this->input->post('ID' . $i);
            if (!empty($word) && !empty($price)) {
                if (!empty($connect_id)) {
                    update('keyword_type_price', ['word' => $word, 'price' => $price], ['ID' => $connect_id]);
                } else {
                    insert('keyword_type_price', ['keyword_type_id' => $id, 'word' => $word, 'price' => $price]);
                }
            }
        }
        redirect(base_url('keyword-type'));
    }
    public function delete_keyword_type()
    {
        $role = $this->session->userdata('role');

        if (isset($role) && $role == 'admin') {
            $id  = $this->input->get('ID');
            delete('keyword_type', ['ID' => $id]);
            delete('keyword_type_price', ['keyword_type_id' => $id]);
        }
        redirect(base_url('keyword-type'));
    }
}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
