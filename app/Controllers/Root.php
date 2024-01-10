<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Root extends BaseController{
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
			return redirect()->to(base_url('o'));
		}else{
			session()->setFlashdata('gagal','');
			return view('landing');
		}
	}

	public function login(){
		$get = $this->request->getPost();
		$username = $get['username'];
		$password = $get['password'];
		$cek = $this->mod->getSome('pengguna',['username' => $username, 'password' => md5($password)]);
		if(count($cek) > 0){
			$cek = $this->mod->getData('pengguna',['username' => $username, 'password' => md5($password)]);
			if($cek['status'] == 'Nonaktif'){
				session()->setFlashdata('gagal','Akun tidak dapat diakses!');
				return view('landing');
			}else{
				if($cek['level'] == 'top'){
					session()->set('top',$cek['kodepengguna']);
				}else if($cek['level'] == 'high'){
					session()->set('high',$cek['kodepengguna']);
				}else if($cek['level'] == 'mid'){
					session()->set('mid',$cek['kodepengguna']);
				}else{
					session()->set('low',$cek['kodepengguna']);
				}
				return redirect()->to(base_url(''));
			}
		}else{
			session()->setFlashdata('gagal','Akun tidak ditemukan atau Kombinasi tidak sesuai!');
			return view('landing');
		}
	}

	public function logins(){
		$get = $this->request->getPost();
		$username = $get['username'];
		$password = $get['password'];
		$cek = $this->mod->getSome('pengguna',['username' => $username, 'password' => md5($password)]);
		if(count($cek) > 0){
			$cek = $this->mod->getData('pengguna',['username' => $username, 'password' => md5($password)]);
			if($cek['status'] == 'Nonaktif'){
				session()->setFlashdata('gagal','Akun tidak dapat diakses!');
				return view('landing');
			}else{
				session()->set($cek['level'],$cek['kodepengguna']);
				return redirect()->to(base_url(''));
			}
		}else{
			session()->setFlashdata('gagal','Akun tidak ditemukan atau Kombinasi tidak sesuai!');
			return view('landing');
		}
	}

	public function logout(){
		session_unset();
		session()->destroy();
		return redirect()->to(base_url(''));
	}
}
?>