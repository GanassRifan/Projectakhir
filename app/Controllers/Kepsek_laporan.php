<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Kepsek_laporan extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index(){
		if(session()->get('top')){
			return redirect()->to(base_url('a'));
		}else if(session()->get('high')){
			return redirect()->to(base_url('k'));
		}else if(session()->get('mid')){
			$bulan = date('m');
			$tahun = date('Y');
			$sekolah = $this->mod->getData('pengguna',['kodepengguna' => session()->get('mid')])['kodesekolah'];
			$cek = $this->db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
			if($cek > 0){
				$relasi = $this->db->query("select koderelasi from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
				$cek = $this->db->query("select ifnull(count(*),0) as jumlah from rekap where kodesekolah = '".$sekolah."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
				if($cek > 0){
					$rekap = $this->db->query("select * from rekap where kodesekolah = '".$sekolah."' and koderelasi = '".$relasi."'")->getRowArray();
					if($rekap['status'] == '0'){
						$this->mod->updating('rekap',['status' => '1'],['koderekap' => $rekap['koderekap']]);
						$input = array(
							'kodeverifikasi' => null,
							'waktu' => date('Y-m-d H:i:s'),
							'status' => '1',
							'catatan' => 'Data diterima. Menunggu verifikasi Kepala Sekolah',
							'kodepengguna' => session()->get('mid'),
							'koderekap' => $rekap['koderekap']
						);
						$this->mod->inserting('verifikasi',$input);
					}
				}
			}
			$data['bulan'] = (int)$bulan;
			$data['tahun'] = $tahun;
			$data['sekolah'] = $sekolah;
			$data['buka'] = '';
			return view('kepsek/laporan',$data);
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
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('mid')])['kodesekolah'];
		$data['buka'] = '';
		return view('kepsek/laporan',$data);
	}

	public function detail($x){
		$rl = $this->mod->getData('rekap',['koderekap' => $x])['koderelasi'];
		$rl = $this->mod->getData('relasi',['koderelasi' => $rl]);
		$rk = $this->mod->getData('rekap',['koderekap' => $x]);
		if($rk['status'] == '0'){
			$this->mod->updating('rekap',['status' => '1'],['koderekap' => $x]);
			$input = array(
				'kodeverifikasi' => null,
				'waktu' => date('Y-m-d H:i:s'),
				'status' => '1',
				'catatan' => 'Data diterima. Menunggu verifikasi Kepala Sekolah',
				'kodepengguna' => session()->get('mid'),
				'koderekap' => $x
			);
			$this->mod->inserting('verifikasi',$input);
		}
		$data['bulan'] = (int)$rl['bulan'];
		$data['tahun'] = $rl['tahun'];
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('mid')])['kodesekolah'];
		$data['buka'] = '';
		return view('kepsek/laporan',$data);
	}

	public function tolak(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$this->mod->updating('rekap',['status' => '2'],['koderekap' => $get['kode']]);
		$input = array(
			'kodeverifikasi' => null,
			'waktu' => date('Y-m-d H:i:s'),
			'status' => '2',
			'catatan' => 'Data Tidak Sesuai. '.$get['catatan'],
			'kodepengguna' => session()->get('mid'),
			'koderekap' => $get['kode']
		);
		$this->mod->inserting('verifikasi',$input);
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('mid')])['kodesekolah'];
		$data['buka'] = '';
		return view('kepsek/laporan',$data);
	}

	public function terima($x){
		$rl = $this->mod->getData('rekap',['koderekap' => $x])['koderelasi'];
		$rl = $this->mod->getData('relasi',['koderelasi' => $rl]);
		$rk = $this->mod->getData('rekap',['koderekap' => $x]);
		$kecamatan = $this->mod->getData('sekolah',['kodesekolah' => $rk['kodesekolah']])['kecamatan'];
		$this->mod->updating('rekap',['status' => '3'],['koderekap' => $x]);
		$input = array(
			'kodeverifikasi' => null,
			'waktu' => date('Y-m-d H:i:s'),
			'status' => '3',
			'catatan' => 'Data telah diverifikasi oleh Kepala Sekolah. Diteruskan kepada Korwilcam '.$kecamatan,
			'kodepengguna' => session()->get('mid'),
			'koderekap' => $x
		);
		$this->mod->inserting('verifikasi',$input);
		$data['bulan'] = (int)$rl['bulan'];
		$data['tahun'] = $rl['tahun'];
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('mid')])['kodesekolah'];
		$data['buka'] = '';
		return view('kepsek/laporan',$data);
	}
}
?>