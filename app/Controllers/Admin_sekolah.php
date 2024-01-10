<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Admin_sekolah extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
	}

	public function index(){
		if(session()->get('top')){
			$data['data'] = $this->mod->getAll('sekolah');
			return view('admin/sekolah',$data);
		}else if(session()->get('high')){
			return redirect()->to(base_url('k'));
		}else if(session()->get('mid')){
			return redirect()->to(base_url('s'));
		}else if(session()->get('low')){
			return redirect()->to(base_url('o'));
		}else{
			return redirect()->to(base_url(''));
		}
	}

	public function buatusername($x){
		$username = "";
		$x = explode(" ", $x);
		$x = strtolower($x[0]);
		$ada = true;
		while ($ada) {
			$username = $x.rand(100,999);
			$cek = $this->mod->getSome('pengguna',['username' => $username]);
			if (count($cek) == 0) {
				$ada = false;
			}
		}
		return $username;
	}

	public function simpan(){
		$get = $this->request->getPost();
		$unix = rand(100,99999999);
		$data = array(
			'kodesekolah' => null,
			'npsn' => $get['npsn'],
			'nis' => '',
			'noijin' => $get['noijin'],
			'tglijin' => date('Y-m-d', strtotime($get['tglijin'])),
			'nobhi' => $get['nobhi'],
			'tglbhi' => date('Y-m-d', strtotime($get['tglbhi'])),
			'stakred' => '',
			'tglakred' => '0000-00-00',
			'nama' => $get['nama'],
			'lembaga' => $get['lembaga'],
			'yayasan' => '',
			'berdiri' => date('Y-m-d', strtotime($get['tglberdiri'])),
			'logo' => $unix,
			'kecamatan' => $get['kecamatan']
		);
		$this->mod->inserting('sekolah',$data);
		$kode = $this->mod->getData('sekolah',['logo' => $unix])['kodesekolah'];
		$data = array(
			'kodepengguna' => null,
			'nama' => $get['kepsek'],
			'jekel' => $get['jekel'],
			'alamat' => $get['alamat'],
			'telepon' => $get['telepon'],
			'username' => $this->buatusername($get['kepsek']),
			'password' => md5(123456),
			'kecamatan' => $get['kecamatan'],
			'level' => 'mid',
			'status' => 'Aktif',
			'kodesekolah' => $kode
		);
		$this->mod->inserting('pengguna',$data);
		$this->mod->updating('sekolah',['logo' => ''],['kodesekolah' => $kode]);
		return redirect()->to(base_url('a/sekolah'));
	}

	public function ubah(){
		$get = $this->request->getPost();
		$status = $get['status'];
		if($status == 'Nonaktif'){
			$this->mod->updating('pengguna',['status' => 'Nonaktif'],['level' => 'mid','kodesekolah' => $get['kode']]);
			$this->mod->updating('pengguna',['status' => 'Nonaktif'],['level' => 'low','kodesekolah' => $get['kode']]);
		}else{
			$p = $this->db->query("select kodepengguna from pengguna where level = 'mid' and kodesekolah = '".$get['kode']."' order by kodepengguna desc")->getRowArray()['kodepengguna'];
			$this->mod->updating('pengguna',['status' => 'Aktif'],['kodepengguna' => $p]);
			$cek = $this->mod->getSome('pengguna',['level' => 'low','kodesekolah' => $get['kode']]);
			if(count($cek) > 0){
				$p = $this->db->query("select kodepengguna from pengguna where level = 'low' and kodesekolah = '".$get['kode']."' order by kodepengguna desc")->getRowArray()['kodepengguna'];
				$this->mod->updating('pengguna',['status' => 'Aktif'],['kodepengguna' => $p]);
			}
		}
		return redirect()->to(base_url('a/sekolah'));
	}

	public function hapus($x){
		$this->mod->deleting('sekolah',['kodesekolah' => $x]);
		$this->mod->deleting('pengguna',['kodesekolah' => $x]);
		return redirect()->to(base_url('a/sekolah'));
	}
}
?>