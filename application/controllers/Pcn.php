<?php


class Pcn extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
         $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
      $this->load->model('Pcn_model');
    }

    public function pcn()
    {

        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }

        $data['folder'] = 'page';
        $data['file'] = 'pcn';
        $project_id = $this->input->get('project_id');
        if ($role == 'customer') {
            $session_user = row_select('users', ['username' => $username], []);
            $data['customer_id'] = $where_array['customer'] = $session_user['id'];
        }
        if ($role == 'ctv') {
            $session_user = row_select('users', ['username' => $username], []);
            $data['ctv_id'] = $where_array['customer'] = $session_user['id'];
        }
        if (!empty($project_id)) {
            $data['project'] = $project = row_select('project', ['ID' => $project_id], []);

            $data['title'] = 'Từ khóa dự án ' . $project['name'];
        } else {

            $data['title'] = 'Từ khóa tất cả dự án';
        }
        $data['current_page'] = 'pcn';
        $this->load->view('admin/index', $data);
    }

    public function pcn_choose()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }
        if ($role != 'admin' && $role != 'ctv') {
            redirect(base_url());
        }
        $data['folder'] = 'page';
        $data['file'] = 'pcn_choose';

        $project_id = $this->input->get('project_id');

        if (!empty($project_id)) {
            $data['project'] = $project = row_select('project', ['ID' => $project_id], []);

            $data['title'] = 'Chọn từ khóa dự án ' . $project['name'];
        } else {

            $data['title'] = 'Chọn từ khóa tất cả dự án';
        }

        $data['current_page'] = 'pcn';
        $this->load->view('admin/index', $data);
    }

    public function post_update($id)
    {

        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }

        $pcn = row_select('pcn', ['ID' => $id], []);
        $ctv_id  = row_select('users', ['username' => $username], ['id'])['id'];

        if ($role == 'admin' || $ctv_id == $pcn['ctv']) {

            $data['folder'] = 'page';
            $data['file']   = 'post_update';
            $data['pcn'] = $pcn;
            $data['current_page'] = 'pcn';
            $data['title'] = 'Cập nhật bài viết';
            $this->load->view('admin/index', $data);
        } else {
            redirect(base_url('pcn'));
        }
    }

    public function image_list()
    {
        $id = $this->input->get('ID');
        $images = result_select('image', ['pcn_id' => $id], []);

        foreach ($images as $key => $image) {
            $url = base_url('assets/image/' . $image['image']);
            echo '<p>' . $url . '</p><img src="' . $url . '">';
        }
    }

    public function preview($id)
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }

        $data['pcn']  = $pcn = row_select('pcn', ['ID' => $id], []);

        $data['folder'] = 'page';
        $data['file'] = 'preview';

        $data['current_page'] = 'pcn';
        $data['title'] = 'Xem trước bài viết cho pcn ' . $pcn['name'];
        $this->load->view('admin/index', $data);
    }

    //==============Chức năng==============//
    public function auto_update()
    {
        $id = $this->input->post('ID');
        $content = $this->input->post('content');
        update('pcn', ['content' =>  $content], ['ID' => $id]);
    }

    public function upload_excel()
    {
        $this->load->view('upload_excel');
    }

    public function excel_pcn()
    {
        $config['upload_path'] = './assets/excel';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = '2048';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            printR($error);
        } else {
            $data['upload_data'] = $upload_data = $this->upload->data();
            echo '<p>Upload thành công</p><br><a href="' . base_url('pcn-choose') . '">Quay lại</a>';

            $this->load->view('upload_excel_progress', $data);
        }
    }







    //Chức năng pcn
    public function give_pcn()
    {      
    
        
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
        $id      = $this->input->post('ID');
        $ctv     = $this->input->post('ctv');
        update('pcn', ['ctv' => $ctv, 'dateTake' => date('Y/m/d H:i:s')], ['ID' => $id]);
         
        
        redirect(base_url('pcn-choose'));
      
    }
    public function edit_pcn_info()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id         = $this->input->post('ID');
        $ctv        = $this->input->post('ctv');
        $deadline   = $this->input->post('deadline');
        $outline_check   = $this->input->post('outline_check');
        $finish_outline   = $this->input->post('finish_outline');

        $data = array(
            'ctv' => $ctv,
            'dateTake' => date('Y/m/d H:i:s'),
            'deadline' => $deadline,
            'outline_check' => $outline_check,
            'finish_outline' => $finish_outline
        );

        update('pcn', $data, ['ID' => $id]);
        redirect(base_url('preview/' . $id));
    }
    public function point_pcn()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id         = $this->input->post('ID');
        $point     = $this->input->post('point');
        update('pcn', ['point' => $point], ['ID' => $id]);
        redirect(base_url('pcn'));
    }
    public function note_pcn()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id       = $this->input->post('ID');
        $note     = $this->input->post('note');
        update('pcn', ['note' => $note, 'finish' => 4], ['ID' => $id]);
        redirect(base_url('pcn'));
    }
    public function customer_note_pcn()
    {
        $id       = $this->input->post('ID');
        $note     = $this->input->post('customer_note');
        update('pcn', ['customer_note' => $note, 'finish' => 4], ['ID' => $id]);
        redirect(base_url('pcn'));
    }
    public function approve_pcn()
    {
        $id            = $this->input->get('ID');
        $action        = $this->input->get('action');

        switch ($action) {
            case 'outline':
                update('pcn', ['finish_outline' => 1], ['ID' => $id]);
                break;
            case 'content':
                update('pcn', ['finish' => 3], ['ID' => $id]);
                break;
            case 'pay':
                update('pcn', ['finish' => 1, 'dateApproveMoney' => date('Y/m/d H:i:s')], ['ID' => $id]);
                break;
        }
        redirect(base_url('preview/' . $id));
    }
    public function edit_pcn()
    {
        $id               = $this->input->post('ID');
        $metaTitle        = $this->input->post('metaTitle');
        $metaDescription  = $this->input->post('metaDescription');

        $data = array(
            'metaTitle'         => $metaTitle,
            'metaDescription'   => $metaDescription,
            'dateUpdate'        => date('Y/m/d H:i:s')
        );

        $pcn = row_select('pcn', ['ID' => $id], []);
        if ($pcn['outline_check'] == 0) {
            $content          = $this->input->post('content');
            $finish           = $this->input->post('finish');

            if ($finish == 'on') {
                $finish = 2;
            } else {
                $finish = 0;
            }

            $data['finish'] = $finish;
            $data['content'] = $content;
        } else {
            if ($pcn['finish_outline'] != 1) {

                $finish_outline   = $this->input->post('finish_outline');
                $outline          = $this->input->post('outline');

                if ($finish_outline == 'on') {
                    $finish_outline = 2;
                } else {
                    $finish_outline = 0;
                }

                $data['finish_outline'] = $finish_outline;
                if ($pcn['finish'] == 4) {
                    $data['finish']         = 0;
                }
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
        }

        update('pcn', $data, ['ID' => $id]);
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
            insert('image', ['pcn_id' => $id, 'image' => $value]);
        }
        redirect(base_url('post/update/' . $id));
    }
    public function add_pcn()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }

        $name           = $this->input->post('name');
        $project        = $this->input->post('project');
        $pcn_type   = $this->input->post('pcn_type');
        $word           = $this->input->post('word');
        $deadline       = $this->input->post('deadline');
        $outline_check  = $this->input->post('outline_check');

        $data = array(
            'name'             => $name,
            'project'          => $project,
            'pcn_type'     => $pcn_type,
            'word'             => $word,
            'deadline'         => $deadline,
            'outline_check'    => $outline_check,
            'dateCreate'       => date('Y/m/d H:i:s')
        );
        insert('pcn', $data);
        redirect(base_url('pcn-choose'));
    }

     public function addd_pcn()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }

        $name           = $this->input->post('name');
        $project        = $this->input->post('project');
        $pcn_type   = $this->input->post('pcn_type');
        $word           = $this->input->post('word');
        $deadline       = $this->input->post('deadline');
        $outline_check  = $this->input->post('outline_check');

        $data = array(
            'name'             => $name,
            'project'          => $project,
            'pcn_type'     => $pcn_type,
            'word'             => $word,
            'deadline'         => $deadline,
            'outline_check'    => $outline_check,
            'dateCreate'       => date('Y/m/d H:i:s')
        );
        insert('pcn', $data);
        redirect(base_url('pcn'));
    }


    public function choose_pcn()
    {
        $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }
        $id = $this->input->get('ID');
        $user_id = row_select('users', ['username' => $username], ['id'])['id'];
        update('pcn', ['ctv' => $user_id, 'dateTake' => date('Y/m/d H:i:s')], ['ID' => $id]);
        redirect(base_url('pcn-choose'));
    }

     public function pcn_pcn()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
       
        $id      = $this->input->post('ID');
        $ctv     = $this->input->post('ctv');
         if(!empty($ctv)){
        update('pcn', ['ctv' => $ctv, 'dateTake' => date('Y/m/d H:i:s')], ['ID' => $id]);
          redirect(base_url('pcn'));
        }
        
    }

    public function delete_pcn()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }
        $id = $this->input->get('ID');
        delete('pcn', ['ID' => $id]);
        redirect(base_url('pcn'));
    }
    
 public function assigNment  ()  {
    		if($this->input->is_ajax_request()){
			if($posts = $this->Pcn_model->get_assigNment()){
				$data = array('responce' => 'success','posts' => $posts);
				
			}else{
				$data = array('responce' => 'error','massage' => 'Không tìm thấy dữ liệu');
			}
			echo json_encode($data);
		}else{
			echo "không cho phép truy cập data";
		}
	}
     public function addUsers  ()  {
    		if($this->input->is_ajax_request()){
			if($posts = $this->Pcn_model->get_users()){
				$data = array('responce' => 'success','posts' => $posts);
				
			}else{
				$data = array('responce' => 'error','massage' => 'Không tìm thấy dữ liệu');
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
