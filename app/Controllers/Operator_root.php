<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Operator_root extends BaseController{
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
			return view('operator/landing');
		}else{
			return redirect()->to(base_url(''));
		}
	}

	public function tampilpengguna(){
		$data['data'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')]);
		return view('operator/profil',$data);
	}

	public function ubahpengguna(){
		$get = $this->request->getPost();
		$data = array(
			'nama' => $get['nama'],
			'alamat' => $get['alamat'],
			'telepon' => $get['telepon'],
			'username' => $get['username']
		);
		$this->mod->updating('pengguna',$data,['kodepengguna' => session()->get('low')]);
		return redirect()->to(base_url('o/pengguna'));
	}

	public function tampilakses(){
		return view('operator/akses');
	}

	public function ubahakses(){
		$get = $this->request->getPost();
		$p1 = $get['p1'];
		$p2 = $get['p2'];
		$p3 = $get['p3'];
		$cek = $this->mod->getSome('pengguna',['kodepengguna' => session()->get('low'),'password' => md5($p1)]);
		if(count($cek) > 0){
			if($p2 == $p3){
				$this->mod->updating('pengguna',['password' => md5($p3)],['kodepengguna' => session()->get('low')]);
			}
		}
		return redirect()->to(base_url('o/akses'));
	}
}
?>