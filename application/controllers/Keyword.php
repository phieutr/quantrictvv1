<?php

class Keyword extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function keyword()
    {

        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }

        $data['folder'] = 'page';
        $data['file'] = 'keyword';
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
        $data['current_page'] = 'keyword';
        $this->load->view('admin/index', $data);
    }

    public function keyword_choose()
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
        $data['file'] = 'keyword_choose';

        $project_id = $this->input->get('project_id');

        if (!empty($project_id)) {
            $data['project'] = $project = row_select('project', ['ID' => $project_id], []);

            $data['title'] = 'Chọn từ khóa dự án ' . $project['name'];
        } else {

            $data['title'] = 'Chọn từ khóa tất cả dự án';
        }

        $data['current_page'] = 'keyword-choose';
        $this->load->view('admin/index', $data);
    }

    public function post_update($id)
    {

        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }

        $keyword = row_select('keyword', ['ID' => $id], []);
        $ctv_id  = row_select('users', ['username' => $username], ['id'])['id'];

        if ($role == 'admin' || $ctv_id == $keyword['ctv']) {

            $data['folder'] = 'page';
            $data['file']   = 'post_update';
            $data['keyword'] = $keyword;
            $data['current_page'] = 'keyword';
            $data['title'] = 'Cập nhật bài viết';
            $this->load->view('admin/index', $data);
        } else {
            redirect(base_url('keyword'));
        }
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
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }

        $data['keyword']  = $keyword = row_select('keyword', ['ID' => $id], []);

        $data['folder'] = 'page';
        $data['file'] = 'preview';

        $data['current_page'] = 'keyword';
        $data['title'] = 'Xem trước bài viết cho keyword ' . @$keyword['name'];
        $this->load->view('admin/index', $data);
    }

    //==============Chức năng==============//
    public function auto_update()
    {
        $id = $this->input->post('ID');
        $content = $this->input->post('content');
        update('keyword', ['content' =>  $content], ['ID' => $id]);
    }

    public function upload_excel()
    {
        $this->load->view('upload_excel');
    }

    public function excel_keyword()
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
            echo '<p>Upload thành công</p><br><a href="' . base_url('keyword-choose') . '">Quay lại</a>';

            $this->load->view('upload_excel_progress', $data);
        }
    }

    //Chức năng keyword
    public function give_keyword()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id      = $this->input->post('ID');
        $ctv     = $this->input->post('ctv');
        update('keyword', ['ctv' => $ctv, 'dateTake' => date('Y/m/d H:i:s')], ['ID' => $id]);
        redirect(base_url('keyword-choose'));
    }
    public function edit_keyword_info()
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

        update('keyword', $data, ['ID' => $id]);
        redirect(base_url('preview/' . $id));
    }
    public function point_keyword()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id         = $this->input->post('ID');
        $point     = $this->input->post('point');
        update('keyword', ['point' => $point], ['ID' => $id]);
        redirect(base_url('keyword'));
    }
    public function note_keyword()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $id       = $this->input->post('ID');
        $note     = $this->input->post('note');
        update('keyword', ['note' => $note, 'finish' => 4], ['ID' => $id]);
        redirect(base_url('preview/' . $id));
    }
    public function customer_note_keyword()
    {
        $id       = $this->input->post('ID');
        $note     = $this->input->post('customer_note');
        update('keyword', ['customer_note' => $note, 'finish' => 4], ['ID' => $id]);
        redirect(base_url('preview/' . $id));
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
                update('keyword', ['finish' => 3, 'dateApproveMoney' => date('Y/m/d H:i:s')], ['ID' => $id]);
                break;
            case 'pay':
                update('keyword', ['finish' => 1], ['ID' => $id]);
                break;
        }
        redirect(base_url('preview/' . $id));
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
        if ($keyword['outline_check'] == 0) {
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
            if ($keyword['finish_outline'] != 1) {

                $finish_outline   = $this->input->post('finish_outline');
                $outline          = $this->input->post('outline');

                if ($finish_outline == 'on') {
                    $finish_outline = 2;
                } else {
                    $finish_outline = 0;
                }

                $data['finish_outline'] = $finish_outline;
                if ($keyword['finish'] == 4) {
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
        $deadline       = $this->input->post('deadline');
        $outline_check  = $this->input->post('outline_check');

        $data = array(
            'name'             => $name,
            'project'          => $project,
            'keyword_type'     => $keyword_type,
            'word'             => $word,
            'deadline'         => $deadline,
            'outline_check'    => $outline_check,
            'dateCreate'       => date('Y/m/d H:i:s')
        );
        insert('keyword', $data);
        redirect(base_url('keyword-choose'));
    }
         public function addd_keyword()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }
      
        $name           = $this->input->post('name');
        $sub_name       = $this->input->post('sub_name');
        $keyword_note  = $this->input->post('keyword_note');
        $project        = $this->input->post('project');
        $keyword_type   = $this->input->post('keyword_type');
        $word           = $this->input->post('word');
        $deadline       = $this->input->post('deadline');
        $outline_check  = $this->input->post('outline_check');

        $data = array(
            
            'name'             => $name,
            'sub_name'         => $sub_name,
            'keyword_note'    => $keyword_note,
            'project'          => $project,
            'keyword_type'     => $keyword_type,
            'word'             => $word,
            'deadline'         => $deadline,
            'outline_check'    => $outline_check,
            'dateCreate'       => date('Y/m/d H:i:s')
        );
        insert('keyword', $data);
        redirect(base_url('pcn'));
    }


       public function pcn_keyword()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
       
        $id      = $this->input->post('ID');
        $ctv     = $this->input->post('ctv');
       
        update('keyword', ['ctv' => $ctv, 'dateTake' => date('Y/m/d H:i:s')], ['ID' => $id]);
          redirect(base_url('pcn'));
        
        
    }
      public function posted()
    {
      
          $id             = $this->input->post('ID');
      
        $status         =   $this->input->post('status');
      
          $check       = $this->input->post('check');
          $check_page  = $this->input->post('check_page');
          $project_id     = $this->input->post('project');

        $data = array(
            
           
             'posted'               => $check,
         
        );
          update('keyword', $data, ['id' =>   $id   ]);
          if($check_page == 9){
              if(empty($project_id)){
              redirect(base_url('keyword?status='.$status));}else{
                  $project = '&project_id='.$project_id;
                  redirect(base_url('keyword?status='.$status.$project));

              }
          }else{
        redirect(base_url('preview/'.$id));}
    }
        public function check_posted()
    {
      
        $id             = $this->input->post('ID');
       
        $status         =   $this->input->post('status');
          $check       = $this->input->post('check');
        

        $data = array(
            
           
             'posted'               => $check,
         
        );
          update('keyword', $data, ['id' =>   $id   ]);
          $check_page  = $this->input->post('check_page');
        if($check_page == 9){
              redirect(base_url('keyword?status='. $status ));
          }else{
        redirect(base_url('preview/'.$id));}
    }

