<?php

class Ctv_timeline extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public
        $color1         = '#007bff',
        $color2         = '#ffc107',
        $color3         = 'red';

    public function timeline()
    {
        $data['role'] = $role = $this->session->userdata('role');
        $data['username'] = $username = $this->session->userdata('username');
        if ($role != 'admin') {
            redirect(base_url());
        }
        $data['title'] = 'Theo dõi cộng tác viên';

        $keywords = result_select('keyword', ['ctv!=' => null], []);
        $data['ctv_id'] = $ctv_id = $this->input->get('ctv_id');
        if (!empty($ctv_id)) {
            $fullname = row_select('users', ['id' => $ctv_id], [])['fullname'];
            $keywords = result_select('keyword', ['ctv' => $ctv_id], []);
            $data['title'] = 'Theo dõi cộng tác viên ' . $fullname;
        }
        $timeline_array = [];
        foreach ($keywords as $key => $value) {
            if ($value['finish'] == 1 || $value['finish'] == 3) {
                unset($keywords[$key]);
            }
        }
        foreach ($keywords as $key => $keyword) {

            $color = $this->color3;
            if ($keyword['finish_outline'] != 1 && $keyword['outline_check'] == 1) {
                $color = $this->color2;
            } else {
                if ($keyword['finish'] == 2) {
                    $color = $this->color1;
                }
            }

            $time_data = array(
                'title' => $keyword['ID'] . '- ' . $keyword['name'],
                'url' => base_url('preview/' . $keyword['ID']),
                'start' => $keyword['deadline'],
                'allDay' => true,
                'backgroundColor' => $color,
            );
            $timeline_array[] = $time_data;
        }
        $data['timeline_js_array'] = $timeline_js_array = json_encode($timeline_array);

        $data['folder'] = 'page';
        $data['file'] = 'ctv_timeline';

        $data['current_page'] = 'dashboard';

        $this->load->view('admin/index', $data);
    }

    //==============Chức năng==============//

}
function printR($myarray)
{
    echo "<pre>";
    print_r($myarray);
    echo "</pre>";
}
