<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
use App\Libraries\Fpdf\Fpdf;
class Korwilcam_laporan extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index(){
		if(session()->get('top')){
			return redirect()->to(base_url('a'));
		}else if(session()->get('high')){
			$bulan = date('m');
			$tahun = date('Y');
			$kecamatan = $this->db->query("select kecamatan from pengguna where kodepengguna = '".session()->get('high')."'")->getRowArray()['kecamatan'];
			$data['bulan'] = (int)$bulan;
			$data['tahun'] = $tahun;
			$data['kecamatan'] = $kecamatan;
			$data['sekolah'] = $this->mod->getSome('sekolah',['kecamatan' => $kecamatan]);
			return view('korwilcam/laporan',$data);
		}else if(session()->get('mid')){
			return redirect()->to(base_url('s'));
		}else if(session()->get('low')){
			return redirect()->to(base_url('o'));
		}else{
			return redirect()->to(base_url(''));
		}
	}

	public function tampil(){
		$get = $this->request->getPost();
		$bulan = $get['bulan'];
		$tahun = $get['tahun'];
		$kecamatan = $get['kecamatan'];
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['kecamatan'] = $kecamatan;
		$data['sekolah'] = $this->mod->getSome('sekolah',['kecamatan' => $get['kecamatan']]);
		return view('korwilcam/laporan',$data);
	}

	public function detail($x){
		$x = $this->mod->getData('rekap',['koderekap' => $x]);
		if($x['status'] == 3){
			$this->mod->updating('rekap',['status' => '4'],['koderekap' => $x['koderekap']]);
			$data = array(
				'kodeverifikasi' => null,
				'waktu' => date('Y-m-d H:i:s'),
				'status' => '4',
				'catatan' => 'Data diterima. Menunggu verifikasi Korwilcam',
				'kodepengguna' => session()->get('high'),
				'koderekap' => $x['koderekap']
			);
			$this->mod->inserting('verifikasi',$data);
		}
		$data['rekap'] = $this->mod->getData('rekap',['koderekap' => $x['koderekap']]);
		$data['operator'] = $this->mod->getData('pengguna',['kodepengguna' => $x['kodepengguna']]);
		$data['sekolah'] = $this->mod->getData('sekolah',['kodesekolah' => $x['kodesekolah']]);
		$data['relasi'] = $this->mod->getData('relasi',['koderelasi' => $x['koderelasi']]);
		$data['skema'] = $this->db->query("select aspek.* from skema join aspek on skema.kodeaspek = aspek.kodeaspek where skema.koderelasi = '".$x['koderelasi']."' group by aspek.aspek asc")->getResultArray();
		$data['aspek'] = $this->db->query("select aspek.* from skema join aspek on skema.kodeaspek = aspek.kodeaspek where skema.koderelasi = '".$x['koderelasi']."' order by aspek.aspek asc, aspek.kodeaspek asc ")->getResultArray();
		$data['berkas'] = $this->mod->getSome('berkas',['koderekap' => $x['koderekap']]);
		$data['ptk'] = $this->mod->getSome('kdptk',['koderekap' => $x['koderekap']]);
		$data['log'] = $this->db->query("select * from verifikasi where koderekap = '".$x['koderekap']."' order by waktu asc")->getResultArray();
		return view('korwilcam/laporandetail',$data);
	}

	public function tolak(){
		$get = $this->request->getPost();
		$this->mod->updating('rekap',['status' => '5'],['koderekap' => $get['kode']]);
		$data = array(
			'kodeverifikasi' => null,
			'waktu' => date('Y-m-d H:i:s'),
			'status' => '5',
			'catatan' => $get['catatan'],
			'kodepengguna' => session()->get('high'),
			'koderekap' => $get['kode']
		);
		$this->mod->inserting('verifikasi',$data);
		return redirect()->to( base_url('k/laporan'));
	}

	public function terima($x){
		$this->mod->updating('rekap',['status' => '6'],['koderekap' => $x]);
		$kecamatan = $this->mod->getData('pengguna',['kodepengguna' => session()->get('high')])['kecamatan'];
		$data = array(
			'kodeverifikasi' => null,
			'waktu' => date('Y-m-d H:i:s'),
			'status' => '6',
			'catatan' => 'Data telah diverifikasi oleh Korwilcam '.$kecamatan.'. Diteruskan ke Pusat',
			'kodepengguna' => session()->get('high'),
			'koderekap' => $x
		);
		$this->mod->inserting('verifikasi',$data);
		return redirect()->to( base_url('k/laporan'));
	}

	public function cetak(){
	}
}
?>