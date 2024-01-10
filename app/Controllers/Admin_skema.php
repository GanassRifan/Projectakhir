<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
class Admin_skema extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
	}

	public function index(){
		if(session()->get('top')){
			$bulan = date('m');
			$tahun = date('Y');
			$data['bulan'] = (int)$bulan;
			$data['tahun'] = $tahun;
			$data['aspek'] = $this->db->query("select * from aspek order by aspek asc, subaspek asc")->getResultArray();
			return view('admin/skema',$data);
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

	public function tampil(){
		$get = $this->request->getPost();
		$bulan = $get['bulan'];
		$tahun = $get['tahun'];
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['aspek'] = $this->db->query("select * from aspek order by aspek asc, subaspek asc")->getResultArray();
		return view('admin/skema',$data);
	}

	public function simpan(){
		$get = $this->request->getPost();
		$kode = "";
		if(!empty($get['aspek'])){
			$cek = $this->mod->getSome('relasi',['bulan' => (int)$get['bulan'],'tahun' => $get['tahun']]);
			if(count($cek) == 0){
				$data = array(
					'koderelasi' => null,
					'bulan' => (int)$get['bulan'],
					'tahun' => $get['tahun'],
					'status' => '0'
				);
				$this->mod->inserting('relasi',$data);
			}
			$kode = $this->mod->getData('relasi',['bulan' => (int)$get['bulan'],'tahun' => $get['tahun']])['koderelasi'];
			$this->mod->deleting('skema',['koderelasi' => $kode]);
			for ($i=0; $i < count($get['aspek']); $i++) {
				$data = array(
					'kodeskema' => null,
					'kodeaspek' => $get['aspek'][$i],
					'koderelasi' => $kode
				);
				$this->mod->inserting('skema',$data);
			}
		}
		return redirect()->to(base_url('a/skema'));
	}

	public function buka($x){
		$this->mod->updating('relasi',['status' => '1'],['koderelasi' => $x]);
		return redirect()->to(base_url('a/skema'));
	}

	public function verifikasi($x){
		$this->mod->updating('relasi',['status' => '2'],['koderelasi' => $x]);
		return redirect()->to(base_url('a/skema'));
	}

	public function kunci($x){
		$this->mod->updating('relasi',['status' => '3'],['koderelasi' => $x]);
		return redirect()->to(base_url('a/skema'));
	}

	public function bukakunci(){
		$get = $this->request->getPost();
		$pin = $get['pin'];
		$cek = $this->mod->getSome('infosistem',['pin' => md5($pin)]);
		if(count($cek) > 0){
			$this->mod->updating('relasi',['status' => '1'],['koderelasi' => $get['id']]);
		}
		return redirect()->to(base_url('a/skema'));
	}
}
?>