<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class NoteJs extends CI_Controller{
   public function __construct()
   {  
      parent::__construct();
      $this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');
      $this->load->model('listNotes_model');
   }
   public function index()
   {
      $this->load->view('preview');
   }
  public function insert()
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('checkNote', 'checkNote', 'required');
				$this->form_validation->set_rules('checkNote', 'checkNote', 'required');
				$id = $this->input->post('checkNote');
				$note = $this->input->post('Name');
			if ($this->form_validation->run() == FALSE)
          {
				$data = array('responce' => 'error', 'message' => validation_errors());
			 }
          else 
         {
           $ajax_data = $this->input->post();
				if ($this->listNotes_model->insert_entry($ajax_data)) {
					$data = array('responce' => 'success', 'message' => 'Ghi chú thành công');
				} else {
					$data = array('responce' => 'error', 'message' => 'Không thêm được bản ghi');
				}
			}
           update('keyword', ['customer_note' => $note, 'finish' => 4], ['ID' => $id]);
			echo json_encode($data);
		} else {
			echo "No direct script access allowed";
		}
	}
	public function fetch(){
		if($this->input->is_ajax_request()){
			if($posts = $this->listNotes_model->get_entries()){
				$data = array('responce' => 'success','posts' => $posts);
				
			}else{
				$data = array('responce' => 'error','massage' => 'Không tìm thấy dữ liệu');
			}
			echo json_encode($data);
		}else{
			echo "không cho phép truy cập data";
		}
	}
		public function fetchh(){
		if($this->input->is_ajax_request()){
			if($posts = $this->listNotes_model->get_entriess()){
				$data = array('responce' => 'success','posts' => $posts);
				
			}else{
				$data = array('responce' => 'error','massage' => 'Không tìm thấy dữ liệu');
			}
			echo json_encode($data);
		}else{
			echo "không cho phép truy cập data";
		}
	}
// 	public function check(){
// 		if($this->input->is_ajax_request()){
// 		 $id = $this->input->post('id');

//     if(empty($id)){
//        echo 'error';
//     }else {
// 		if(isset($_POST['id'])){
//     $id  = $this->input->post('id');
//    $todo = row_select('listNotes', ['id' => $id], ['name', 'checkNote', 'checked']);
	
// 	 $checked = $todo['checked'];
// 	 $uChecked = $checked ? 0 : 1;
// 	  $res = update('listNotes', ['checked' => $uChecked], ['id' => $id]);//phieu;
	  
//        if ($post = $this->listNotes_model->checked($id)) {
// 				$data = array('responce' => 'success', 'post' => $post);
// 			} else {
// 				$data = array('responce' => 'error', 'message' => 'failed to fetch record');
// 			}
// 			echo json_encode($data);
      
//     }
// 	}
// }else{
// 			echo "không cho phép truy cập data";
// 		}
// }




	public function delete(){
		if($this->input->is_ajax_request()){
			$del_id = $this->input->post('del_id');
			if($this->listNotes_model->delete_entry($del_id)){
				$data = array('responce' => 'success');
			}else{
				$data = array('responce' => 'error');
			}
			echo json_encode($data);
		}else{
			echo "không cho phép truy cập data";
		}
	}
		public function edit()
	{
		if ($this->input->is_ajax_request()) {
			$edit_id = $this->input->post('edit_id');

			if ($post = $this->listNotes_model->edit_entry($edit_id)) {
				$data = array('responce' => 'success', 'post' => $post);
			} else {
				$data = array('responce' => 'error', 'message' => 'failed to fetch record');
			}
			echo json_encode($data);
		} else {
			echo "No direct script access allowed";
		}
	}
	
	public function update(){
		if($this->input->is_ajax_request()){

         $this->form_validation->set_rules('edit_name', 'Name', 'required');
			$this->form_validation->set_rules('edit_checkNote', 'checkNote', 'required');
			if ($this->form_validation->run() == FALSE)
          {
				$data = array('responce' => 'error', 'message' => validation_errors());
			 }
          else 
         {
           			$data['id'] = $this->input->post('edit_record_id');
			         $data['name'] = $this->input->post('edit_name');
			         $data['checkNote'] = $this->input->post('edit_checkNote');

				if ($this->listNotes_model->update_entry($data)) {
					$data = array('responce' => 'success', 'message' => 'Cập nhật thành công');
				} else {
					$data = array('responce' => 'error', 'message' => 'Cập nhật không thành công');
				}
			}

			echo json_encode($data);

		}else{
			echo "No direct script access allowed";
		}
	}

	public function complete_note(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');	 
			if ($this->listNotes_model->complete_note($id)) {
				$data = array('responce' => 'success', 'message' => 'Ghi chú thành công');
			} else {
				$data = array('responce' => 'error', 'message' => 'Không thêm được bản ghi');
			}
			echo json_encode($data);
			
	}
 }
 	public function undoNotes(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			$todo = row_select('listnotes', ['id' => $id], ['name', 'checkNote','fullname','checked']); 
			 update('keyword', ['finish' => 4], ['ID' => $todo['checkNote']]);
			if ($this->listNotes_model->undoNotes($id)) {
				$data = array('responce' => 'success', 'message' => 'Ghi chú thành công');
			} else {
				$data = array('responce' => 'error', 'message' => 'Không thêm được bản ghi');
			}
			echo json_encode($data);
			
	}
 }
}