<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class kegiatan extends CI_Controller {

	function __construct() { 
		parent::__construct(); 
		$this->load->model('kegiatan_model');
		$this->load->helper(array('form', 'url'));
        $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->library('dompdf_gen');
	}
	
	public function infoKBRI()
	{
		$data['query']=$this->kegiatan_model->getDataInfoKBRI();
		$this->load->view('admin/infoKBRI', $data);
	}

	public function uploadDataInfoKBRI(){
		$this->load->view('admin/uploadInfoKBRI');
	}

	public function importDataInfoKBRI(){
          $fileName = $this->input->post('file', TRUE);

          $config['upload_path'] = './upload/'; 
          $config['file_name'] = $fileName;
          $config['allowed_types'] = 'xls|xlsx|csv|ods|ots';
          $config['max_size'] = 10000;

          $this->load->library('upload', $config);
          $this->upload->initialize($config); 
          
          if (!$this->upload->do_upload('file')) {
           $error = array('error' => $this->upload->display_errors());
           $this->session->set_flashdata('pesan',"<div class=\"col-md-8\"><div class=\"alert alert-warning\" id=\"alert\" style=\"margin-left: 345px\">Ada Kesalahan Dalam Penguploadan.</div></div>"); 
           redirect(base_url().'admin/kegiatan/uploadInfoKBRI');
          } else {
           $media = $this->upload->data();
           $inputFileName = 'upload/'.$media['file_name'];
           
           try {
            $inputFileType = IOFactory::identify($inputFileName);
            $objReader = IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
           } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
           }

           $sheet = $objPHPExcel->getSheet(0);
           $highestRow = $sheet->getHighestRow();
           $highestColumn = $sheet->getHighestColumn();


           for ($row = 2; $row <= $highestRow; $row++){  
             $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
               NULL,
               TRUE,
               FALSE);

                $data = array(
                    "tanggal"     => PHPExcel_Style_NumberFormat::toFormattedString($rowData[0][1], 'YYYY-MM-DD'),
                    "negara"    	=> $rowData[0][2],
                    "judul"   		=> $rowData[0][3],
                    "no"      		=> $rowData[0][4],
                    "uraian"    	=> $rowData[0][5],
                    "keterangan"  => $rowData[0][6],
                    
                );
                 
                //sesuaikan nama dengan nama tabel
                $this->kerjasama_model->insertDataInfoKBRI($data);
                delete_files($media['file_path']);
                     
            }
        $this->session->set_flashdata('succses','Upload Data Berhasil');
        redirect(base_url().'admin/kegiatan/infoKBRI');
        }
    }
    
	public function cetakDataInfoKBRI(){
        $data['query'] = $this->kegiatan_model->getDataInfoKBRI(); 
        $this->load->view('admin/cetakDataInfoKBRI', $data);
 
        $paper_size  = 'A4'; //paper size
        $orientation = 'landscape'; //tipe format kertas
        $html = $this->output->get_output();
 
        $this->dompdf->set_paper($paper_size, $orientation);
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("dataInfoKBRI.pdf", array('Attachment'=>0));
    }

	public function simpanDataInfoKBRI(){
		$data['query'] = $this->kegiatan_model->getDataInfoKBRI();
    $this->load->view('admin/simpanexcelInfoKBRI', $data);
	}

    public function tambahDataInfoKBRI() {
        $this->form_validation->set_rules('negara','negara','required');
        $this->form_validation->set_rules('judul','judul','required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/tambahDataInfoKBRI');
        } else {
                $data = array (
                'tanggal'     => $this->input->post('tanggal'),
                'negara'      => $this->input->post('negara'),
                'judul'       => $this->input->post('judul'),
                'no'          => $this->input->post('no'),
                'uraian'      => $this->input->post('uraian'),
                'keterangan'  => $this->input->post('keterangan'),
                'file'        => $this->input->post('file')
                );
            $this->kerjasama_model->insertDataInfoKBRI($data);
            $this->session->set_flashdata('succses','Data Berhasil Ditambahkan.');
            redirect(base_url().'admin/kegiatan/infoKBRI');
            }
    } 

    public function editDataInfoKBRI($id){
         $this->form_validation->set_rules('negara','negara','required');
        $this->form_validation->set_rules('judul','judul','required');

        if ($this->form_validation->run() === FALSE) {
            $data['detail'] = $this->kegiatan_model->detailDataInfoKBRI($id);
            $this->load->view('admin/editDataInfoKBRI', $data);
        } else {
                $data = array (
                'id'          => $this->input->post('id'),
                'id_kbri'     => $this->input->post('id_kbri'),
				        'tanggal'     => $this->input->post('tanggal'),
                'negara'      => $this->input->post('negara'),
                'judul'       => $this->input->post('judul'),
                'no'          => $this->input->post('no'),
                'uraian'      => $this->input->post('uraian'),
                'keterangan'  => $this->input->post('keterangan'),
                'file'        => $this->input->post('file')
                );

            $this->kegiatan_model->updateDataInfoKBRI($data);
            $this->session->set_flashdata('succses','Data Berhasil Diubah.');
            redirect(base_url().'admin/kegiatan/infoKBRI');
            }
    }

	public function hapusDataInfoKBRI($id) {
		$this->kegiatan_model->deleteDataInfoKBRI($id);
		redirect(base_url().'admin/kegiatan/infoKBRI');
	} 

	//=======================================================================================//

	public function infoRistek()
	{
		$this->load->view('admin/infoRistekdikti');
	}


	//=======================================================================================//
	//kode Untuk Sub Menu Instansi
	public function instansi()
	{
		$data['instansi'] = $this->kegiatan_model->getDataInstansi(); 
		$this->load->view('admin/instansi', $data);
	}

	public function cariinstansi()
	{
		$keyword = $this->input->post('keyword');
		$data['instansi'] = $this->kegiatan_model->searchDataInstansi($keyword); 
		$this->load->view('admin/instansi', $data);
	}

	public function hapusDataInstansi($id) {
		$this->kegiatan_model->deleteDataInstansi($id);
		redirect(base_url().'admin/kegiatan/instansi');
	} 
}
