<?php

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function index()
    {
        $role = $this->session->userdata('role');
        $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }

        $data['folder'] = 'page';
        $project_id = $this->input->get('project_id');
        if (!empty($project_id)) {
            $data['project'] = $project = row_select('project', ['ID' => $project_id], []);
            $data['file'] = 'dashboard_project';
            $data['title'] = 'Dashboard ' . $project['name'];
        } else {
            $data['file'] = 'dashboard';
            $data['title'] = 'Dashboard tất cả dự án';
        }

        $this->load->view('admin/index', $data);
    }


    public function project()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $data['folder'] = 'page';
        $data['file'] = 'project';

        $data['title'] = 'Dự án';
        $this->load->view('admin/index', $data);
    }

    public function keyword_type()
    {
        $data['folder'] = 'page';
        $data['file'] = 'keyword_type';


        $data['title'] = 'Dạng từ khóa';
        $this->load->view('admin/index', $data);
    }

    public function keyword()
    {
        $data['folder'] = 'page';
        $data['file'] = 'keyword';


        $data['title'] = 'Từ khóa';
        $this->load->view('admin/index', $data);
    }

    public function keyword_choose()
    {
        $data['folder'] = 'page';
        $data['file'] = 'keyword_choose';


        $data['title'] = 'Chọn từ khóa';
        $this->load->view('admin/index', $data);
    }

    public function post_update($id)
    {
        $role = $this->session->userdata('role');
        $username = $this->session->userdata('username');
        $keyword = row_select('keyword', ['ID' => $id], []);

        if ($role == 'admin' || $username == $keyword['ctv']) {

            $data['folder'] = 'page';
            $data['file']   = 'post_update';
            $data['keyword'] = $keyword;

            $data['title'] = 'Cập nhật bài viết';
            $this->load->view('admin/index', $data);
        } else {
            redirect(base_url('keyword'));
        }
    }

    public function ctv()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }

        $data['folder'] = 'page';
        $data['file'] = 'ctv';


        $data['title'] = 'Cộng tác viên';
        $this->load->view('admin/index', $data);
    }
    
        public function pcn()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }

        $data['folder'] = 'page';
        $data['file'] = 'pcn';


        $data['title'] = 'Cộng tác viên';
        $this->load->view('admin/index', $data);
    }

    public function image_list()
    {
        $id = $this->input->get('ID');
        $images = result_select('image', ['keyword_id' => $id], []);

        foreach ($images as $key => $image) {
            $url = base_url('assets/image/' . $image['image']);
            echo '<p>' . $url . '</p><img src="' . $url . '">';
        }
    }

    public function preview($id)
    {
        $role = $this->session->userdata('role');
        if (empty($role)) {
            redirect(base_url());
        }
        $data['keyword']  = $keyword = row_select('keyword', ['ID' => $id], []);

        $data['folder'] = 'page';
        $data['file'] = 'preview';


        $data['title'] = 'Xem trước bài viết cho keyword ' . $keyword['name'];
        $this->load->view('admin/index', $data);
    }

    //==============Chức năng==============//

    //Chức năng keyword
    public function note_keyword()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        echo $id       = $this->input->post('ID');
        echo $note     = $this->input->post('note');
        // update('keyword', [], []);
    }
    public function approve_keyword()
    {
        $id            = $this->input->get('ID');
        $action        = $this->input->get('action');

        switch ($action) {
            case 'outline':
                update('keyword', ['finish_outline' => 1], ['ID' => $id]);
                break;
            case 'content':
                update('keyword', ['finish' => 3], ['ID' => $id]);
                break;
            case 'pay':
                update('keyword', ['finish' => 1], ['ID' => $id]);
                break;
        }
        redirect(base_url('keyword'));
    }
    public function edit_keyword()
    {
        $id               = $this->input->post('ID');
        $metaTitle        = $this->input->post('metaTitle');
        $metaDescription  = $this->input->post('metaDescription');

        $data = array(
            'metaTitle'         => $metaTitle,
            'metaDescription'   => $metaDescription,
            'dateUpdate'        => date('Y/m/d H:i:s')
        );

        $keyword = row_select('keyword', ['ID' => $id], []);
        if ($keyword['finish_outline'] != 1) {

            $finish_outline   = $this->input->post('finish_outline');
            $outline          = $this->input->post('outline');

            if ($finish_outline == 'on') {
                $finish_outline = 2;
            } else {
                $finish_outline = 0;
            }

            $data['finish_outline'] = $finish_outline;
            $data['outline']        = $outline;
        } else {
            $content          = $this->input->post('content');
            $finish           = $this->input->post('finish');

            if ($finish == 'on') {
                $finish = 2;
            } else {
                $finish = 0;
            }

            $data['finish'] = $finish;
            $data['content'] = $content;
        }
        update('keyword', $data, ['ID' => $id]);
        redirect(base_url('post/update/' . $id));
    }
    public function image_upload()
    {
        $data = [];
        $id = $this->input->post('ID');
        $count = count($_FILES['files']['name']);

        for ($i = 0; $i < $count; $i++) {

            if (!empty($_FILES['files']['name'][$i])) {

                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                $config['upload_path'] = './assets/image';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|PNG|JPEG';
                $config['max_size'] = '5000';
                $config['file_name'] = $_FILES['files']['name'][$i];

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file')) {

                    $up = $this->upload->data();
                    $file_path = './assets/image/' . $up['file_name'];

                    $this->load->library('image_lib');
                    $config['source_image'] = $file_path;
                    $config['width'] = 850;

                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    $data[] = $up['file_name'];
                }
            }
        }
        foreach ($data as $key => $value) {
            insert('image', ['keyword_id' => $id, 'image' => $value]);
        }
        redirect(base_url('post/update/' . $id));
    }
    public function add_keyword()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }

        $name           = $this->input->post('name');
        $project        = $this->input->post('project');
        $keyword_type   = $this->input->post('keyword_type');
        $word           = $this->input->post('word');

        $data = array(
            'name'             => $name,
            'project'          => $project,
            'keyword_type'     => $keyword_type,
            'word'             => $word,
            'dateCreate'       => date('Y/m/d H:i:s')
        );
        insert('keyword', $data);
        redirect(base_url('keyword-choose'));
    }

    public function choose_keyword()
    {
        $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }
        $id = $this->input->get('ID');
        $user_id = row_select('users', ['username' => $username], ['id'])['id'];
        update('keyword', ['ctv' => $user_id, 'dateTake' => date('Y/m/d H:i:s')], ['ID' => $id]);
        redirect(base_url('keyword-choose'));
    }
    //Chức năng keyword_type
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

        $n = 10;
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
    //Chức năng user
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
        $address   = $this->input->post('address');
        $role      = $this->input->post('role');

        $data = array(
            'fullname'  => $fullname,
            'username'  => $username,
            'password'  => md5($password),
            'email'     => $email,
            'phone'     => $phone,
            'address'   => $address,
            'role'      => $role
        );
        insert('users', $data);
        redirect(base_url('ctv'));
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
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }

        $id         = $this->input->post('edit_id');
        $name       = $this->input->post('edit_name');
        $phone      = $this->input->post('edit_phone');
        $email      = $this->input->post('edit_email');
        $address    = $this->input->post('edit_address');

        $data = [
            'fullname'      => $name,
            'phone'     => $phone,
            'email' => $email,
            'address'   => $address
        ];

        update('users', $data, ['id' => $id]);
        redirect(base_url('ctv'));
    }

    //Chức năng project
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

        $data = [
            'name'      => $name,
            'customer' => $customer,
            'dateBegin' => $dateBegin,
            'dateEnd'   => $dateEnd,
            'note'      => $note
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
        }
        redirect(base_url('project'));
    }
}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
