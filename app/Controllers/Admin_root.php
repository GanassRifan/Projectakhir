<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Admin_root extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
	}

	public function index(){
		if(session()->get('top')){
			return view('admin/landing');
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

	public function tampilinfo(){
		$data['data'] = $this->db->query("select * from infosistem")->getRowArray();
		return view('admin/infosistem',$data);
	}

	public function ubahinfo(){
		$get = $this->request->getPost();
		$data = array(
			'nama' => $get['nama'],
			'tagline' => $get['tagline']
		);
		$this->mod->updateAll('infosistem',$data);
		return redirect()->to(base_url('a/infosistem'));
	}

	public function tampilpengguna(){
		$data['data'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('top')]);
		return view('admin/profil',$data);
	}

	public function ubahpengguna(){
		$get = $this->request->getPost();
		$data = array(
			'nama' => $get['nama'],
			'alamat' => $get['alamat'],
			'telepon' => $get['telepon'],
			'username' => $get['username']
		);
		$this->mod->updating('pengguna',$data,['kodepengguna' => session()->get('top')]);
		return redirect()->to(base_url('a/pengguna'));
	}

	public function tampilakses(){
		return view('admin/akses');
	}

	public function ubahakses(){
		$get = $this->request->getPost();
		$p1 = $get['p1'];
		$p2 = $get['p2'];
		$p3 = $get['p3'];
		$cek = $this->mod->getSome('pengguna',['kodepengguna' => session()->get('top'),'password' => md5($p1)]);
		if(count($cek) > 0){
			if($p2 == $p3){
				$this->mod->updating('pengguna',['password' => md5($p3)],['kodepengguna' => session()->get('top')]);
			}
		}
		return redirect()->to(base_url('a/akses'));
	}

	public function tampilpin(){
		return view('admin/pin');
	}

	public function ubahpin(){
		$get = $this->request->getPost();
		$p1 = $get['p1'];
		$p2 = $get['p2'];
		$p3 = $get['p3'];
		$cek = $this->mod->getSome('infosistem',['pin' => md5($p1)]);
		if(count($cek) > 0){
			if($p2 == $p3){
				$this->mod->updateAll('infosistem',['pin' => md5($p3)]);
			}
		}
		return redirect()->to(base_url('a/pin'));
	}
}
?>