<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Admin_aspek extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
	}

	public function index(){
		if(session()->get('top')){
			$data['data'] = $this->mod->getAll('aspek');
			return view('admin/aspek',$data);
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

	public function tambah(){
		$get = $this->request->getPost();
		if($get['jenis'] == 'pilihan'){
			$aspek = array(
				'aspek' => $get['aspek'],
				'subaspek' => $get['subaspek'],
				'jenis' => $get['jenis']
			);
			$data['aspek'] = $aspek;
			$data['pilihan'] = [];
			return view('admin/aspekpilihanbaru',$data);
		}else{
			$aspek = array(
				'aspek' => $get['aspek'],
				'subaspek' => $get['subaspek'],
				'jenis' => $get['jenis']
			);
			$data['aspek'] = $aspek;
			return view('admin/aspekbaru',$data);
		}
	}

	public function tambahdetail(){
		$get = $this->request->getPost();
		$pilihan = unserialize($get['pilihan']);
		array_push($pilihan, $get['isian']);
		$data['aspek'] = unserialize($get['aspek']);
		$data['pilihan'] = $pilihan;
		return view('admin/aspekpilihanbaru',$data);
	}

	public function hapusdetail($aspek,$pilihan,$x){
		$pilihan = unserialize($pilihan);
		array_splice($pilihan, $x, 1);
		$data['aspek'] = unserialize($aspek);
		$data['pilihan'] = $pilihan;
		return view('admin/aspekpilihanbaru',$data);
	}

	public function simpan(){
		$get = $this->request->getPost();
		$data = array(
			'kodeaspek' => null,
			'aspek' => $get['aspek'],
			'subaspek' => $get['subaspek'],
			'jenis' => $get['jenis'],
			'satuan' => $get['satuan']
		);
		$this->mod->inserting('aspek',$data);
		return redirect()->to(base_url('a/aspek'));
	}

	public function simpanpilihan(){
		$get = $this->request->getPost();
		$unix = rand(100,99999999);
		$aspek = unserialize($get['aspek']);
		$pilihan = unserialize($get['pilihan']);
		$data = array(
			'kodeaspek' => null,
			'aspek' => $aspek['aspek'],
			'subaspek' => $aspek['subaspek'],
			'jenis' => $aspek['jenis'],
			'satuan' => $unix
		);
		$this->mod->inserting('aspek',$data);
		$kode = $this->mod->getData('aspek',['satuan' => $unix])['kodeaspek'];
		for ($i=0; $i < count($pilihan); $i++) {
			$data = array(
				'kodepilihan' => null,
				'pilihan' => $pilihan[$i],
				'kodeaspek' => $kode
			);
			$this->mod->inserting('pilihan',$data);
		}
		$this->mod->updating('aspek',['satuan' => ''],['kodeaspek' => $kode]);
		return redirect()->to(base_url('a/aspek'));
	}

	public function ubah(){
		$get = $this->request->getPost();
		$data = array(
			'aspek' => $get['aspek'],
			'subaspek' => $get['subaspek'],
			'satuan' => $get['satuan']
		);
		$this->mod->updating('aspek',$data,['kodeaspek' => $get['kode']]);
		return redirect()->to(base_url('a/aspek'));
	}

	public function detail($x){
		$data['data'] = $this->mod->getData('aspek',['kodeaspek' => $x]);
		$data['pilihan'] = $this->mod->getSome('pilihan',['kodeaspek' => $x]);
		return view('admin/aspekdetail',$data);
	}

	public function ubahpilihan(){
		$get = $this->request->getPost();
		$data = array(
			'aspek' => $get['aspek'],
			'subaspek' => $get['subaspek']
		);
		$this->mod->updating('aspek',$data,['kodeaspek' => $get['kode']]);
		return redirect()->to(base_url('a/aspek'));
	}

	public function tambahpilihan(){
		$get = $this->request->getPost();
		$data = array(
			'kodepilihan' => null,
			'pilihan' => $get['pilihan'],
			'kodeaspek' => $get['kode']
		);
		$this->mod->inserting('pilihan',$data);
		return redirect()->to(base_url('a/aspek/detail/'.$get['kode']));
	}

	public function hapuspilihan($x){
		$kode = $this->mod->getData('pilihan',['kodepilihan' => $x])['kodeaspek'];
		$this->mod->deleting('pilihan',['kodepilihan' => $x]);
		return redirect()->to(base_url('a/aspek/detail/'.$kode));
	}

	public function hapus($x){
		$this->mod->deleting('aspek',['kodeaspek' => $x]);
		$this->mod->deleting('pilihan',['kodeaspek' => $x]);
		return redirect()->to(base_url('a/aspek'));
	}
}
?>