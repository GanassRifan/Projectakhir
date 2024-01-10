<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Operator_laporan extends BaseController{
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
			return redirect()->to(base_url('s'));
		}else if(session()->get('low')){
			$bulan = date('m');
			$tahun = date('Y');
			$data['bulan'] = (int)$bulan;
			$data['tahun'] = $tahun;
			$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
			$data['buka'] = '';
			return view('operator/laporan',$data);
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
		$data['buka'] = '';
		return view('operator/laporan',$data);
	}

	public function detail($x){
		$rl = $this->mod->getData('rekap',['koderekap' => $x])['koderelasi'];
		$rl = $this->mod->getData('relasi',['koderelasi' => $rl]);
		$rk = $this->mod->getData('rekap',['koderekap' => $x]);
		$data['bulan'] = (int)$rl['bulan'];
		$data['tahun'] = $rl['tahun'];
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = '';
		return view('operator/laporan',$data);
	}

	public function simpan(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$sekolah = $get['sekolah'];
		$relasi = $get['relasi'];
		$rekap = "";
		$waktu = date('Y-m-d H:i:s');
		$tglakred = "0000-00-00";
		if($get['stakred'] != ""){
			$tglakred = date('Y-m-d', strtotime($get['tglakred']));
		}
		$input = array(
			'nis' => $get['nis'],
			'stakred' => $get['stakred'],
			'tglakred' => $tglakred,
			'yayasan' => $get['yayasan']
		);
		$this->mod->updating('sekolah',$input,['kodesekolah' => $sekolah]);
		$input = array(
			'koderekap' => null,
			'waktu' => $waktu,
			'telepon' => $get['telepon'],
			'email' => $get['email'],
			'alamat' => $get['alamat'],
			'status' => '',
			'kodepengguna' => $get['pengguna'],
			'kodesekolah' => $get['sekolah'],
			'koderelasi' => $relasi
		);
		$this->mod->inserting('rekap',$input);
		$rekap = $this->mod->getData('rekap',['kodesekolah' => $sekolah,'koderelasi' => $relasi])['koderekap'];
		$input = array(
			'kodeverifikasi' => null,
			'waktu' => $waktu,
			'status' => '',
			'catatan' => 'Proses rekap data oleh Operator',
			'kodepengguna' => $get['pengguna'],
			'koderekap' => $rekap
		);
		$this->mod->inserting('verifikasi',$input);
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = '';
		return view('operator/laporan',$data);
	}

	public function ubah(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$waktu = date('Y-m-d H:i:s');
		$sekolah = $get['sekolah'];
		$tglakred = "0000-00-00";
		if($get['stakred'] != ""){
			$tglakred = date('Y-m-d', strtotime($get['tglakred']));
		}
		$input = array(
			'nis' => $get['nis'],
			'stakred' => $get['stakred'],
			'tglakred' => $tglakred,
			'yayasan' => $get['yayasan']
		);
		$this->mod->updating('sekolah',$input,['kodesekolah' => $sekolah]);
		$input = array(
			'telepon' => $get['telepon'],
			'email' => $get['email'],
			'alamat' => $get['alamat']
		);
		$this->mod->updating('rekap',$input,['koderekap' => $get['rekap']]);
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = '';
		return view('operator/laporan',$data);
	}

	public function ubahumum(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$rekap = $get['rekap'];
		$relasi = $get['relasi'];
		$aspek = unserialize($get['aspek']);
		for ($i=0; $i < count($aspek); $i++) {
			$subaspek = $this->mod->getSome('aspek',['aspek' => $aspek[$i]['aspek']]);
			foreach ($subaspek as $sa) {
				$cek = $this->db->query("select ifnull(count(*),0) as jumlah from skema where kodeaspek = '".$sa['kodeaspek']."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
				if($cek > 0){
					$x = "a".$sa['kodeaspek'];
					$x = $get[$x];
					$cek = $this->mod->getSome('rincian',['kodeaspek' => $sa['kodeaspek'],'koderekap' => $rekap]);
					if(count($cek) == 0){
						$data = array(
							'koderincian' => null,
							'rincian' => $x,
							'kodeaspek' => $sa['kodeaspek'],
							'koderekap' => $rekap
						);
						$this->mod->inserting('rincian',$data);
					}else{
						$kode = $this->mod->getData('rincian',['kodeaspek' => $sa['kodeaspek'],'koderekap' => $rekap])['koderincian'];
						$this->mod->updating('rincian',['rincian' => $x],['koderincian' => $kode]);
					}
				}
			}
		}
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = $get['buka'];
		return view('operator/laporan',$data);
	}

	public function ubahsiswa(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$rekap = $get['rekap'];
		$rombel = unserialize($get['rombel']);
		for ($i=0; $i < count($rombel); $i++) {
			$cek = $this->mod->getSome('kdsiswa',['koderombel' => $rombel[$i]['koderombel'],'koderekap' => $rekap]);
			$xl1 = "l1".$rombel[$i]['koderombel'];
			$xl2 = "l2".$rombel[$i]['koderombel'];
			$xl3 = "l3".$rombel[$i]['koderombel'];
			$xp1 = "p1".$rombel[$i]['koderombel'];
			$xp2 = "p2".$rombel[$i]['koderombel'];
			$xp3 = "p3".$rombel[$i]['koderombel'];
			$xabs = "abs".$rombel[$i]['koderombel'];
			if(count($cek) == 0){
				$isian = array(
					'kodekd' => null,
					'awal_l' => $get[$xl1],
					'awal_p' => $get[$xp1],
					'masuk_l' => $get[$xl2],
					'masuk_p' => $get[$xp2],
					'keluar_l' => $get[$xl3],
					'keluar_p' => $get[$xp3],
					'absensi' => $get[$xabs],
					'koderombel' => $rombel[$i]['koderombel'],
					'koderekap' => $rekap
				);
				$this->mod->inserting('kdsiswa',$isian);
			}else{
				$kode = $this->mod->getData('kdsiswa',['koderombel' => $rombel[$i]['koderombel'],'koderekap' => $rekap])['kodekd'];
				$isian = array(
					'awal_l' => $get[$xl1],
					'awal_p' => $get[$xp1],
					'masuk_l' => $get[$xl2],
					'masuk_p' => $get[$xp2],
					'keluar_l' => $get[$xl3],
					'keluar_p' => $get[$xp3],
					'absensi' => $get[$xabs]
				);
				$this->mod->updating('kdsiswa',$isian,['kodekd' => $kode]);
			}
		}
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = $get['buka'];
		return view('operator/laporan',$data);
	}

	public function ubahagama(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$rekap = $get['rekap'];
		$da = ['Budha','Hindu','Islam','Katolik','Kristen'];
		$rombel = unserialize($get['rombel']);
		for ($i=0; $i < count($rombel); $i++) {
			$cek = $this->mod->getSome('kdsiswa',['koderombel' => $rombel[$i]['koderombel'],'koderekap' => $rekap]);
			for ($j=0; $j < 5; $j++) {
				$x = strtolower($da[$j]);
				$x1 = "l".$x.$rombel[$i]['koderombel'];
				$x2 = "p".$x.$rombel[$i]['koderombel'];
				$x1 = $get[$x1];
				$x2 = $get[$x2];
				if($x1 > 0 || $x2 > 0){
					$cek = $this->mod->getSome('kdagama',['agama' => $da[$j],'koderombel' => $rombel[$i]['koderombel'],'koderekap' => $rekap]);
					if(count($cek) == 0){
						$input = array(
							'kodekd' => null,
							'jumlah_l' => $x1,
							'jumlah_p' => $x2,
							'agama' => $da[$j],
							'koderombel' => $rombel[$i]['koderombel'],
							'koderekap' => $rekap
						);
						$this->mod->inserting('kdagama',$input);
					}else{
						
					}
				}else{
					$this->mod->deleting('kdagama',['agama' => $da[$j],'koderombel' => $rombel[$i]['koderombel'],'koderekap' => $rekap]);
				}
			}
		}
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = $get['buka'];
		return view('operator/laporan',$data);
	}

	public function tambahptk(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$rekap = $get['rekap'];
		$unix = rand(100,99999999);
		$input = array(
			'kodeptk' => null,
			'nip' => $get['nip'],
			'nama' => $get['nama'],
			'jekel' => $get['jekel'],
			'tpl' => $get['tpt'],
			'tgl' => date('Y-m-d', strtotime($get['tgl'])),
			'agama' => $get['agama'],
			'ijazah' => $get['ijazah'],
			'jabatan' => $get['jabatan'],
			'golongan' => $get['golongan'],
			'kelas' => $get['kelas'],
			'bulan' => $get['masabulan'],
			'tahun' => $get['masatahun'],
			'keterangan' => $unix,
			'koderekap' => $rekap
		);
		$this->mod->inserting('kdptk',$input);
		$kode = $this->mod->getData('kdptk',['keterangan' => $unix])['kodeptk'];
		$this->mod->updating('kdptk',['keterangan' => $get['keterangan']],['kodeptk' => $kode]);
		$tgl1 = "0000-00-00";
		$tgl2 = "0000-00-00";
		$tgl3 = "0000-00-00";
		$tgl4 = "0000-00-00";
		if($get['noangkat'] != ''){
			$tgl1 = date('Y-m-d', strtotime($get['tglangkat']));
			$tgl2 = date('Y-m-d', strtotime($get['tmtangkat']));
		}
		if($get['noakhir'] != ''){
			$tgl3 = date('Y-m-d', strtotime($get['tglakhir']));
			$tgl4 = date('Y-m-d', strtotime($get['tmtakhir']));
		}
		$input = array(
			'kodesk' => null,
			'noangkat' => $get['noangkat'],
			'tglangkat' => $tgl1,
			'tmtangkat' => $tgl2,
			'noakhir' => $get['noakhir'],
			'tglakhir' => $tgl3,
			'tmtakhir' => $tgl4,
			'kodeptk' => $kode
		);
		$this->mod->inserting('sk',$input);
		$input = array(
			'kodetunjangan' => null,
			'tpg' => $get['tpg'],
			'insentif' => $get['insentif'],
			'kesra' => $get['kesra'],
			'kodeptk' => $kode
		);
		$this->mod->inserting('tunjangan',$input);
		$input = array(
			'kodeabsensi' => null,
			'sakit' => $get['sakit'],
			'ijin' => $get['ijin'],
			'alfa' => $get['alfa'],
			'dinas' => $get['dinas'],
			'kodeptk' => $kode
		);
		$this->mod->inserting('absensi',$input);
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = $get['buka'];
		return view('operator/laporan',$data);
	}

	public function tambahberkas(){
		$berkas = $this->request->getFile('berkas');
		$file = $berkas->getRandomName();
		$berkas->move('../public/assets/file/',$file);
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$rekap = $get['rekap'];
		$data = array(
			'kodeberkas' => null,
			'berkas' => $file,
			'deskripsi' => $get['deskripsi'],
			'koderekap' => $rekap
		);
		$this->mod->inserting('berkas',$data);
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = $get['buka'];
		return view('operator/laporan',$data);
	}

	public function kirim(){
		$get = $this->request->getPost();
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		$rekap = $get['rekap'];
		$waktu = date('Y-m-d H:i:s');
		$input = array(
			'waktu' => $waktu,
			'status' => '0'
		);
		$this->mod->updating('rekap',$input,['koderekap' => $rekap]);
		$input = array(
			'kodeverifikasi' => null,
			'waktu' => $waktu,
			'status' => '0',
			'catatan' => 'Data dikirim oleh Operator untuk diperiksa Kepala Sekolah',
			'kodepengguna' => $get['pengguna'],
			'koderekap' => $rekap
		);
		$this->mod->inserting('verifikasi',$input);
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['sekolah'] = $this->mod->getData('pengguna',['kodepengguna' => session()->get('low')])['kodesekolah'];
		$data['buka'] = '';
		return view('operator/laporan',$data);
	}
}
?>