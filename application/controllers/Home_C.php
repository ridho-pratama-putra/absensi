<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_C extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('Absen_M');
		date_default_timezone_set("Asia/Jakarta");
    }
	public function view($page = 'absen')
	{
		$date = date('Y-m-d');
		$dataCondition['tanggal'] = $date;
		$apakah_hari_libur = $this->Absen_M->read('data_libur',$dataCondition)->result();
		unset($dataCondition['tanggal']);
		if ($apakah_hari_libur != array() AND isset($this->session->userdata['logged_in']) == false) {
			$this->load->view('html/header');
			$this->load->view('html/block');
		}elseif($apakah_hari_libur != array() AND isset($this->session->userdata['logged_in']) == true){
			redirect('Jabatan_C');
		}
		else {
			$data['nama_karyawan']=$this->Absen_M->readS('data_k')->result();
			$data['status']=$this->Absen_M->readS('data_s')->result();
			
			$data['absen']=$this->Absen_M->rawQuery("
				SELECT data_ra.id_a, data_s.keterangan_s, data_ra.detail, data_ra.tanggal, data_ra.jam, data_ra.acc, data_k.nama_k FROM data_ra
				INNER JOIN data_k ON data_ra.id_k = data_k.id_k
				INNER JOIN data_s ON data_ra.id_s = data_s.id_s
				WHERE tanggal = '".$date."'	ORDER BY data_ra.id_a DESC ")->result();
			
			$dataCondition['end']= '00:00:00';
			$data['ijin']=$this->Absen_M->rawQuery("
				SELECT data_k.nama_k, data_i.perihal, data_i.start, data_i.end, data_i.tanggal, data_i.id_i FROM data_i INNER JOIN data_k ON data_i.id_k = data_k.id_k WHERE end = '".$dataCondition['end']."'")->result();
			unset($dataCondition);
			$this->load->view('html/header');
			$this->load->view('html/menu');
			$this->load->view($page,$data);
			$this->load->view('html/footer');
		}
	}
	public function login()
	{
		$this->form_validation->set_rules('l_username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('l_password', 'Password', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			if(isset($this->session->userdata['logged_in'])){
				$this->session->set_flashdata("alert_login_validate", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>anda sudah login!</strong></div>");
			}else{
				$this->session->set_flashdata("alert_login_validate", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>login gagal!</strong> </div>");
			}
		}
		else {
			$data = array(	'username_k' => $this->input->post('l_username'),
							'password_k' => md5($this->input->post('l_password')
			));
			$result = $this->Absen_M->read('data_l',$data)->result();
			if ($result != array()) {
				$session_data = array(
					'username' => $result[0]->username_k,
					'id_k' => $result[0]->id_k,
					'hak_akses' => $result[0]->hak_akses
				);
				if (($session_data['hak_akses'] == '1') or ($session_data['hak_akses'] == '2')) {
					$this->session->set_flashdata("alert_login", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' 	aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>login sukses!</strong> </div>");
					$this->session->set_userdata('logged_in', $session_data);
					// header('Location: http://localhost/absensi/Home_C/view');
				} else {
					$this->session->set_flashdata("alert_login", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' 	aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>anda bukan admin!</strong> </div>");
					// redirect();
				}
			} else {
				$this->session->set_flashdata("alert_login", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>username dan password tidak ditemukan!</strong> </div>");
				// redirect();
			}
		}
			redirect();
	}
	public function logout() 
	{
		$sess_array = array(
							'username' => '',
							'id_k' =>'',
							'hak_akses' =>''
		);
		$this->session->unset_userdata('logged_in', $sess_array);
		$this->session->set_flashdata("alert_login", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Logged out!</strong> </div>");
		redirect();
	}
	public function create_absen()
	{
		$data['id_k'] = $this->input->post('c_id_k');
		$data['id_s'] = $this->input->post('c_status');

		$date = date('m');
		
		$where_idm['id_m'] =  1;
		$datax['jam_masuk'] = $this->Absen_M->read('data_m',$where_idm)->result();
		$jam_masuk = $datax['jam_masuk'][0]->misc;

		$where_idm['id_m'] =  4;
		$datax['jam_pulang'] = $this->Absen_M->read('data_m',$where_idm)->result();
		$jam_pulang = $datax['jam_pulang'][0]->misc;
		unset($where_idm);


		$data['tanggal'] = date("Y-m-d");
		//$data['tanggal'] = "2017-06-09";
		$data['jam'] = date('H:i:s', time());
		$data['acc'] ='0';
		
		if ($data['id_s'] == 1) 
		{
			if ($data['jam'] > $jam_masuk) 
			{
				$data['detail'] = "telat";
			}
			else
			{
				$data['detail'] = "tepat waktu";	
			}
		}
		else
		{
			$data['detail'] = $this->input->post('c_detail');
		}

		$datas['id_k'] = $data['id_k'];
		$datalike['tanggal'] = $data['tanggal'];
		$cari = $this->Absen_M->searchResult('data_ra',$datas,$datalike)->result_array();//apakah sudah absen hari ini
		if ($cari === array()) 
		{
			$datas['start'] = $jam_masuk;
			$datas['end'] = $jam_pulang;
			if ($data['id_s'] == 3) 
			{
				$where_idk['id_k'] = $data['id_k'];
				$cuti_ku = $this->Absen_M->read('data_c',$where_idk)->result();
				$wes_cuti = $cuti_ku[0]->cuti_berapakali;
				
				if ($wes_cuti == $date) 
				{
					$this->session->set_flashdata("notifikasi", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Cuti gagal!</strong> Batas cuti anda habis</div>");
				}
				else
				{
					$result = $this->Absen_M->create('data_ra',$data);
					if($result){
						$this->session->set_flashdata("notifikasi", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Cuti berhasil!</strong></div>");
					}
					else
					{
						$this->session->set_flashdata("notifikasi", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Cuti gagal!</strong></div>");
					}
				}
			}
			elseif ($data['id_s'] == 7) { //ambil hari libur
				//blok semua absensi dan semua ijin(cuti hadir dkk)
				if (isset($this->session->userdata['logged_in'])) {
					unset($data['id_k'],$data['jam'],$data['acc'],$data['id_s']);
					var_dump($data);
					$result = $this->Absen_M->create('data_libur',$data);
					var_dump($result);
				} else {
					$this->session->set_flashdata("notifikasi", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>anda tidak punya akses!</strong></div>");
				}
			}
			else
			{
				$result = $this->Absen_M->create('data_ra',$data);
				var_dump($data);
				echo "insert hadir ";
				var_dump($result);
				if($result)
				{
					$this->session->set_flashdata("notifikasi", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Absen berhasil!</strong></div>");
				}
				else
				{
					$this->session->set_flashdata("notifikasi", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Absen gagal!(databes)</strong></div>");
				}
			}
		}
		else
		{
			$this->session->set_flashdata("notifikasi", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>anda sudah absen hari ini!</strong></div>");
		}
		redirect();
	}
	public function create_ijin()
	{
		$data['id_k'] = $this->input->post('c_id_k');
		$data['perihal'] = $this->input->post('c_perihal');
		$data['start'] = date('H:i:s', time());
		$data['tanggal'] = date('Y-m-d');
		
		$where_idm['id_m'] =  1;
		$datax['jam_masuk'] = $this->Absen_M->read('data_m',$where_idm)->result();
		$jam_masuk = $datax['jam_masuk'][0]->misc;

		$where_idm['id_m'] =  4;
		$datax['jam_pulang'] = $this->Absen_M->read('data_m',$where_idm)->result();
		$jam_pulang = $datax['jam_pulang'][0]->misc;

		unset($datax,$where_idm);

		$datar['id_k']  = $data['id_k'];
		$datar['tanggal'] = $data['tanggal'];
		$datar['id_s'] = '1';
		$datar['acc'] = '1';

		$apakah_hadir_dan_acc = $this->Absen_M->read('data_ra',$datar)->result();
		unset($datar['id_s'],$datar['acc']);

		$datar['end']= "00:00:00";
		if ($apakah_hadir_dan_acc != array()) {
			$apakah_ijinku_belum_end =$this->Absen_M->read('data_i',$datar)->result();
			// print_r($datar);
			// echo "<br>";
			// echo "<br>";
			// var_dump($apakah_ijinku_belum_end);
			// echo "<br>";
			// echo "<br>";
			if ($apakah_ijinku_belum_end == array()) {
				// echo "sek kenek";
				if ($data['start'] < $jam_masuk){
		 			$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>jam start belum masuk jam kerja</strong></div>");	
		 		}else {
		 			$insert_data_t = $this->Absen_M->create('data_i',$data);
		 			if($insert_data_t){
		 				$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>ijin berhasil</strong> ijin anda akan distop oleh admin saat anda kembali</div>");
		 			}else{
		 				$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>gagal memasukkan ke database</strong></div>");	
		 			}
		 		}
			} else {
				$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>anda masih punya tanggungan ijin</strong></div>");	
			}
			
	 	} else {
		 	$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Harus hadir dan mendapat acc agar bisa ijin</strong></div>");	
		}
		redirect('Home_C/view/ijin');
	}
	public function stop_ijin($data,$start)
	{
		$dataCondition['id_i'] = $data;
		$datau['end'] = date('H:i:s', time());
		$time1 = strtotime($start);
		$time2 = strtotime($datau['end']);
		$difference = round(abs($time2 - $time1) / 3600,2);
		if ($difference >= 0.5) {
			$result = $this->Absen_M->update('data_i',$dataCondition,$datau);
			$results = json_decode($result, true);

			if ($results['status']) {
				$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>ijin berhasil di stop.</strong> Data ijin anda telah masuk ke laporan</div>");
			}
			else{
				$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>ijin gagal di stop(database) </strong> </div>");
			}
		} else {
			$result = $this->Absen_M->delete('data_i',$dataCondition);
			// echo "string";
			// print_r($result);
			if ($result) {
				$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>ijin berhasil di stop.</strong> Data ijin anda dihapus karena kurang dari 30 menit</div>");
			}
			else{
				$this->session->set_flashdata("notifikasi_ijin", "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>ijin gagal di stop. </strong> ijin gagal di hapus(database) </div>");
			}
		}
		

		redirect('Home_C/view/ijin');
	}
	
}
