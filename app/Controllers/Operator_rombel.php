<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Operator_rombel extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
	}

	public function index(){
		if(session()->get('top')){
			return redirect()->to(base_url('a'));
		}else if(session()->get('high')){
			return redirect()->to(base_url('k'));
		}else if(session()->get('mid')){
			return redirect()->to(base_url('s'));
		}else if(session()->get('low')){
			$bulan = date('m');
			$tahun = date('Y');
			$data['bulan'] = (int)$bulan;
			$data['tahun'] = $tahun;
			$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
			return view('operator/rombel',$data);
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
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		return view('operator/rombel',$data);
	}

	public function simpan(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$cek = $this->mod->getSome('relasi',['bulan' => $bulan,'tahun' => $tahun]);
		if(count($cek) == 0){
			$data = array(
				'koderelasi' => null,
				'bulan' => $bulan,
				'tahun' => $tahun,
				'status' => 0
			);
			$this->mod->inserting('relasi',$data);
		}
		$kode = $this->mod->getData('relasi',['bulan' => (int)$get['bulan'],'tahun' => $get['tahun']])['koderelasi'];
		$data = array(
			'koderombel' => null,
			'rombel' => $get['rombel'],
			'jumlah' => $get['jumlah'],
			'kodesekolah' => $get['sekolah'],
			'koderelasi' => $kode
		);
		$this->mod->inserting('rombel',$data);
		return redirect()->to(base_url('o/rombel'));
	}

	public function ubah(){
		$get = $this->request->getPost();
		$data = array(
			'rombel' => $get['rombel'],
			'jumlah' => $get['jumlah']
		);
		$this->mod->updating('rombel',$data,['koderombel' => $get['kode']]);
		return redirect()->to(base_url('o/rombel'));
	}

	public function hapus($x){
		$this->mod->deleting('rombel',['koderombel' => $x]);
		return redirect()->to(base_url('o/rombel'));
	}
}
?>