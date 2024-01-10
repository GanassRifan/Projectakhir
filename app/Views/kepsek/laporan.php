<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$daftartahun = $db->query("select year(berdiri) as tahun from sekolah where kodesekolah = '".$sekolah."'")->getRowArray()['tahun'];
$daftaragama = ['Budha','Hindu','Islam','Katolik','Kristen'];
$relasi = "";
$rekap = "";
$status = "-";
$persen = 0;
$aspek = [];
$rombel = [];
$ptk = [];
$berkas = [];
$cek = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
if($cek > 0){
   $relasi = $db->query("select koderelasi from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
   $aspek = $db->query("select aspek.aspek from skema join aspek on skema.kodeaspek = aspek.kodeaspek where skema.koderelasi = '".$relasi."' group by aspek.aspek asc")->getResultArray();
   $rombel = $db->query("select * from rombel where kodesekolah = '".$sekolah."' and koderelasi = '".$relasi."'")->getResultArray();
   $cek = $db->query("select ifnull(count(*),0) as jumlah from rekap where kodesekolah = '".$sekolah."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
   if($cek > 0){
      $rk = $db->query("select * from rekap where kodesekolah = '".$sekolah."' and koderelasi = '".$relasi."'")->getRowArray();
      $op = $db->query("select nama from pengguna where kodepengguna = '".$rk['kodepengguna']."'")->getRowArray();
      $status = $rk['status'];
      $rekap = $rk['koderekap'];
      $ptk = $db->query("select * from kdptk where koderekap = '".$rekap."'")->getResultArray();
      $berkas = $db->query("select * from berkas where koderekap = '".$rekap."'")->getResultArray();
   }
}
$s = $db->query("select * from sekolah where kodesekolah = '".$sekolah."'")->getRowArray();
?>
<!DOCTYPE html>
<html lang="en">
<?php echo view('kepsek/part_head') ?>
<body id="default-scheme">
   <section id="container">
      <?php echo view('kepsek/part_header') ?>
      <?php echo view('kepsek/part_sidebar') ?>
      <section id="main-content">
         <section class="wrapper">
            <div class="top-page-header">
               <h2>Halaman Pengolah Data Laporan Bulanan</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Laporan Bulanan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('s/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('s/laporan') ?>">Input Laporan Bulanan</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Pengolahan Data Laporan Bulanan <?php echo $daftarbulan[(int)$bulan].' '.$tahun ?></h2>
                  <ul class="nav navbar-right panel_options">
                     <form method="post" action="<?php echo base_url('s/laporan/tampil') ?>">
                        <li>
                           <div class="row col-lg-13">
                              <div class="col-sm-7">
                                 <select class="form-control input-sm" name="bulan" style="color: black;" required onchange="this.form.submit()">
                                    <?php for ($i=1; $i <= count($daftarbulan) ; $i++) {?>
                                       <option <?php if($bulan == $i){echo "selected";} ?> value="<?php echo $i ?>"><?php echo $daftarbulan[$i] ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                              <div class="col-sm-5">
                                 <select class="form-control input-sm" name="tahun" style="color: black;" required onchange="this.form.submit()">
                                    <?php for ($i=date('Y'); $i >= $daftartahun ; $i--) {?>
                                       <option <?php if($tahun == $i){echo "selected";} ?>><?php echo $i ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </div>
                        </li>
                     </form>
                  </ul>
                  <div class="clearfix"></div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 projects">
                  <div class="project-box xs-box-shadowed bg-white">
                     <?php if($status == 'x'){ ?>
                        <a href="#detailrekap" class="btn btn-info btn-xs btn-flat pull-right">Detail Rekap <i class="fa fa-expand"></i></a>
                     <?php } ?>
                     <?php if($status == 1){ ?>
                        <a href="#verifikasirekap" data-toggle="modal" class="btn btn-warning btn-xs btn-flat pull-right">Verifikasi Rekap <i class="fa fa-check"></i></a>
                     <?php } ?>
                     <h4 class="project-title">Laporan Bulanan <?php echo $s['nama'] ?> Bulan <?php echo $daftarbulan[(int)$bulan] ?> Tahun <?php echo $tahun ?></h4>                     
                     <?php if($status != '-'){ ?>
                        <p class="project-edited">Operator : <span><?php echo $op['nama'] ?></span></p>
                        <p></p>
                        <?php if($status != ''){ ?>
                           <p class="project-timezone">Dikirim pada : <?php echo date('d/m/Y H:i:s', strtotime($rk['waktu'])) ?></p><p></p>
                        <?php } ?>
                     <?php } ?>
                     <hr>
                     <div class="row" style="margin-top:-10px; margin-bottom:-14px;">
                        <div class="col-md-4 project-total-hours">
                           <p>LOG AKTIFITAS</p>
                           <p>
                              <?php
                              if($status >= 0 && $status < 3){
                                 echo "Verifikasi Lembaga";
                              }else if($status >= 3 && $status < 6){
                                 echo "Verifikasi Korwilcam";
                              }else if($status >= 6 && $status < 9){
                                 echo "Verifikasi Pusat";
                              }else {
                                 echo "-";
                              }
                              ?>
                           </p>
                        </div>  
                        <div class="col-md-8 project-description">
                           <p>
                              <?php
                              if($status != '-'){
                                 if($status == ''){
                                    echo "Proses rekap data oleh Operator";
                                 }else{
                                    $vr1 = $db->query("select * from verifikasi where koderekap = '".$rekap."' order by waktu desc limit 1")->getRowArray();
                                    $vr2 = date('d/m/Y H:i:s', strtotime($vr1['waktu']));
                                    $vr3 = $db->query("select nama from pengguna where kodepengguna = '".$vr1['kodepengguna']."'")->getRowArray()['nama'];
                                    $vr4 = $vr1['catatan'];
                                    echo $vr3." : ".$vr4."<br>";
                                    echo $vr2;
                                 }
                              }
                              ?>
                           </p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                           <?php if($status != '-'){ ?>
                              <div class="project-task-numbers">
                                 <p>
                                    <?php
                                    if($status == 'x'){
                                       $persen = 100;
                                       echo "semua tugas selesai dikerjakan";
                                    }else{
                                       if($status == ''){
                                          echo "tugas dikerjakan";
                                       }else if($status == '0'){
                                          echo "1";
                                          $persen = 10;
                                       }else if($status == '1'){
                                          echo "2";
                                          $persen = 20;
                                       }else if($status == '2'){
                                          echo "3";
                                          $persen = 30;
                                       }else if($status == '3'){
                                          echo "4";
                                          $persen = 40;
                                       }else if($status == '4'){
                                          echo "5";
                                          $persen = 50;
                                       }else if($status == '5'){
                                          echo "6";
                                          $persen = 60;
                                       }else if($status == '6'){
                                          echo "7";
                                          $persen = 70;
                                       }else if($status == '7'){
                                          echo "8";
                                          $persen = 80;
                                       }else if($status == '8'){
                                          echo "9";
                                          $persen = 90;
                                       }
                                       if($status != ''){
                                          echo " dari 10 tugas dikerjakan";
                                       }
                                    }
                                    ?>
                                 </p>
                                 <div class="progress progress-xs">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $persen ?>%">
                                    </div>
                                 </div>
                              </div>
                           <?php } ?>
                        </div>
                        <div class="col-md-6">
                           <?php if($status == ''){ ?>
                              <p class="pull-right"><i class="fa fa-circle text-info"></i> Proses Rekap</p>
                           <?php }else if($status == 'x'){ ?>
                              <p class="pull-right"><i class="fa fa-circle text-success"></i> Selesai</p>
                           <?php }else if($status == '-'){ ?>
                              <p class="pull-right"><i class="fa fa-circle text-danger"></i> Belum</p>
                           <?php }else if($status == 2 || $status == 5 || $status == 8){ ?>
                              <p class="pull-right"><i class="fa fa-circle text-info"></i> Penyesuaian</p>
                           <?php }else{ ?>
                              <p class="pull-right"><i class="fa fa-circle text-warning"></i> Verifikasi</p>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
                  <div class="panel-group horizontal" id="accordion3">
                     <div class="panel panel-info">
                        <div class="panel-heading">
                           <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse1" <?php if($buka != 'umum'){?> class="collapsed" <?php } ?>>Keadaan Umum Lembaga</a>
                           </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse <?php if($buka == 'umum'){echo "in";} ?>">
                           <div class="panel-body">
                              <div class="row">
                                 <?php
                                 foreach ($aspek as $a) {
                                    $subaspek = $db->query("select * from aspek where aspek = '".$a['aspek']."' order by subaspek asc")->getResultArray();
                                    ?>
                                    <div class="col-md-4">
                                       <b><?php echo $a['aspek'] ?></b>
                                       <?php
                                       foreach ($subaspek as $sa) {
                                          $rincian = '-';
                                          $cek = $db->query("select ifnull(count(*),0) as jumlah from skema where kodeaspek = '".$sa['kodeaspek']."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
                                          if($cek > 0){
                                             $cek = $db->query("select ifnull(count(*),0) as jumlah from rincian where kodeaspek = '".$sa['kodeaspek']."' and koderekap = '".$rekap."'")->getRowArray()['jumlah'];
                                             if($cek > 0){
                                                $rincian = $db->query("select rincian from rincian where kodeaspek = '".$sa['kodeaspek']."' and koderekap = '".$rekap."'")->getRowArray()['rincian'];
                                                if($sa['jenis'] == 'angka'){
                                                   $rincian .= " ".$sa['satuan'];
                                                }
                                             }
                                             ?>
                                             <div class="row">
                                                <div class="col-sm-6"><?php echo $sa['subaspek'] ?></div>
                                                <div class="col-sm-6"><?php echo ": ".$rincian ?></div>
                                             </div>
                                          <?php } ?>
                                       <?php } ?>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse2" <?php if($buka != 'siswa'){?> class="collapsed" <?php } ?>>Keadaan Anak Didik</a>
                           </h4>
                        </div>
                        <div id="collapse2" class="panel-collapse collapse <?php if($buka == 'siswa'){echo "in";} ?>">
                           <div class="panel-body">
                              <table class="table-bordered" style="width: 100%;">
                                 <thead>
                                    <tr style="height:30px">
                                       <th rowspan="2" style="text-align: center;">Rombel</th>
                                       <th rowspan="2" style="text-align: center;" width="5%">Jumlah</th>
                                       <th colspan="3" style="text-align: center;">Awal Bulan</th>
                                       <th colspan="3" style="text-align: center;">Masuk</th>
                                       <th colspan="3" style="text-align: center;">Keluar</th>
                                       <th colspan="3" style="text-align: center;">Akhir Bulan</th>
                                       <th rowspan="2" style="text-align: center;" width="7%">Persentase Absen</th>
                                    </tr>
                                    <tr style="height:30px">
                                       <th style="text-align: center;" width="6%">L</th>
                                       <th style="text-align: center;" width="6%">P</th>
                                       <th style="text-align: center;" width="6%">JML</th>
                                       <th style="text-align: center;" width="6%">L</th>
                                       <th style="text-align: center;" width="6%">P</th>
                                       <th style="text-align: center;" width="6%">JML</th>
                                       <th style="text-align: center;" width="6%">L</th>
                                       <th style="text-align: center;" width="6%">P</th>
                                       <th style="text-align: center;" width="6%">JML</th>
                                       <th style="text-align: center;" width="6%">L</th>
                                       <th style="text-align: center;" width="6%">P</th>
                                       <th style="text-align: center;" width="6%">JML</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    foreach ($rombel as $r) {
                                       $jl1 = 0;
                                       $jp1 = 0;
                                       $jl2 = 0;
                                       $jp2 = 0;
                                       $jl3 = 0;
                                       $jp3 = 0;
                                       $ja = 0;
                                       $cek = $db->query("select ifnull(count(*),0) as jumlah from kdsiswa where koderombel = '".$r['koderombel']."' and koderekap = '".$rekap."'")->getRowArray()['jumlah'];
                                       if($cek > 0){
                                          $cek = $db->query("select * from kdsiswa where koderombel = '".$r['koderombel']."' and koderekap = '".$rekap."'")->getRowArray();
                                          $jl1 = $cek['awal_l'];
                                          $jp1 = $cek['awal_p'];
                                          $jl2 = $cek['masuk_l'];
                                          $jp2 = $cek['masuk_p'];
                                          $jl3 = $cek['keluar_l'];
                                          $jp3 = $cek['keluar_p'];
                                          $ja = $cek['absensi'];
                                       }
                                       ?>
                                       <tr style="height: 25px;">
                                          <td>&nbsp;<?php echo $r['rombel'] ?></td>
                                          <td align="center"><?php echo number_format($r['jumlah']) ?></td>
                                          <td align="center"><?php echo number_format($jl1) ?></td>
                                          <td align="center"><?php echo number_format($jp1) ?></td>
                                          <td align="center"><?php echo number_format($jl1 + $jp1) ?></td>
                                          <td align="center"><?php echo number_format($jl2) ?></td>
                                          <td align="center"><?php echo number_format($jp2) ?></td>
                                          <td align="center"><?php echo number_format($jl2 + $jp2) ?></td>
                                          <td align="center"><?php echo number_format($jl3) ?></td>
                                          <td align="center"><?php echo number_format($jp3) ?></td>
                                          <td align="center"><?php echo number_format($jl3 + $jp3) ?></td>
                                          <td align="center"><?php echo number_format($jl1 + $jl2 - $jl3) ?></td>
                                          <td align="center"><?php echo number_format($jp2 + $jp2 - $jp3) ?></td>
                                          <td align="center"><?php echo number_format(($jl1 + $jp1) + ($jl2 + $jp2) - ($jl3 + $jp3)) ?></td>
                                          <td align="center"><?php echo number_format($ja)."%" ?></td>
                                       </tr>
                                    <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse3" <?php if($buka != 'agama'){?> class="collapsed" <?php } ?>>Keadaan Agama Anak Didik</a>
                           </h4>
                        </div>
                        <div id="collapse3" class="panel-collapse collapse <?php if($buka == 'agama'){echo "in";} ?>">
                           <div class="panel-body">
                              <table class="table-bordered" style="width: 100%;">
                                 <thead>
                                    <tr style="height:30px">
                                       <th rowspan="2" style="text-align: center;">Rombel</th>
                                       <?php for ($i=0; $i < 5; $i++) {?>
                                          <th colspan="2" style="text-align: center;"><?php echo $daftaragama[$i] ?></th>
                                       <?php } ?>
                                       <th colspan="3" style="text-align: center;">Jumlah</th>
                                    </tr>
                                    <tr style="height:30px">
                                       <?php for ($i=0; $i < 6; $i++) {?>
                                          <th style="text-align: center;" width="6%">L</th>
                                          <th style="text-align: center;" width="6%">P</th>
                                       <?php } ?>
                                       <th style="text-align: center;" width="6%">Total</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    foreach ($rombel as $r) {
                                       $isi = $db->query("select * from kdsiswa where koderombel = '".$r['koderombel']."' and koderekap = '".$rekap."'")->getRowArray();
                                       ?>
                                       <tr style="height: 25px;">
                                          <td>&nbsp;<?php echo $r['rombel'] ?></td>
                                          <?php
                                          $totl = 0;
                                          $totp = 0;
                                          for ($i=0; $i < 5; $i++) {
                                             $jl = $db->query("select ifnull(sum(jumlah_l),0) as jumlah from kdagama where agama = '".$daftaragama[$i]."' and koderombel = '".$r['koderombel']."' and koderekap = '".$rekap."'")->getRowArray()['jumlah'];
                                             $jp = $db->query("select ifnull(sum(jumlah_p),0) as jumlah from kdagama where agama = '".$daftaragama[$i]."' and koderombel = '".$r['koderombel']."' and koderekap = '".$rekap."'")->getRowArray()['jumlah'];
                                             $totl += $jl;
                                             $totp += $jp;
                                             ?>
                                             <td align="center"><?php echo number_format($jl) ?></td>
                                             <td align="center"><?php echo number_format($jp) ?></td>
                                          <?php } ?>
                                          <td align="center"><?php echo number_format($totl) ?></td>
                                          <td align="center"><?php echo number_format($totp) ?></td>
                                          <td align="center"><?php echo number_format($totl + $totp) ?></td>
                                       </tr>
                                    <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-warning">
                        <div class="panel-heading">
                           <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse4" <?php if($buka != 'ptk'){?> class="collapsed" <?php } ?>>Keadaan Pendidik dan Tenaga Kependidikan</a>
                           </h4>
                        </div>
                        <div id="collapse4" class="panel-collapse collapse <?php if($buka == 'ptk'){echo "in";} ?>">
                           <div class="panel-body">
                              <table class="table-bordered" style="width: 100%;">
                                 <thead>
                                    <tr style="height:30px">
                                       <th rowspan="2" style="text-align: center;" width="3%">No</th>
                                       <th rowspan="2" style="text-align: center;" width="20%">Nama / NIP</th>
                                       <th rowspan="2" style="text-align: center;" width="3%">L/P</th>
                                       <th rowspan="2" style="text-align: center;" width="18%">Tempat, Tanggal Lahir</th>
                                       <th rowspan="2" style="text-align: center;">Agama</th>
                                       <th rowspan="2" style="text-align: center;" width="18%">Ijazah Terakhir</th>
                                       <th rowspan="2" style="text-align: center;">Jabatan</th>
                                       <th rowspan="2" style="text-align: center;">Gol. R.</th>
                                       <th colspan="2" style="text-align: center;">Masa Kerja</th>
                                    </tr>
                                    <tr style="height:30px">
                                       <th style="text-align: center;" width="6%">Tahun</th>
                                       <th style="text-align: center;" width="6%">Bulan</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    $n = 1;
                                    foreach ($ptk as $p) {
                                       ?>
                                       <tr style="height: 25px;">
                                          <td align="center">&nbsp;<?php echo $n++ ?></td>
                                          <td>
                                             &nbsp;<?php echo $p['nama'] ?><br>
                                             &nbsp;<small><?php echo $p['nip'] ?></small>
                                          </td>
                                          <td align="center"><?php echo $p['jekel'] ?></td>
                                          <td>&nbsp;<?php echo $p['tpl'].', '.date('d/m/Y', strtotime($p['tgl'])) ?></td>
                                          <td align="center"><?php echo $p['agama'] ?></td>
                                          <td>&nbsp;<?php echo $p['ijazah'] ?></td>
                                          <td align="center"><?php echo $p['jabatan'] ?></td>
                                          <td align="center"><?php echo $p['golongan'] ?></td>
                                          <td align="center"><?php echo $p['bulan'] ?></td>
                                          <td align="center"><?php echo $p['tahun'] ?></td>
                                       </tr>
                                    <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-warning">
                        <div class="panel-heading">
                           <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse5" <?php if($buka != 'sk'){?> class="collapsed" <?php } ?>>Status SK Pendidik dan Tenaga Kependidikan</a>
                           </h4>
                        </div>
                        <div id="collapse5" class="panel-collapse collapse <?php if($buka == 'sk'){echo "in";} ?>">
                           <div class="panel-body">
                              <table class="table-bordered" style="width: 100%;">
                                 <thead>
                                    <tr style="height:30px">
                                       <th rowspan="2" style="text-align: center;" width="3%">No</th>
                                       <th rowspan="2" style="text-align: center;" width="20%">Nama / NIP</th>
                                       <th rowspan="2" style="text-align: center;" width="3%">L/P</th>
                                       <th colspan="3" style="text-align: center;">SK Pengangkatan</th>
                                       <th colspan="3" style="text-align: center;">SK Terakhir</th>
                                    </tr>
                                    <tr style="height:30px">
                                       <th style="text-align: center;">Nomor</th>
                                       <th style="text-align: center;" width="10%">Tgl. Surat</th>
                                       <th style="text-align: center;" width="10%">Tmt</th>
                                       <th style="text-align: center;">Nomor</th>
                                       <th style="text-align: center;" width="10%">Tgl. Surat</th>
                                       <th style="text-align: center;" width="10%">Tmt</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    $n = 1;
                                    foreach ($ptk as $p) {
                                       $sk = $db->query("select * from sk where kodeptk = '".$p['kodeptk']."'")->getRowArray();
                                       $no1 = '';
                                       $tgl1 = '-';
                                       $tmt1 = '-';
                                       $no2 = '';
                                       $tgl2 = '-';
                                       $tmt2 = '-';
                                       if($sk['noangkat'] != ''){
                                          $no1 = $sk['noangkat'];
                                          $tgl1 = date('d/m/Y', strtotime($sk['tglangkat']));
                                          $tmt1 = date('d/m/Y', strtotime($sk['tmtangkat']));
                                       }
                                       if($sk['noakhir'] != ''){
                                          $no1 = $sk['noakhir'];
                                          $tgl1 = date('d/m/Y', strtotime($sk['tglakhir']));
                                          $tmt1 = date('d/m/Y', strtotime($sk['tmtakhir']));
                                       }
                                       ?>
                                       <tr style="height: 25px;">
                                          <td align="center">&nbsp;<?php echo $n++ ?></td>
                                          <td>
                                             &nbsp;<?php echo $p['nama'] ?><br>
                                             &nbsp;<small><?php echo $p['nip'] ?></small>
                                          </td>
                                          <td align="center"><?php echo $p['jekel'] ?></td>
                                          <td>&nbsp;<?php echo $no1 ?></td>
                                          <td align="center"><?php echo $tgl1 ?></td>
                                          <td align="center"><?php echo $tmt1 ?></td>
                                          <td>&nbsp;<?php echo $no2 ?></td>
                                          <td align="center"><?php echo $tgl2 ?></td>
                                          <td align="center"><?php echo $tmt2 ?></td>
                                       </tr>
                                    <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-warning">
                        <div class="panel-heading">
                           <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse6" <?php if($buka != 'tunjangan'){?> class="collapsed" <?php } ?>>Tunjangan Pendidik dan Tenaga Kependidikan</a>
                           </h4>
                        </div>
                        <div id="collapse6" class="panel-collapse collapse <?php if($buka == 'tunjangan'){echo "in";} ?>">
                           <div class="panel-body">
                              <table class="table-bordered" style="width: 100%;">
                                 <thead>
                                    <tr style="height:30px">
                                       <th rowspan="2" style="text-align: center;" width="3%">No</th>
                                       <th rowspan="2" style="text-align: center;" width="20%">Nama / NIP</th>
                                       <th rowspan="2" style="text-align: center;" width="3%">L/P</th>
                                       <th rowspan="2" style="text-align: center;" width="5%">Kelas</th>
                                       <th colspan="3" style="text-align: center;">Tunjangan Lain</th>
                                       <th colspan="5" style="text-align: center;">Absensi</th>
                                       <th rowspan="2" style="text-align: center;" width="27%">Keterangan</th>
                                    </tr>
                                    <tr style="height:30px">
                                       <th style="text-align: center;" width="10%">TPG</th>
                                       <th style="text-align: center;" width="10%">Insentif</th>
                                       <th style="text-align: center;" width="10%">Kesra</th>
                                       <th style="text-align: center;" width="3%">S</th>
                                       <th style="text-align: center;" width="3%">I</th>
                                       <th style="text-align: center;" width="3%">A</th>
                                       <th style="text-align: center;" width="3%">DL</th>
                                       <th style="text-align: center;" width="5%">Jumlah</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    $n = 1;
                                    foreach ($ptk as $p) {
                                       $tj = $db->query("select * from tunjangan where kodeptk = '".$p['kodeptk']."'")->getRowArray();
                                       $ab = $db->query("select * from absensi where kodeptk = '".$p['kodeptk']."'")->getRowArray();
                                       $jml = $ab['sakit'] + $ab['ijin'] + $ab['alfa'] + $ab['dinas'];
                                       ?>
                                       <tr style="height: 25px;">
                                          <td align="center">&nbsp;<?php echo $n++ ?></td>
                                          <td>
                                             &nbsp;<?php echo $p['nama'] ?><br>
                                             &nbsp;<small><?php echo $p['nip'] ?></small>
                                          </td>
                                          <td align="center"><?php echo $p['jekel'] ?></td>
                                          <td align="center"><?php echo $p['kelas'] ?></td>
                                          <td align="right"><?php echo number_format($tj['tpg']) ?>&nbsp;</td>
                                          <td align="right"><?php echo number_format($tj['insentif']) ?>&nbsp;</td>
                                          <td align="right"><?php echo number_format($tj['kesra']) ?>&nbsp;</td>
                                          <td align="center"><?php echo number_format($ab['sakit']) ?></td>
                                          <td align="center"><?php echo number_format($ab['ijin']) ?></td>
                                          <td align="center"><?php echo number_format($ab['alfa']) ?></td>
                                          <td align="center"><?php echo number_format($ab['dinas']) ?></td>
                                          <td align="center"><?php echo number_format($jml) ?></td>
                                          <td>&nbsp;<?php echo $p['keterangan'] ?></td>
                                       </tr>
                                    <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-success">
                        <div class="panel-heading">
                           <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse7" <?php if($buka != 'berkas'){?> class="collapsed" <?php } ?>>Berkas Pendukung</a>
                           </h4>
                        </div>
                        <div id="collapse7" class="panel-collapse collapse <?php if($buka == 'berkas'){echo "in";} ?>">
                           <div class="panel-body">
                              <?php if(count($berkas) > 0){ ?>
                                 <?php foreach ($berkas as $b) {?>
                                    <i><?php echo $b['deskripsi'] ?></i>&nbsp;&nbsp;<a href="<?php echo base_url('/assets/file/'.$b['berkas']) ?>" download title="Klik untuk mengunduh berkas"><i class="fa fa-download"></i></a><br>
                                 <?php } ?>
                              <?php }else{ ?>
                                 <b><i>Tidak Ada Berkas Lampiran</i></b>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('kepsek/part_script') ?>
</body>
<div class="modal" id="verifikasirekap" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Verifikasi Data</h4>
         </div>
         <form action="<?php echo base_url('s/laporan/tolak') ?>" method="post">
            <input type="hidden" name="kode" value="<?php echo $rekap ?>">
            <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
            <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
            <input type="hidden" name="sekolah" value="<?php echo $sekolah ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Jika laporan bulanan sudah sesuai, pilih tombol <code>Laporan Sesuai</code> untuk memverifikasi persetujuan. Atau masukkan catatan khusus jika laporan belum sesuai, lalu pilih tombol <code>Laporan Tidak Sesuai</code></p>
               <div class="form-group">
                  <label for="exampleInputEmail1">Catatan Verifikasi</label>
                  <textarea class="form-control input-sm" name="catatan" placeholder="Uraian Catatan Verifikasi Laporan Bulanan" rows="9" style="resize: none;color: black;" required></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <a href="<?php echo base_url('s/laporan/terima/'.$rekap) ?>" class="btn btn-success btn-raised rippler rippler-default btn-sm">Laporan Sesuai</a>
               <button type="submit" class="btn btn-warning btn-raised rippler rippler-default btn-sm">Laporan Tidak Sesuai</button>
            </div>
         </form>
      </div>
   </div>
</div>
</html>