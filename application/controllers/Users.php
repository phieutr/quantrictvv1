<?php
class Users extends CI_Controller
{
    // Log in user
    public function login()
    {

        $this->load->model('User_model');

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');


        if ($this->form_validation->run() === FALSE) {
            $this->load->view('user/login');
        } else {

            // Get username
            $username = $this->input->post('username');
            // Get and encrypt the password
            $password = md5($this->input->post('password'));

            // Login user
            $role = $this->User_model->login($username, $password);

            if ($role) {
                // Create session
                $user_data = array(
                    'username' => $username,
                    'role' => $role,
                    'logged_in' => true
                );

                $this->session->set_userdata($user_data);

                // Set message
                $this->session->set_flashdata('user_loggedin', 'You are now logged in');

                redirect(base_url('dashboard'));
            } else {
                // Set message
                $this->session->set_flashdata('login_failed', 'Tài khoản hoặc mật khẩu không chính xác');
                redirect(base_url('login'));
            }
        }
    }
    //register
    public function register()
    {
        $this->load->view('user/Register');
    }

    // Log user out
    public function logout()
    {
        // Unset user data
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('username');

        // Set message
        $this->session->set_flashdata('user_loggedout', 'Đăng xuất thành công');

        redirect(base_url('login'));
    }
}
