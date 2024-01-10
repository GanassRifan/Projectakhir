<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Kepsek_operator extends BaseController{
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
			$s = $this->mod->getData('pengguna',['kodepengguna' => session()->get('mid')]);
			$data['data'] = $this->mod->getSome('pengguna',['level' => 'low','kodesekolah' => $s['kodesekolah']]);
			$data['sekolah'] = $s['kodesekolah'];
			$data['kecamatan'] = $s['kecamatan'];
			return view('kepsek/operator',$data);
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
		$data = array(
			'kodepengguna' => null,
			'nama' => $get['nama'],
			'jekel' => $get['jekel'],
			'alamat' => $get['alamat'],
			'telepon' => $get['telepon'],
			'username' => $this->buatusername($get['nama']),
			'password' => md5(123456),
			'kecamatan' => $get['kecamatan'],
			'level' => 'low',
			'status' => 'Aktif',
			'kodesekolah' => $get['sekolah']
		);
		$this->mod->inserting('pengguna',$data);
		return redirect()->to(base_url('s/operator'));
	}

	public function ubah(){
		$get = $this->request->getPost();
		$this->mod->updating('pengguna',['status' => $get['status']],['kodepengguna' => $get['kode']]);
		return redirect()->to(base_url('s/operator'));
	}

	public function hapus($x){
		$this->mod->deleting('pengguna',['kodepengguna' => $x]);
		return redirect()->to(base_url('s/operator'));
	}
}
?>