public function posted_customer()
    {
      
        $id             = $this->input->post('ID');
      
        $status         =   $this->input->post('status');
      
          $check_customer       = $this->input->post('check_customer');
          $check_page  = $this->input->post('check_page');
          $project_id     = $this->input->post('project');

        $data = array(
            
           
             'posted_customer'               => $check_customer,
         
        );
          update('keyword', $data, ['id' =>   $id   ]);
          if($check_page == 9){
              if(empty($project_id)){
              redirect(base_url('keyword?status='.$status));}else{
                  $project = '&project_id='.$project_id;
                  redirect(base_url('keyword?status='.$status.$project));

              }
          }else{
        redirect(base_url('preview/'.$id));}
    }



    public function check_posted_customer()
    {
      
        $id             = $this->input->post('ID');
       
        $status         =   $this->input->post('status');
          $check_customer       = $this->input->post('check_customer');
        

        $data = array(
            
           
             'posted_customer'               => $check_customer,
         
        );
          update('keyword', $data, ['id' =>   $id   ]);
          $check_page  = $this->input->post('check_page');
        if($check_page == 9){
              redirect(base_url('keyword?status='. $status ));
          }else{
        redirect(base_url('preview/'.$id));}
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

    public function choose_keyword_edit()
    {
        $username = $this->session->userdata('username');
        if (!isset($username)) {
            redirect(base_url('login'));
        }
        $id = $this->input->post('ID');
        $name = $this->input->post('name');
        $word = $this->input->post('word');
        $project = $this->input->post('project');
        $keyword_type = $this->input->post('keyword_type');
        $deadline = $this->input->post('deadline');

        $data = array(
            'ID' => $id,
            'name' => $name,
            'word' => $word,
            'project' => $project,
            'keyword_type' => $keyword_type,
            'deadline' => $deadline,
        );

        update('keyword', $data, ['ID' => $id]);
        redirect(base_url('keyword-choose'));
    }
    public function delete_keyword()
    {
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url('ctv'));
        }
        $id = $this->input->get('ID');
        delete('keyword', ['ID' => $id]);
        redirect(base_url('keyword'));
    }
    public function word_count()
    {
        $content = $this->input->post('content');
        echo str_word_count(convert_name(strip_tags($content)));
    }
  // Update

           public function Update_sub_keyword()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $id      = $this->input->post('ID');
        $sub_keyword     = $this->input->post('sub_keyword');
       
        update('keyword', ['sub_name' => $sub_keyword], ['ID' => $id]);//phieu;
          redirect(base_url('preview/'.$id));
        
        
    }
      public function Update_main_keyword()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $id      = $this->input->post('ID');
        $sub_keyword     = $this->input->post('main_keyword');
       
        update('keyword', ['name' => $sub_keyword], ['ID' => $id]);//phieu;
          redirect(base_url('preview/'.$id));
        
        
    }
          public function Update_metaTitle()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $id      = $this->input->post('ID');
        $sub_keyword     = $this->input->post('metaTitle');
       
        update('keyword', ['metaTitle' => $sub_keyword], ['ID' => $id]);//phieu;
          redirect(base_url('preview/'.$id));
        
        
    }
               public function Update_keyword_note()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $id      = $this->input->post('ID');
        $keyword_note     = $this->input->post('keyword_note');
       
        update('keyword', ['keyword_note' => $keyword_note], ['ID' => $id]);//phieu;
          redirect(base_url('preview/'.$id));
        
        
    }

     public function Update_word()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $id      = $this->input->post('ID');
        $word     = $this->input->post('word');
       
        update('keyword', ['word' => $word], ['ID' => $id]);//phieu;
          redirect(base_url('preview/'.$id));
        
        
    }
    public function Update_thisType()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $id_thisType      = $this->input->post('id_thisType');
        $check_thisType     = $this->input->post('check_thisType');
       
        update('keyword', ['keyword_type' => $id_thisType], ['ID' => $check_thisType]);//phieu;
          redirect(base_url('keyword/' ));
        
        
    }
     public function Update_result_select()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $result_select      = $this->input->post('result_select');
        $check_result_select     = $this->input->post('check_result_select');
       
        update('keyword', ['project' => $result_select], ['ID' =>  $check_result_select]);//phieu;
          redirect(base_url('preview/'.$check_result_select));
        
        
    }

    public function Update_deadline()
    {
          $role = $this->session->userdata('role');
        if ($role != 'admin') {
            redirect(base_url());
            
        }
    
        $deadline      = $this->input->post('deadline');
        $check_deadline     = $this->input->post('check_deadline');
       
        update('keyword', ['deadline' => $deadline ], ['ID' =>  $check_deadline]);//phieu;
          redirect(base_url('keyword/' ));
        
        
    }
    public function delete_ctv()
    {
        $role = $this->session->userdata('role');

        if (isset($role) && $role == 'admin') {
            $id  = $this->input->get('id');
            $update_ctv = null;

            update('keyword', ['ctv' => $update_ctv], ['ID' => $id]);//phieu;
          redirect(base_url('pcn'));
        }
        redirect(base_url('keyword'));
    }


}

function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
function convert_name($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    return $str;
}
