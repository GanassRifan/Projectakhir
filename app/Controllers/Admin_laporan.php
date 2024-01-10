<?php
namespace App\Controllers;
use CodeIgniter\Config\Services;
use App\Models\Databasemodel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Libraries\Fpdf\Fpdf;
class Admin_laporan extends BaseController{
	function __construct(){
		$this->mod = new Databasemodel();
		$this->db = db_connect();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index(){
		if(session()->get('top')){
			$bulan = date('m');
			$tahun = date('Y');
			$kecamatan = 'Bojong';
			$data['bulan'] = (int)$bulan;
			$data['tahun'] = $tahun;
			$data['kecamatan'] = $kecamatan;
			$data['sekolah'] = $this->mod->getAll("sekolah");
			return view('admin/laporan',$data);
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
		$kecamatan = $get['kecamatan'];
		$data['bulan'] = (int)$bulan;
		$data['tahun'] = $tahun;
		$data['kecamatan'] = $kecamatan;
		$data['sekolah'] = $this->mod->getAll("sekolah");
		return view('admin/laporan',$data);
	}

	public function detail($x){
		$x = $this->mod->getData('rekap',['koderekap' => $x]);
		if($x['status'] == 6){
			$this->mod->updating('rekap',['status' => '7'],['koderekap' => $x['koderekap']]);
			$data = array(
				'kodeverifikasi' => null,
				'waktu' => date('Y-m-d H:i:s'),
				'status' => '7',
				'catatan' => 'Data diterima. Menunggu verifikasi Pusat',
				'kodepengguna' => session()->get('top'),
				'koderekap' => $x['koderekap']
			);
			$this->mod->inserting('verifikasi',$data);
		}
		$data['rekap'] = $this->mod->getData('rekap',['koderekap' => $x['koderekap']]);
		$data['operator'] = $this->mod->getData('pengguna',['kodepengguna' => $x['kodepengguna']]);
		$data['sekolah'] = $this->mod->getData('sekolah',['kodesekolah' => $x['kodesekolah']]);
		$data['relasi'] = $this->mod->getData('relasi',['koderelasi' => $x['koderelasi']]);
		$data['skema'] = $this->db->query("select aspek.* from skema join aspek on skema.kodeaspek = aspek.kodeaspek where skema.koderelasi = '".$x['koderelasi']."' group by aspek.aspek asc")->getResultArray();
		$data['aspek'] = $this->db->query("select aspek.* from skema join aspek on skema.kodeaspek = aspek.kodeaspek where skema.koderelasi = '".$x['koderelasi']."' order by aspek.aspek asc, aspek.kodeaspek asc ")->getResultArray();
		$data['berkas'] = $this->mod->getSome('berkas',['koderekap' => $x['koderekap']]);
		$data['ptk'] = $this->mod->getSome('kdptk',['koderekap' => $x['koderekap']]);
		$data['log'] = $this->db->query("select * from verifikasi where koderekap = '".$x['koderekap']."' order by waktu asc")->getResultArray();
		return view('admin/laporandetail',$data);
	}

	public function tolak(){
		$get = $this->request->getPost();
		$this->mod->updating('rekap',['status' => '8'],['koderekap' => $get['kode']]);
		$data = array(
			'kodeverifikasi' => null,
			'waktu' => date('Y-m-d H:i:s'),
			'status' => '8',
			'catatan' => $get['catatan'],
			'kodepengguna' => session()->get('top'),
			'koderekap' => $get['kode']
		);
		$this->mod->inserting('verifikasi',$data);
		return redirect()->to( base_url('a/laporan'));
	}

	public function terima($x){
		$this->mod->updating('rekap',['status' => 'x'],['koderekap' => $x]);
		$data = array(
			'kodeverifikasi' => null,
			'waktu' => date('Y-m-d H:i:s'),
			'status' => 'x',
			'catatan' => 'Data telah diverifikasi oleh Pusat. Sesuai',
			'kodepengguna' => session()->get('top'),
			'koderekap' => $x
		);
		$this->mod->inserting('verifikasi',$data);
		return redirect()->to( base_url('a/laporan'));
	}

	public function cetak(){
		$get = $this->request->getPost();
		$kecamatan = $get['kecamatan'];
		$bulan = (int)$get['bulan'];
		$tahun = $get['tahun'];
		if($get['jenis'] == 'Pdf'){
			$this->cetakpdf($kecamatan,$bulan,$tahun);
		}else{
			$this->cetakexcel($kecamatan,$bulan,$tahun);
		}
	}

	public function cetakpdf($a,$b,$c){
		$relasi = "";
		$cek = $this->db->query("select * from relasi where bulan = '".(int)$b."' and tahun = '".$c."'")->getResultArray();
		if(count($cek) > 0){
			$relasi = $this->db->query("select * from relasi where bulan = '".(int)$b."' and tahun = '".$c."'")->getRowArray()['koderelasi'];
		}
		$sekolah = $this->mod->getSome('sekolah',['kecamatan' => $a]);
		$bulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$this->pdf = new fpdf('L','mm','A4');

		$this->pdf->AddPage();
		$this->pdf->SetFont('Times','B',12);
		$this->pdf->Cell(278,5,'LAPORAN REKAPITULASI DATA',0,1,'C');
		$this->pdf->Cell(278,5,strtoupper($bulan[(int)$b]).' '.$c,0,1,'C');
		$this->pdf->Cell(278,5,'KECAMATAN '.strtoupper($a),0,1,'C');
		$this->pdf->Ln(10);

		$this->pdf->SetFont('Times','B',11);
		$this->pdf->Cell(9,6,'No',1,0,'C');
		$this->pdf->Cell(65,6,'Sekolah',1,0,'C');
		$this->pdf->Cell(45,6,'Kepala Sekolah',1,0,'C');
		$this->pdf->Cell(36,6,'Waktu Kirim',1,0,'C');
		$this->pdf->Cell(123,6,'Status',1,1,'C');

		
		if(count($sekolah) == 0){
			$this->pdf->SetFont('Times','I',10);
			$this->pdf->Cell(278,6,'data kosong...',1,1,'C');
		}else{
			$this->pdf->SetFont('Times','',10);
			$n = 1;
			foreach ($sekolah as $s) {
				$kode = "";
				$kepsek = "";
				$wktkirim = "-";
				$st = "";
				$status = "Belum";
				$cek = $this->db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$s['kodesekolah']."' and status = 'Aktif'")->getResultArray();
				if(count($cek) > 0){
					$kepsek = $this->db->query("select nama from pengguna where level = 'mid' and kodesekolah = '".$s['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
				}
				$cek = $this->db->query("select * from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getResultArray();
				if(count($cek) > 0){
					$kode = $this->db->query("select koderekap from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['koderekap'];
					$st = $this->db->query("select status from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['status'];
					$status = $this->db->query("select * from verifikasi where koderekap = '".$kode."' order by waktu desc")->getRowArray();
					$wktkirim = $this->db->query("select waktu from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['waktu'];
					$wktkirim = date('d/m/Y H:i:s', strtotime($wktkirim));
					$status = $status['catatan'].' ('.date('d/m/Y H:i:s', strtotime($status['waktu'])).')';
				}
				$this->pdf->Cell(9,6,$n++,1,0,'C');
				$this->pdf->Cell(65,6,$s['nama'],1,0);
				$this->pdf->Cell(45,6,$kepsek,1,0);
				$this->pdf->Cell(36,6,$wktkirim,1,0,'C');
				$this->pdf->Cell(123,6,$status,1,1);
			}
		}

		$this->pdf->Ln(5);

		$this->pdf->SetFont('Times','',11);
		$this->pdf->Cell(200,6,'',0,0,'C');
		$this->pdf->Cell(78,6,'Pekalongan, '.$this->tanggal_indo(date('Y-m-d')),0,1,'C');
		$this->pdf->Cell(200,6,'',0,0,'C');
		$this->pdf->Cell(78,6,'Mengetahui,',0,1,'C');
		$this->pdf->Ln(15);
		$this->pdf->SetFont('Times','BU',11);
		$this->pdf->Cell(200,6,'',0,0,'C');
		$this->pdf->Cell(78,6,'Pimpinan',0,1,'C');

		$this->pdf->Output();
		exit;
	}

	public function cetakexcel($a,$b,$c){
		$spreadsheet = new Spreadsheet();
		$relasi = "";
		$cek = $this->db->query("select * from relasi where bulan = '".(int)$b."' and tahun = '".$c."'")->getResultArray();
		if(count($cek) > 0){
			$relasi = $this->db->query("select * from relasi where bulan = '".(int)$b."' and tahun = '".$c."'")->getRowArray()['koderelasi'];
		}
		$sekolah = $this->mod->getSome('sekolah',['kecamatan' => $a]);
		$bulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'LAPORAN REKAPITULASI DATA');
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', strtoupper($bulan[(int)$b]).' '.$c);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'KECAMATAN '.strtoupper($a));
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A4', 'No.')
		->setCellValue('B4', 'Sekolah')
		->setCellValue('C4', 'Kepala Sekolah')
		->setCellValue('D4', 'Waktu Kirim')
		->setCellValue('E4', 'Status');

		$column = 5;
		$n = 1;
		foreach ($sekolah as $s) {
			$kode = "";
			$kepsek = "";
			$wktkirim = "-";
			$st = "";
			$status = "Belum";
			$cek = $this->db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$s['kodesekolah']."' and status = 'Aktif'")->getResultArray();
			if(count($cek) > 0){
				$kepsek = $this->db->query("select nama from pengguna where level = 'mid' and kodesekolah = '".$s['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
			}
			$cek = $this->db->query("select * from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getResultArray();
			if(count($cek) > 0){
				$kode = $this->db->query("select koderekap from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['koderekap'];
				$st = $this->db->query("select status from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['status'];
				$status = $this->db->query("select * from verifikasi where koderekap = '".$kode."' order by waktu desc")->getRowArray();
				$wktkirim = $this->db->query("select waktu from rekap where kodesekolah = '".$s['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['waktu'];
				$wktkirim = date('d/m/Y H:i:s', strtotime($wktkirim));
				$status = $status['catatan'].' ('.date('d/m/Y H:i:s', strtotime($status['waktu'])).')';
			}
			$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A'.$column, $n++)
			->setCellValue('B'.$column, $s['nama'])
			->setCellValue('C'.$column, $kepsek)
			->setCellValue('D'.$column, $wktkirim)
			->setCellValue('E'.$column, $status);
			$column++;
		}

		$writer = new Xlsx($spreadsheet);
		$filename = "Laporan_Rekapitulasi_".$a."_".$bulan[(int)$b]."_".$c;
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function submissionprintxls($b, $t){
		$bulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$data = $this->db->query("select * from pengajuan where month(tglpengajuan) = '".$b."' and year(tglpengajuan) = '".$t."' order by tglpengajuan asc")->getResultArray();
		$spreadsheet = new Spreadsheet();

		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A1', 'LAPORAN DATA PENGAJUAN KREDIT');
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A2', $bulan[(int)$b].' '.$t);

		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A3', 'No.')
		->setCellValue('B3', 'Tanggal')
		->setCellValue('C3', 'Jenis Kredit')
		->setCellValue('D3', 'Plafon')
		->setCellValue('E3', 'Tenor')
		->setCellValue('F3', 'Status');

		$column = 4;
		$x = 1;

		foreach ($data as $d) {
			$k = $this->db->query("select * from kredit where idkredit = '".$d['idkredit']."'")->getRowArray()['kredit'];
			$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A'.$column, $x++)
			->setCellValue('B'.$column, date('d/m/Y', strtotime($d['tglpengajuan'])))
			->setCellValue('C'.$column, $k)
			->setCellValue('D'.$column, "Rp".number_format($d['plafon']))
			->setCellValue('E'.$column, $d['tenor']." bulan")
			->setCellValue('F'.$column, $d['status']);
			$column++;
		}

		$writer = new Xlsx($spreadsheet);
		$filename = "Laporan_Pengajuan_Kredit_".$bulan[(int)$b]." ".$t;

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	function tanggal_indo($tanggal, $cetak_hari = false){
		$bulan = array (1 =>   'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		$split    = explode('-', $tanggal);
		$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
		return $tgl_indo;
	}
}
?>