<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class kerjasama extends CI_Controller {

	function __construct() { 
		parent::__construct(); 
		$this->load->model('kerjasama_model');
		$this->load->helper(array('form', 'url', 'download'));
        $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->library('dompdf_gen');
        $this->load->library('form_validation');
        $this->load->library('pagination');
	}

	//Code Untuk menu Usulan Kerjasama 
	public function usulanKS()
	{
		$data['usulanKS'] = $this->kerjasama_model->getDataUsulanKS(); 
		$this->load->view('admin/usulanKS', $data);
	}

	public function cariusulanKS()
	{
		$key = $this->input->get('key');
        $search = array(
            'tanggal'=> $key,
            'nama'=> $key,
            'instansi'=> $key,
            'jabatan'=> $key,
            'alamat'=> $key,
            'telepon'=> $key,
            'email'=> $key,
            'pesan'=> $key,
            'jenisks'=> $key
        ); 
		$data['usulanKS'] = $this->kerjasama_model->searchDataUsulanKS($search); 
		$this->load->view('admin/usulanKS', $data);
	}

	//==================================================================================//

	//Code Untuk sub menu Kerja Sama K/L 
    public function kerjasamaKL(){
        $page  = $this->input->get('per_page');
        $batas = 10; //jumlah data yang ditampilkan per halaman
        if(!$page):     //jika page bernilai kosong maka batas akhirna akan di set 0
           $offset = 0;
        else:
           $offset = $page; // jika tidak kosong maka nilai batas akhir nya akan diset nilai page terakhir
        endif;

        $this->session->unset_userdata('key');

        $config['page_query_string'] = TRUE; //mengaktifkan pengambilan method get pada url default
        $config['base_url']    = base_url().'admin/kerjasama/kerjasamaKL?';   //url yang muncul ketika tombol pada paging diklik
        $config['total_rows']  = $this->kerjasama_model->countDataKerjasamaKL(); // jumlah total data
        $config['per_page']    = $batas; //batas sesuai dengan variabel batas
 
        $config['uri_segment'] = $page; //merupakan posisi pagination dalam url pada kesempatan ini saya menggunakan method get untuk menentukan posisi pada url yaitu per_page
 
        $config['full_tag_open']    = '<ul class="pagination">';
        $config['full_tag_close']   = '</ul>';
        $config['first_link']       = '&laquo; First';
        $config['first_tag_open']   = '<li class="prev page">';
        $config['first_tag_close']  = '</li>';
         
        $config['last_link']        = 'Last &raquo;';
        $config['last_tag_open']    = '<li class="next page">';
        $config['last_tag_close']   = '</li>';
 
        $config['next_link']        = 'Next &rarr;';
        $config['next_tag_open']    = '<li class="next page">';
        $config['next_tag_close']   = '</li>';
 
        $config['prev_link']        = '&larr; Prev';
        $config['prev_tag_open']    = '<li class="prev page">';
        $config['prev_tag_close']   = '</li>';
 
        $config['cur_tag_open']     = '<li class="current"><a href="">';
        $config['cur_tag_close']    = '</a></li>';
 
        $config['num_tag_open']     = '<li class="page">';
        $config['num_tag_close']    = '</li>';
        $this->pagination->initialize($config);

        $data['paging']            = $this->pagination->create_links();
        $data['jumlahpage']        = $page;
        $data['kerjasamakl']       = $this->kerjasama_model->getDataKerjasamaKL($batas, $offset); 
        // $data['instansi'] = $this->kerjasama_model->getDaftarNamaLembaga();
        $this->load->view('admin/kerjasamaKL', $data);
    }

    public function cariDataKerjasamaKL(){
        $key    = $this->input->get('key'); //method get key
        $page   = $this->input->get('per_page');  //method get per_page

        $search = array(
            'instansi'=> $key,
            'nomorKS'=> $key,
            'namaKS'=> $key,
            'mitra'=> $key,
            'bidangfokus'=> $key,
            'kategori'=> $key,
            'jenis'=> $key,
            'tempat'=> $key,
            'tanggal'=> $key,
            'berlaku'=> $key,
            'status'=> $key,
            'tglakhir'=> $key
        ); //array pencarian yang akan dibawa ke model

        $this->session->set_userdata('key', $key);

        $batas = 10; //jumlah data yang ditampilkan per halaman
        if(!$page):     //jika page bernilai kosong maka batas akhirna akan di set 0
           $offset = 0;
        else:
           $offset = $page; // jika tidak kosong maka nilai batas akhir nya akan diset nilai page terakhir
        endif;
 
        $config['page_query_string'] = TRUE; //mengaktifkan pengambilan method get pada url default
        $config['base_url']    = base_url().'admin/kerjasama/kerjasamaKL?key='.$key;   //url yang muncul ketika tombol pada paging diklik
        $config['total_rows']  = $this->kerjasama_model->countDataKerjasamaKLSearch($search); // jumlah total data
        $config['per_page']    = $batas; //batas sesuai dengan variabel batas
 
        $config['uri_segment'] = $page; //merupakan posisi pagination dalam url pada kesempatan ini saya menggunakan method get untuk menentukan posisi pada url yaitu per_page
 
        $config['full_tag_open']   = '<ul class="pagination">';
        $config['full_tag_close']  = '</ul>';
        $config['first_link']      = '&laquo; First';
        $config['first_tag_open']  = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
 
        $config['last_link']       = 'Last &raquo;';
        $config['last_tag_open']   = '<li class="next page">';
        $config['last_tag_close']  = '</li>';
 
        $config['next_link']       = 'Next &rarr;';
        $config['next_tag_open']   = '<li class="next page">';
        $config['next_tag_close']  = '</li>';
 
        $config['prev_link']       = '&larr; Prev';
        $config['prev_tag_open']   = '<li class="prev page">';
        $config['prev_tag_close']  = '</li>';
 
        $config['cur_tag_open']    = '<li class="current"><a href="">';
        $config['cur_tag_close']   = '</a></li>';
 
        $config['num_tag_open']    = '<li class="page">';
        $config['num_tag_close']   = '</li>';

        $this->pagination->initialize($config);

        $data['paging']     = $this->pagination->create_links();
        $data['jumlahpage'] = $page;
        $data['kerjasamakl'] = $this->kerjasama_model->getDataKerjasamaKL($batas, $offset, $search); 
        $this->load->view('admin/kerjasamaKL', $data);
    }

	public function cetakDataKerjasamaKL(){
        $key    = $this->session->userdata('key'); 
        $search = array(
            'instansi'=> $key,
            'nomorKS'=> $key,
            'namaKS'=> $key,
            'mitra'=> $key,
            'bidangfokus'=> $key,
            'kategori'=> $key,
            'jenis'=> $key,
            'tempat'=> $key,
            'tanggal'=> $key,
            'berlaku'=> $key,
            'status'=> $key,
            'tglakhir'=> $key
        );

        if (!empty($key)) {
            $data['kerjasamakl'] = $this->kerjasama_model->getDataKerjasamaKLSaveExcel($search);
            $this->load->view('admin/simpanexcelPerkembangan', $data);  
        } else {
            $data['kerjasamakl'] = $this->kerjasama_model->getDataKerjasamaKLSaveExcel();
            $this->load->view('admin/simpanexcelPerkembangan', $data);  
        }  
 
        $paper_size  = 'A4'; //paper size
        $orientation = 'landscape'; //tipe format kertas
        $html = $this->output->get_output();
 
        $this->dompdf->set_paper($paper_size, $orientation);
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("DataKerjasamaKL.pdf", array('Attachment'=>0));
    }

    public function simpanDataKerjasamaKL() {
        $key    = $this->session->userdata('key'); 
        $search = array(
            'instansi'=> $key,
            'nomorKS'=> $key,
            'namaKS'=> $key,
            'mitra'=> $key,
            'bidangfokus'=> $key,
            'kategori'=> $key,
            'jenis'=> $key,
            'tempat'=> $key,
            'tanggal'=> $key,
            'berlaku'=> $key,
            'status'=> $key,
            'tglakhir'=> $key
        );

        if (!empty($key)) {
            $data['kerjasamakl'] = $this->kerjasama_model->getDataKerjasamaKLSaveExcel($search);
            $this->load->view('admin/simpanexcelPerkembangan', $data);  
        } else {
            $data['kerjasamakl'] = $this->kerjasama_model->getDataKerjasamaKLSaveExcel();
            $this->load->view('admin/simpanexcelPerkembangan', $data);  
        }  
		  	
    }

	public function tambahDataKerjasamaKL() {
        $instansi   = $this->input->post('instansi');
        $namaKS     = $this->input->post('namaKS');
        $mitra      = $this->input->post('mitra');

        $this->form_validation->set_rules('instansi','instansi','required');
        $this->form_validation->set_rules('mitra','mitra','required');
        $this->form_validation->set_rules('namaKS','namaKS','required');
        $this->form_validation->set_rules('kategori','kategori','required');
        $this->form_validation->set_rules('jenis','jenis','required');  

        if ($this->form_validation->run() === FALSE) {
            $data['instansi'] = $this->kerjasama_model->getDaftarNamaLembaga();
            $this->load->view('admin/tambahDataKerjasamaKL', $data);
        } else {
                $data['instansi']          = $instansi;
                $data['nomorKS']           = $this->input->post('nomorKS');
                $data['namaKS']            = $namaKS;
                $data['mitra']             = $mitra;
                $data['bidangfokus']       = $this->input->post('bidangfokus');
                $data['kategori']          = $this->input->post('kategori');
                $data['jenis']             = $this->input->post('jenis');
                $data['tempat']            = $this->input->post('tempat');
                $data['tanggal']           = $this->input->post('tanggal');
                $data['berlaku']           = $this->input->post('berlaku');
                $data['status']            = $this->input->post('status');
                $data['tglakhir']          = $this->input->post('tglakhir');
                $data['filedokumen']       = $this->input->post('filedokumen');
                $data['idUser']            = $this->session->userdata('idUser');

            // $this->kerjasama_model->insertDataKerjasamaKL($data);
            
            $fileName = $this->input->post('filedokumen');
            $config['upload_path']      = APPPATH. '../upload/dataKerjasamaKL/'; 
            $config['allowed_types']    = 'pdf';
            $config['file_name']        = $fileName;
            $config['max_size']         = '2048';
            $config['overwrite']        = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $cek = $this->kerjasama_model->checkDuplicateDataKL($instansi, $namaKS, $mitra);

            if (!$this->upload->do_upload('filedokumen')) {
                $error = array('error' => $this->upload->display_errors());
                //$this->session->set_flashdata('pesan',"<div class=\"col-md-6\"><div class=\"alert alert-warning\" id=\"alert\" style=\"margin-left: 345px\">Ada Kesalahan Dalam Penguploadan.</div></div>"); 
                $data['filedokumen']           = $this->input->post('filedokumen');

                if ($cek > 0) {
                    $this->session->set_flashdata('error','Data Sudah Ada.');
                    $data['instansi'] = $this->kerjasama_model->getDaftarNamaLembaga();
                    $this->load->view('admin/tambahDataKerjasamaKL', $data);
                } else {
                    $this->kerjasama_model->insertDataKerjasamaKL($data);
                    $this->session->set_flashdata('succses','Data Berhasil Ditambahkan.');
                    redirect(base_url().'admin/kerjasama/kerjasamaKL');

                }
            } else {
                $media = $this->upload->data();
                $data['filedokumen'] = $media['file_name'];

                // $cek = $this->kerjasama_model->checkDuplicateDataKL($instansi, $namaKS, $mitra);
                if ($cek > 0) {
                    $this->session->set_flashdata('error','Data Sudah Ada.');
                    $data['instansi'] = $this->kerjasama_model->getDaftarNamaLembaga();
                    $this->load->view('admin/tambahDataKerjasamaKL', $data);
                } else {
                    $this->kerjasama_model->insertDataKerjasamaKL($data);
                    $this->session->set_flashdata('succses','Data Berhasil Ditambahkan.');
                    redirect(base_url().'admin/kerjasama/kerjasamaKL');

                }
         
            }
        }
    } 

    // public function cekDataKerjasamaKL($instansi, $namaKS, $mitra){
    //     $instansi = $this->input->post('instansi');
    //     // $nomorKS  = $this->input->post('instansi');
    //     $namaKS   = $this->input->post('instansi');
    //     $mitra    = $this->input->post('instansi');
    //     return $this->kerjasama_model->checkDuplicateDataKL($instansi, $namaKS, $mitra);
    // }


    public function editDataKerjasamaKL($id){
        $this->form_validation->set_rules('instansi','instansi','required');
        $this->form_validation->set_rules('mitra','mitra','required');
        $this->form_validation->set_rules('kategori','kategori','required');
        $this->form_validation->set_rules('jenis','jenis','required');    

        $id = $this->uri->segment(4);

        if ($this->form_validation->run() === FALSE) {
            $data['detail'] = $this->kerjasama_model->detailDataKerjasamaKL($id);
            $data['instansi'] = $this->kerjasama_model->getDaftarNamaLembaga();
            $this->load->view('admin/editDataKerjasamaKL', $data);
        } else {
                $data['id']                = $this->input->post('id');
                $data['instansi']          = $this->input->post('instansi');
                $data['nomorKS']           = $this->input->post('nomorKS');
                $data['namaKS']            = $this->input->post('namaKS');
                $data['mitra']             = $this->input->post('mitra');
                $data['bidangfokus']       = $this->input->post('bidangfokus');
                $data['kategori']          = $this->input->post('kategori');
                $data['jenis']             = $this->input->post('jenis');
                $data['tempat']            = $this->input->post('tempat');
                $data['tanggal']           = $this->input->post('tanggal');
                $data['berlaku']           = $this->input->post('berlaku');
                $data['status']            = $this->input->post('status');
                $data['tglakhir']          = $this->input->post('tglakhir');
                $data['idUser']            = $this->session->userdata('idUser');
                //$data['filedokumen']       = $this->input->post('filedokumen');

            $fileName = $this->input->post('filedokumen', TRUE);
            $config['upload_path']      = APPPATH. '../upload/dataKerjasamaKL/'; 
            $config['allowed_types']    = 'pdf';
            $config['file_name']        = $fileName;
            $config['max_size']         = '2048';
            $config['overwrite']        = TRUE;


            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('filedokumen')) {
                $error = array('error' => $this->upload->display_errors());
                //$this->session->set_flashdata('pesan',"<div class=\"col-md-6\"><div class=\"alert alert-warning\" id=\"alert\" style=\"margin-left: 345px\">Ada Kesalahan Dalam Penguploadan.</div></div>"); 
                $data['filedokumen']           = $this->input->post('filedokumen');

                $this->kerjasama_model->updateDataKerjasamaKL($data);
                $this->session->set_flashdata('succses','Data Berhasil Diubah.');
                redirect(base_url().'admin/kerjasama/kerjasamaKL');
            } else {
                $upload = array('upload_data' => $this->upload->data());
                $media = $this->upload->data();
                $data['filedokumen'] = $media['file_name'];
                $this->kerjasama_model->updateDataKerjasamaKL($data);
                $this->session->set_flashdata('succses','Data Berhasil Diubah.');
                redirect(base_url().'admin/kerjasama/kerjasamaKL');
            }
        }
    }

	public function hapusDataKerjasamaKL($id) {
		$this->kerjasama_model->deleteDataKerjasamaKL($id);
		redirect(base_url().'admin/kerjasama/kerjasamaKL');
	} 


	//==================================================================================//

	//Code Untuk sub menu Kerja Sama Perguruan Tinggi 
    public function kerjasamaPT(){
        $page  = $this->input->get('per_page');
        $batas = 10; //jumlah data yang ditampilkan per halaman
        if(!$page):     //jika page bernilai kosong maka batas akhirna akan di set 0
           $offset = 0;
        else:
           $offset = $page; // jika tidak kosong maka nilai batas akhir nya akan diset nilai page terakhir
        endif;

        $this->session->unset_userdata('key');

        $config['page_query_string'] = TRUE; //mengaktifkan pengambilan method get pada url default
        $config['base_url']    = base_url().'admin/kerjasama/kerjasamaPT?';   //url yang muncul ketika tombol pada paging diklik
        $config['total_rows']  = $this->kerjasama_model->countDataPT(); // jumlah total data
        $config['per_page']    = $batas; //batas sesuai dengan variabel batas
 
        $config['uri_segment'] = $page; //merupakan posisi pagination dalam url 
 
        $config['full_tag_open']    = '<ul class="pagination">';
        $config['full_tag_close']   = '</ul>';
        $config['first_link']       = '&laquo; First';
        $config['first_tag_open']   = '<li class="prev page">';
        $config['first_tag_close']  = '</li>';
         
        $config['last_link']        = 'Last &raquo;';
        $config['last_tag_open']    = '<li class="next page">';
        $config['last_tag_close']   = '</li>';
 
        $config['next_link']        = 'Next &rarr;';
        $config['next_tag_open']    = '<li class="next page">';
        $config['next_tag_close']   = '</li>';
 
        $config['prev_link']        = '&larr; Prev';
        $config['prev_tag_open']    = '<li class="prev page">';
        $config['prev_tag_close']   = '</li>';
 
        $config['cur_tag_open']     = '<li class="current"><a href="">';
        $config['cur_tag_close']    = '</a></li>';
 
        $config['num_tag_open']     = '<li class="page">';
        $config['num_tag_close']    = '</li>';
        $this->pagination->initialize($config);

        $data['paging']            = $this->pagination->create_links();
        $data['jumlahpage']        = $page;
        $data['perguruantinggi'] = $this->kerjasama_model->getDataPT($batas, $offset); 
        $this->load->view('admin/PerguruanTinggi', $data);
    }

    public function cariDataPT(){
        $key    = $this->input->get('key'); //method get key
        $page   = $this->input->get('per_page');  //method get per_page

        $search = array(
            'namaPT'        => $key,
            'namamitra'     => $key,
            'negaramitra'   => $key,
            'kategori'      => $key,
            'kegiatanKS'    => $key,
            'hasil'         => $key,
            'tglmulai'      => $key,
            'tglakhir'      => $key
        ); //array pencarian yang akan dibawa ke model

        $this->session->set_userdata('key', $key);

        $batas = 10; //jumlah data yang ditampilkan per halaman
        if(!$page):     //jika page bernilai kosong maka batas akhirna akan di set 0
           $offset = 0;
        else:
           $offset = $page; // jika tidak kosong maka nilai batas akhir nya akan diset nilai page terakhir
        endif;
 
        $config['page_query_string'] = TRUE; //mengaktifkan pengambilan method get pada url default
        $config['base_url']    = base_url().'admin/kerjasama/kerjasamaPT?key='.$key;   //url yang muncul ketika tombol pada paging diklik
        $config['total_rows']  = $this->kerjasama_model->countDataPTSearch($search); // jumlah total data
        $config['per_page']    = $batas; //batas sesuai dengan variabel batas
 
        $config['uri_segment'] = $page; //merupakan posisi pagination dalam url
        $config['full_tag_open']   = '<ul class="pagination">';
        $config['full_tag_close']  = '</ul>';
        $config['first_link']      = '&laquo; First';
        $config['first_tag_open']  = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
 
        $config['last_link']       = 'Last &raquo;';
        $config['last_tag_open']   = '<li class="next page">';
        $config['last_tag_close']  = '</li>';
 
        $config['next_link']       = 'Next &rarr;';
        $config['next_tag_open']   = '<li class="next page">';
        $config['next_tag_close']  = '</li>';
 
        $config['prev_link']       = '&larr; Prev';
        $config['prev_tag_open']   = '<li class="prev page">';
        $config['prev_tag_close']  = '</li>';
 
        $config['cur_tag_open']    = '<li class="current"><a href="">';
        $config['cur_tag_close']   = '</a></li>';
 
        $config['num_tag_open']    = '<li class="page">';
        $config['num_tag_close']   = '</li>';

        $this->pagination->initialize($config);

        $data['paging']     = $this->pagination->create_links();
        $data['jumlahpage'] = $page;
        $data['perguruantinggi'] = $this->kerjasama_model->getDataPT($batas, $offset, $search); 
        $this->load->view('admin/PerguruanTinggi', $data);
    }
	
	public function uploadDataPT(){
		$this->load->view('admin/uploadkerjasamaPT');
	}

	public function importDataPT(){
          $fileName = $this->input->post('file', TRUE);

          $config['upload_path'] = './upload/'; 
          $config['file_name'] = $fileName;
          $config['allowed_types'] = 'xls|xlsx|csv';
          $config['max_size'] = '2048';

          $this->load->library('upload', $config);
          $this->upload->initialize($config); 
          
          if (!$this->upload->do_upload('file')) {
               $error = array('error' => $this->upload->display_errors());
               $this->session->set_flashdata('pesan',"<div class=\"col-md-8\"><div class=\"alert alert-warning\" id=\"alert\" style=\"margin-left: 345px\">Ada Kesalahan Dalam Penguploadan.</div></div>"); 
               redirect(base_url().'admin/kerjasama/uploadDataPT');
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

               if ($highestColumn != 'I') {
                    delete_files($media['file_path']);
                    $this->session->set_flashdata('error',"<div class=\"col-md-8\"><div class=\"alert alert-warning\" id=\"alert\" style=\"margin-left: 345px\">Pastikan Isi File Sesuai dengan Template.</div></div>");
                    $this->load->view('admin/uploadkerjasamaPT');
                } else {

                    for ($row = 2; $row <= $highestRow; $row++){  
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                        $namaPT = $rowData[0][1];
                        $namamitra = $rowData[0][2];
                        $kegiatanKS = $rowData[0][5];


                        $data = array(
                            "namaPT"        => $namaPT,
                            "namamitra"     => $namamitra,
                            "negaramitra"   => $rowData[0][3],
                            "kategori"      => $rowData[0][4],
                            "kegiatanKS"    => $kegiatanKS,
                            "hasil"         => $rowData[0][6],
                            "tglmulai"      => PHPExcel_Style_NumberFormat::toFormattedString($rowData[0][7], 'YYYY-MM-DD'),
                            "tglakhir"      => PHPExcel_Style_NumberFormat::toFormattedString($rowData[0][8], 'YYYY-MM-DD'),
                            "idUser"        => $this->session->userdata('idUser')
                        );

                        $cek = $this->kerjasama_model->checkDuplicateDataPT($namaPT, $namamitra, $kegiatanKS);

                        if ($cek <= 0) {
                            $this->kerjasama_model->insertDataPT($data);
                            delete_files($media['file_path']);
                            $this->session->set_flashdata('succses','Upload Data Berhasil');
                            redirect(base_url().'admin/kerjasama/kerjasamaPT');
                        }
                                               
                    }

                    if ($cek > 0) {
                        delete_files($media['file_path']);
                        $this->session->set_flashdata('error',"<div class=\"col-md-8\"><div class=\"alert alert-warning\" id=\"alert\" style=\"margin-left: 345px\">Data Sudah Ada.</div></div>");
                        $this->load->view('admin/uploadkerjasamaPT');
                    }
                }
        }
    }
    
	public function cetakDataPT(){
        $key    = $this->session->userdata('key'); 
        $search = array(
            'namaPT'        => $key,
            'namamitra'     => $key,
            'negaramitra'   => $key,
            'kategori'      => $key,
            'kegiatanKS'    => $key,
            'hasil'         => $key,
            'tglmulai'      => $key,
            'tglakhir'      => $key
        );

        if (!empty($key)) {
            $data['perguruantinggi'] = $this->kerjasama_model->getDataPTSaveExcel($search); 
            $this->load->view('admin/cetakDataKerjasamaPT', $data);
        } else {

            $data['perguruantinggi'] = $this->kerjasama_model->getDataPTSaveExcel(); 
            $this->load->view('admin/cetakDataKerjasamaPT', $data);
        }    
        
        $paper_size  = 'A4'; //paper size
        $orientation = 'landscape'; //tipe format kertas
        $html = $this->output->get_output();
 
        $this->dompdf->set_paper($paper_size, $orientation);
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("dataPerguruanTinggi.pdf", array('Attachment'=>0));
    }

	public function simpanDataPT(){
        $key    = $this->session->userdata('key'); 
        $search = array(
            'namaPT'        => $key,
            'namamitra'     => $key,
            'negaramitra'   => $key,
            'kategori'      => $key,
            'kegiatanKS'    => $key,
            'hasil'         => $key,
            'tglmulai'      => $key,
            'tglakhir'      => $key
        );

        if (!empty($key)) {
            $data['perguruantinggi'] = $this->kerjasama_model->getDataPTSaveExcel($search); 
            $this->load->view('admin/simpanexcelPT', $data);
        } else {

            $data['perguruantinggi'] = $this->kerjasama_model->getDataPTSaveExcel(); 
            $this->load->view('admin/simpanexcelPT', $data);
        }    
	}

    public function downloadTemplate(){
         force_download('upload/template/Data Perguruan Tinggi.xlsx', NULL);
    }

    public function tambahDataPT() {
        $namaPT     = $this->input->post('namaPT');
        $namamitra  = $this->input->post('namamitra');
        $kegiatanKS = $this->input->post('kegiatanKS');

        $this->form_validation->set_rules('namaPT','nama perguruan tinggi','required');
        $this->form_validation->set_rules('namamitra','nama mitra','required');
        $this->form_validation->set_rules('negaramitra','negara mitra','required');  
        $this->form_validation->set_rules('kategori','kategori','required');  

        if ($this->form_validation->run() == FALSE) {
            $data['daftarperguruantinggi'] = $this->kerjasama_model->getDaftarNamaPT();
            $this->load->view('admin/tambahDataKerjasamaPT', $data);
        } else {
                $data = array (
                'namaPT'            => $namaPT,
                'namamitra'         => $namamitra,
                'negaramitra'       => $this->input->post('negaramitra'),
                'kategori'          => $this->input->post('kategori'),
                'kegiatanKS'        => $kegiatanKS,
                'hasil'             => $this->input->post('hasil'),
                'tglmulai'          => $this->input->post('tglmulai'),
                'tglakhir'          => $this->input->post('tglakhir'),
                'idUser'            => $this->session->userdata('idUser')
                );

            $cek = $this->kerjasama_model->checkDuplicateDataPT($namaPT, $namamitra, $kegiatanKS);

            if ($cek > 0) {
                $this->session->set_flashdata('error','Data Sudah Ada.');
                $data['daftarperguruantinggi'] = $this->kerjasama_model->getDaftarNamaPT();
                $this->load->view('admin/tambahDataKerjasamaPT', $data);
            } else {
                $this->kerjasama_model->insertDataPT($data);
                $this->session->set_flashdata('succses','Data Berhasil Ditambahkan.');
                redirect(base_url().'admin/kerjasama/kerjasamaPT');
            }
        }   
    } 

    public function editDataPT($id){
        $this->form_validation->set_rules('namaPT','nama perguruan tinggi','required');
        $this->form_validation->set_rules('namamitra','nama mitra','required');
        $this->form_validation->set_rules('negaramitra','negara mitra','required');  
        $this->form_validation->set_rules('kategori','kategori','required');  

        if ($this->form_validation->run() === FALSE) {
            $data['detail'] = $this->kerjasama_model->detailDataPT($id);
            $data['daftarperguruantinggi'] = $this->kerjasama_model->getDaftarNamaPT();
            $this->load->view('admin/editDataKerjasamaPT', $data);
        } else {
                $data = array (
                'id'                => $this->input->post('id'),
                'namaPT'            => $this->input->post('namaPT'),
                'namamitra'         => $this->input->post('namamitra'),
                'negaramitra'       => $this->input->post('negaramitra'),
                'kategori'          => $this->input->post('kategori'),
                'kegiatanKS'        => $this->input->post('kegiatanKS'),
                'hasil'             => $this->input->post('hasil'),
                'tglmulai'          => $this->input->post('tglmulai'),
                'tglakhir'          => $this->input->post('tglakhir'),
                'idUser'            => $this->session->userdata('idUser')
                );
            $this->kerjasama_model->updateDataPT($data);
            $this->session->set_flashdata('succses','Data Berhasil Diubah.');
            redirect(base_url().'admin/kerjasama/kerjasamaPT');
            }
    }

	public function hapusDataPT($id) {
		$this->kerjasama_model->deleteDataPT($id);
		redirect(base_url().'admin/kerjasama/kerjasamaPT');
	} 

	//==================================================================================//

	//Code Untuk Kerja Sama Ditjen Kelembagaan
	public function ditjen(){
        $page  = $this->input->get('per_page');
        $batas = 10; //jumlah data yang ditampilkan per halaman
        if(!$page):     //jika page bernilai kosong maka batas akhirna akan di set 0
           $offset = 0;
        else:
           $offset = $page; // jika tidak kosong maka nilai batas akhir nya akan diset nilai page terakhir
        endif;

        $this->session->unset_userdata('key');

        $config['page_query_string'] = TRUE; //mengaktifkan pengambilan method get pada url default
        $config['base_url']    = base_url().'admin/kerjasama/ditjen?';   //url yang muncul ketika tombol pada paging diklik
        $config['total_rows']  = $this->kerjasama_model->countDataDitjen(); // jumlah total data
        $config['per_page']    = $batas; //batas sesuai dengan variabel batas
 
        $config['uri_segment'] = $page; //merupakan posisi pagination dalam url pada kesempatan ini saya menggunakan method get untuk menentukan posisi pada url yaitu per_page
 
        $config['full_tag_open']    = '<ul class="pagination">';
        $config['full_tag_close']   = '</ul>';
        $config['first_link']       = '&laquo; First';
        $config['first_tag_open']   = '<li class="prev page">';
        $config['first_tag_close']  = '</li>';
         
        $config['last_link']        = 'Last &raquo;';
        $config['last_tag_open']    = '<li class="next page">';
        $config['last_tag_close']   = '</li>';
 
        $config['next_link']        = 'Next &rarr;';
        $config['next_tag_open']    = '<li class="next page">';
        $config['next_tag_close']   = '</li>';
 
        $config['prev_link']        = '&larr; Prev';
        $config['prev_tag_open']    = '<li class="prev page">';
        $config['prev_tag_close']   = '</li>';
 
        $config['cur_tag_open']     = '<li class="current"><a href="">';
        $config['cur_tag_close']    = '</a></li>';
 
        $config['num_tag_open']     = '<li class="page">';
        $config['num_tag_close']    = '</li>';
        $this->pagination->initialize($config);

        $data['paging']            = $this->pagination->create_links();
        $data['jumlahpage']        = $page;
		$data['ditjenkelembagaan'] = $this->kerjasama_model->getDataDitjen($batas,$offset); 
		$this->load->view('admin/DitjenKelembagaan', $data);
	}

    public function cariDataDitjen(){
        $key    = $this->input->get('key'); //method get key
        $page   = $this->input->get('per_page');  //method get per_page

        $search = array(
            'namaPT'=> $key,
            'nokerjasama'=> $key,
            'namamitra'=> $key,
            'negaramitra'=> $key,
            'lembaga'=> $key,
            'jenis'=> $key,
            'bentuk'=> $key,
            'tglmulai'=> $key,
            'tglakhir'=> $key
        ); //array pencarian yang akan dibawa ke model

        $this->session->set_userdata('key', $key);

        $batas = 10; //jumlah data yang ditampilkan per halaman
        if(!$page):     //jika page bernilai kosong maka batas akhirna akan di set 0
           $offset = 0;
        else:
           $offset = $page; // jika tidak kosong maka nilai batas akhir nya akan diset nilai page terakhir
        endif;
 
        $config['page_query_string'] = TRUE; //mengaktifkan pengambilan method get pada url default
        $config['base_url']    = base_url().'admin/kerjasama/ditjen?key='.$key;   //url yang muncul ketika tombol pada paging diklik
        $config['total_rows']  = $this->kerjasama_model->countDataDitjenSearch($search); // jumlah total data
        $config['per_page']    = $batas; //batas sesuai dengan variabel batas
 
        $config['uri_segment'] = $page; //merupakan posisi pagination dalam url
 
        $config['full_tag_open']   = '<ul class="pagination">';
        $config['full_tag_close']  = '</ul>';
        $config['first_link']      = '&laquo; First';
        $config['first_tag_open']  = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
 
        $config['last_link']       = 'Last &raquo;';
        $config['last_tag_open']   = '<li class="next page">';
        $config['last_tag_close']  = '</li>';
 
        $config['next_link']       = 'Next &rarr;';
        $config['next_tag_open']   = '<li class="next page">';
        $config['next_tag_close']  = '</li>';
 
        $config['prev_link']       = '&larr; Prev';
        $config['prev_tag_open']   = '<li class="prev page">';
        $config['prev_tag_close']  = '</li>';
 
        $config['cur_tag_open']    = '<li class="current"><a href="">';
        $config['cur_tag_close']   = '</a></li>';
 
        $config['num_tag_open']    = '<li class="page">';
        $config['num_tag_close']   = '</li>';

        $this->pagination->initialize($config);

        $data['paging']     = $this->pagination->create_links();
        $data['jumlahpage'] = $page;
        $data['ditjenkelembagaan'] = $this->kerjasama_model->getDataDitjen($batas, $offset, $search); 
        $this->load->view('admin/DitjenKelembagaan', $data);
    }

	public function cetakDataDitjen(){
        $key    = $this->session->userdata('key'); 
        $search = array(
            'namaPT'=> $key,
            'nokerjasama'=> $key,
            'namamitra'=> $key,
            'negaramitra'=> $key,
            'lembaga'=> $key,
            'jenis'=> $key,
            'bentuk'=> $key,
            'tglmulai'=> $key,
            'tglakhir'=> $key
        ); 

        if (!empty($key)) {
            $data['ditjenkelembagaan'] = $this->kerjasama_model->getDataDitjenSaveExcel($search);
            $this->load->view('admin/simpanexcelDitjenKelembagaan', $data);
        } else {
            $data['ditjenkelembagaan'] = $this->kerjasama_model->getDataDitjenSaveExcel();
            $this->load->view('admin/simpanexcelDitjenKelembagaan', $data);
        } 

        $paper_size  = 'A4'; //paper size
        $orientation = 'landscape'; //tipe format kertas
        $html = $this->output->get_output();
 
        $this->dompdf->set_paper($paper_size, $orientation);
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("dataDitjenKelembagaan.pdf", array('Attachment'=>0));
    }

    public function simpanDataDitjen(){
        $key    = $this->session->userdata('key'); 
        $search = array(
            'namaPT'=> $key,
            'nokerjasama'=> $key,
            'namamitra'=> $key,
            'negaramitra'=> $key,
            'lembaga'=> $key,
            'jenis'=> $key,
            'bentuk'=> $key,
            'tglmulai'=> $key,
            'tglakhir'=> $key
        ); 

        if (!empty($key)) {
            $data['ditjenkelembagaan'] = $this->kerjasama_model->getDataDitjenSaveExcel($search);
            $this->load->view('admin/simpanexcelDitjenKelembagaan', $data);
        } else {
            $data['ditjenkelembagaan'] = $this->kerjasama_model->getDataDitjenSaveExcel();
            $this->load->view('admin/simpanexcelDitjenKelembagaan', $data);
        } 
		
	}

    public function tambahDataDitjen() {
        $namapt = $this->input->post('namaPT');
        $nokerjasama = $this->input->post('nokerjasama');
        $namaMitra = $this->input->post('namamitra');
        $bentuk = $this->input->post('bentuk');

        $this->form_validation->set_rules('namaPT','nama perguruan tinggi','required');
        $this->form_validation->set_rules('nokerjasama','nomer kerja sama','required');
        $this->form_validation->set_rules('namamitra','nama mitra','required');
        $this->form_validation->set_rules('negaramitra','negara mitra','required');
        $this->form_validation->set_rules('lembaga','lembaga','required'); 
        $this->form_validation->set_rules('jenis','jenis','required');
        $this->form_validation->set_rules('bentuk','bentuk','required');  

        if ($this->form_validation->run() === FALSE) {
            $data['daftarperguruantinggi'] = $this->kerjasama_model->getDaftarNamaPT();
            $this->load->view('admin/tambahDataDitjenKelembagaan', $data);
        } else {
                $data = array (
                'namaPT'            => $namapt,
                'nokerjasama'       => $nokerjasama,
                'namamitra'         => $namaMitra,
                'negaramitra'       => $this->input->post('negaramitra'),
                'lembaga'           => $this->input->post('lembaga'),
                'jenis'             => $this->input->post('jenis'),
                'bentuk'            => $bentuk,
                'tglmulai'          => $this->input->post('tglmulai'),
                'tglakhir'          => $this->input->post('tglakhir'),
                'idUser'            => $this->session->userdata('idUser')
                );

            $cek = $this->kerjasama_model->checkDuplicateDataDitjen($namapt, $nokerjasama, $namaMitra, $bentuk);

            if ($cek > 0) {
                $this->session->set_flashdata('error','Data Sudah Ada.');
                $data['daftarperguruantinggi'] = $this->kerjasama_model->getDaftarNamaPT();
                $this->load->view('admin/tambahDataDitjenKelembagaan', $data);
            } else {
                $this->kerjasama_model->insertDataDitjen($data);
                $this->session->set_flashdata('succses','Data Berhasil Ditambahkan.');
                redirect(base_url().'admin/kerjasama/ditjen');
            }
        }
    } 

    public function editDataDitjen($id){
        $this->form_validation->set_rules('namaPT','nama perguruan tinggi','required');
        $this->form_validation->set_rules('nokerjasama','nomor Kerja sama','required');
        $this->form_validation->set_rules('namamitra','nama mitra','required');
        $this->form_validation->set_rules('negaramitra','negara mitra','required');
        $this->form_validation->set_rules('lembaga','lembaga','required'); 
        $this->form_validation->set_rules('jenis','jenis','required');
        $this->form_validation->set_rules('bentuk','bentuk','required'); 

        if ($this->form_validation->run() === FALSE) {
            $data['detail'] = $this->kerjasama_model->detailDataDitjen($id);
            $data['daftarperguruantinggi'] = $this->kerjasama_model->getDaftarNamaPT();
            $this->load->view('admin/editDataDitjenKelembagaan', $data);
        } else {
                $data = array (
                'id'                => $this->input->post('id'),
                'namaPT'            => $this->input->post('namaPT'),
                'nokerjasama'       => $this->input->post('nokerjasama'),
                'namamitra'         => $this->input->post('namamitra'),
                'negaramitra'       => $this->input->post('negaramitra'),
                'lembaga'           => $this->input->post('lembaga'),
                'jenis'             => $this->input->post('jenis'),
                'bentuk'            => $this->input->post('bentuk'),
                'tglmulai'          => $this->input->post('tglmulai'),
                'tglakhir'          => $this->input->post('tglakhir'),
                'idUser'            => $this->session->userdata('idUser')
                );
            $this->kerjasama_model->updateDataDitjen($data);
            $this->session->set_flashdata('succses','Data Berhasil Diubah.');
            redirect(base_url().'admin/kerjasama/ditjen');
            }
    }

    public function hapusDataDitjen($id) {
		$this->kerjasama_model->deleteDataDitjen($id);
		redirect(base_url().'admin/kerjasama/ditjen');
	} 

	 
	//==================================================================================//

 	//Code Untuk Perkembangan Kerjasama
	public function perkembanganKS()
	{
		$data['perkembanganKS'] = $this->kerjasama_model->getDataPerkembangan(); 
		$this->load->view('admin/perkembangan', $data);
	}
	
	public function cetakDataPerkembangan(){
        $data['perkembanganKS'] = $this->kerjasama_model->getDataPerkembangan(); 
        $this->load->view('admin/cetakDataPerkembangan', $data);
 
        $paper_size  = 'A4'; //paper size
        $orientation = 'landscape'; //tipe format kertas
        $html = $this->output->get_output();
 
        $this->dompdf->set_paper($paper_size, $orientation);
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("dataPerkembagan.pdf", array('Attachment'=>0));
    }

    public function simpanDataPerkembangan(){
		$data['perkembanganKS'] = $this->kerjasama_model->getDataPerkembangan();
        $this->load->view('admin/simpanexcelPerkembangan', $data);
	}

    public function tambahDataPerkembangan() {
        $this->form_validation->set_rules('judul','judul','required');
        $this->form_validation->set_rules('mitra','mitra','required');
        $this->form_validation->set_rules('hasil','hasil','required');
        $this->form_validation->set_rules('lembaga','lembaga','required');  

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/tambahDataPerkembangan');
        } else {
                $data = array (
                'judul'           => $this->input->post('judul'),
                'mitra'             => $this->input->post('mitra'),
                'tanggal'           => $this->input->post('tanggal'),
                'perkembangan'      => $this->input->post('perkembangan'),
                'hasil'             => $this->input->post('hasil'),
                'lembaga'           => $this->input->post('lembaga')
                );
            $this->kerjasama_model->insertDataPerkembangan($data);
            $this->session->set_flashdata('succses','Data Berhasil Ditambahkan.');
            redirect(base_url().'admin/kerjasama/perkembanganKS');
            }
    } 

    public function editDataPerkembangan($id){
        $this->form_validation->set_rules('judul','judul','required');
        $this->form_validation->set_rules('mitra','mitra','required');
        $this->form_validation->set_rules('hasil','hasil','required');
        $this->form_validation->set_rules('lembaga','lembaga','required');  

        if ($this->form_validation->run() === FALSE) {
            $data['detail'] = $this->kerjasama_model->detailDataPerkembangan($id);
            $this->load->view('admin/editDataPerkembangan', $data);
        } else {
                $data = array (
                'id'                => $this->input->post('id'),
                'judul'             => $this->input->post('judul'),
                'mitra'             => $this->input->post('mitra'),
                'tanggal'           => $this->input->post('tanggal'),
                'perkembangan'      => $this->input->post('perkembangan'),
                'hasil'             => $this->input->post('hasil'),
                'lembaga'           => $this->input->post('lembaga')
                );
            $this->kerjasama_model->updateDataPerkembangan($data);
            $this->session->set_flashdata('succses','Data Berhasil Diubah.');
            redirect(base_url().'admin/kerjasama/perkembanganKS');
            }
    }

    public function hapusDataPerkembangan($id) {
		$this->kerjasama_model->deleteDataPerkembangan($id);
        $this->session->set_flashdata('succses','Data Berhasil Dihapus.');
		redirect(base_url().'admin/kerjasama/perkembanganKS');
	} 
	
}
