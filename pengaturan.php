<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class pengaturan extends CI_Controller {

	function __construct() { 
		parent::__construct(); 
		$this->load->model('user_model');
		$this->load->model('kerjasama_model');
		$this->load->helper(array('form', 'url'));
	}

	
	public function pengaturanmenu(){
		$data['daftarperguruantinggi'] = $this->kerjasama_model->getDaftarNamaPT(); 
		$this->load->view('admin/pengaturanMenu', $data);
	}

	public function pengaturanPengguna(){
		$data['user'] = $this->user_model->getAllDataUser(); 
		$this->load->view('admin/pengaturanPengguna', $data);
	}

	public function hapusDataUser($id){
		$this->user_model->deleteDataUser($id);
		redirect(base_url().'admin/pengaturan/pengaturanPengguna');
	}

	public function infoSistem(){
		$this->load->view('admin/infoTentangSistem');
	}

	public function ubahPassword(){
		$this->form_validation->set_rules('password','Old Password','required|trim');
		$this->form_validation->set_rules('passwordbaru','New Password','required|trim|min_length[8]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','required|trim|matches[passwordbaru]');

		$idUser = $this->session->userdata('idUser');

		if($this->form_validation->run() == FALSE){
		    $this->load->view('admin/ubahPassword');
		}
		else {
			$password = md5($this->input->post('password')); 
		    $cek_old = $this->user_model->checkOldPassword($password);
		   if ($cek_old == False){
		    	$this->session->set_flashdata('error','Password tidak cocok!' );
		    	$this->load->view('admin/ubahPassword');
		   }else{
			$newPass = 	md5($this->input->post('passwordbaru'));
			$data = array(
				'password'	=> $newPass      
				);

		    $this->user_model->changePassword($idUser, $data);
		    $this->session->set_flashdata('succses','Password berhasil diubah.');
		    redirect(base_url().'admin/pengaturan/ubahPassword');
			}
		
		}
	}
}