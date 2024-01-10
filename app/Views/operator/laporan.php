<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$daftartahun = $db->query("select year(berdiri) as tahun from sekolah where kodesekolah = '".$sekolah."'")->getRowArray()['tahun'];
$daftaragama = ['Budha','Hindu','Islam','Katolik','Kristen'];
$relasi = "";
$rekap = "";
$status = "-";
$persen = 0;
$kunci = 0;
$aspek = [];
$rombel = [];
$ptk = [];
$berkas = [];
$cek = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
if($cek > 0){
   $rl = $db->query("select * from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray();
   $relasi = $rl['koderelasi'];
   $kunci = $rl['status'];
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
<?php echo view('operator/part_head') ?>
<body id="default-scheme">
   <section id="container">
      <?php echo view('operator/part_header') ?>
      <?php echo view('operator/part_sidebar') ?>
      <section id="main-content">
         <section class="wrapper">
            <div class="top-page-header">
               <h2>Halaman Pengolah Data Laporan Bulanan</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Laporan Bulanan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('o/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('o/laporan') ?>">Input Laporan Bulanan</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Pengolahan Data Laporan Bulanan <?php echo $daftarbulan[(int)$bulan].' '.$tahun ?></h2>
                  <ul class="nav navbar-right panel_options">
                     <form method="post" action="<?php echo base_url('o/laporan/tampil') ?>">
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
                     <?php if($kunci == '1'){ ?>
                        <?php if($status == '-'){ ?>
                           <a href="#buatrekap" data-toggle="modal" class="btn btn-success btn-xs btn-flat pull-right">Buat Rekap <i class="fa fa-pencil"></i></a>
                        <?php }else if($status == '' || $status == 2 || $status == 5 || $status == 8){ ?>
                           <?php if(count($ptk) > 0){ ?>
                              <a href="#kirimrekap" data-toggle="modal" class="btn btn-warning btn-xs btn-flat pull-right">Kirim Rekap <i class="fa fa-send"></i></a>
                           <?php } ?>
                           <a href="#editrekap" data-toggle="modal" class="btn btn-success btn-xs btn-flat pull-right" style="margin-right: 10px;">Edit Rekap <i class="fa fa-edit"></i></a>
                        <?php }else if($status == 'x'){ ?>
                           <a href="#detailrekap" data-toggle="modal" class="btn btn-info btn-xs btn-flat pull-right">Detail Rekap <i class="fa fa-expand"></i></a>
                        <?php } ?>
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
                              <?php if($status == '' || $status == 2 || $status == 5 || $status == 8){ ?>
                                 <a href="#ubahumum" data-toggle="modal" class="pull-right" title="Klik untuk mengubah data" style="margin-top: 10px;margin-right: 10px;font-size: 1.5em;"><i class="fa fa-edit"></i></a>
                              <?php } ?>
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse1" <?php if($buka != 'umum'){?> class="collapsed" <?php } ?>>
                                 Keadaan Umum Lembaga
                              </a>
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
                              <?php if($status == '' || $status == 2 || $status == 5 || $status == 8){ ?>
                                 <a href="#ubahsiswa" data-toggle="modal" class="pull-right" title="Klik untuk mengubah data" style="margin-top: 10px;margin-right: 10px;font-size: 1.5em;"><i class="fa fa-edit"></i></a>
                              <?php } ?>
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
                              <?php if($status == '' || $status == 2 || $status == 5 || $status == 8){ ?>
                                 <a href="#ubahagama" data-toggle="modal" class="pull-right" title="Klik untuk mengubah data" style="margin-top: 10px;margin-right: 10px;font-size: 1.5em;"><i class="fa fa-edit"></i></a>
                              <?php } ?>
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
                              <?php if($status == '' || $status == 2 || $status == 5 || $status == 8){ ?>
                                 <a href="#tambahptk" data-toggle="modal" class="pull-right" title="Klik untuk menambah data" style="margin-top: 10px;margin-right: 10px;font-size: 1.5em;"><i class="fa fa-plus-square"></i></a>
                              <?php } ?>
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
                                          <td align="center"><?php echo $p['tahun'] ?></td>
                                          <td align="center"><?php echo $p['bulan'] ?></td>
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
                              <?php if($status == '' || $status == 2 || $status == 5 || $status == 8){ ?>
                                 <a href="#tambahberkas" data-toggle="modal" class="pull-right" title="Klik untuk menambah data" style="margin-top: 10px;margin-right: 10px;font-size: 1.5em;"><i class="fa fa-plus-square"></i></a>
                              <?php } ?>
                              <a data-toggle="collapse" data-parent="#accordion3" href="#collapse7" <?php if($buka != 'berkas'){?> class="collapsed" <?php } ?>>Berkas Pendukung</a>
                           </h4>
                        </div>
                        <div id="collapse7" class="panel-collapse collapse <?php if($buka == 'berkas'){echo "in";} ?>">
                           <div class="panel-body">
                              <p>
                                 <?php if(count($berkas) > 0){ ?>
                                    <?php foreach ($berkas as $b) {?>
                                       <i><?php echo $b['deskripsi'] ?></i>&nbsp;&nbsp;<a href="<?php echo base_url('/assets/file/'.$b['berkas']) ?>" download title="Klik untuk mengunduh berkas"><i class="fa fa-download"></i></a><br>
                                    <?php } ?>
                                 <?php }else{ ?>
                                    <b><i>Tidak Ada Berkas Lampiran</i></b>
                                 <?php } ?>
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('operator/part_script') ?>
</body>
<div class="modal" id="buatrekap" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Buat Rekap Data</h4>
         </div>
         <form method="post" action="<?php echo base_url('o/laporan/simpan') ?>">
            <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
            <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
            <input type="hidden" name="sekolah" value="<?php echo $sekolah ?>">
            <input type="hidden" name="relasi" value="<?php echo $relasi ?>">
            <input type="hidden" name="pengguna" value="<?php echo session()->get('low') ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <div class="row">
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">NIS</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nomor Induk Sekolah" name="nis" maxlength="10" minlength="10" style="color: black;">
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Yayasan</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Yayasan Lembaga" name="yayasan" maxlength="63" style="color: black;">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Status Akreditasi</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Status Akreditasi" name="stakred" maxlength="1" minlength="1" style="color: black;">
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Tanggal Akreditasi</label>
                     <input type="date" class="form-control input-sm" id="exampleInputEmail1" name="tglakred" value="<?php echo date('Y-m-d') ?>" style="color: black;">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Telepon</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nomor Telepon" name="telepon" maxlength="14" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Email</label>
                     <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Alamat Email" name="email" maxlength="99" style="color: black;" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Alamat</label>
                  <textarea class="form-control input-sm" name="alamat" placeholder="Alamat Lengkap" rows="4" style="resize: none;color: black;" required></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
            </div>
         </form>
      </div>
   </div>
</div>
<?php
if($status == '' || $status == 2 || $status == 5 || $status == 8){
   $ds = $db->query("select * from sekolah where kodesekolah = '".$sekolah."'")->getRowArray();
   $dr = $db->query("select * from rekap where koderekap = '".$rekap."'")->getRowArray();
   ?>
   <div class="modal" id="editrekap" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Ubah Rekap Data</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/laporan/ubah') ?>">
               <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
               <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
               <input type="hidden" name="sekolah" value="<?php echo $sekolah ?>">
               <input type="hidden" name="rekap" value="<?php echo $dr['koderekap'] ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahan data. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <div class="row">
                     <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">NIS</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nomor Induk Sekolah" name="nis" maxlength="10" minlength="10" value="<?php echo $ds['nis'] ?>" style="color: black;">
                     </div>
                     <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">Yayasan</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Yayasan Lembaga" name="yayasan" maxlength="63" value="<?php echo $ds['yayasan'] ?>" style="color: black;">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">Status Akreditasi</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Status Akreditasi" name="stakred" maxlength="1" minlength="1" value="<?php echo $ds['stakred'] ?>" style="color: black;">
                     </div>
                     <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">Tanggal Akreditasi</label>
                        <input type="date" class="form-control input-sm" id="exampleInputEmail1" name="tglakred" <?php if($ds['stakred'] == ''){ ?> value="<?php echo date('Y-m-d') ?>" <?php }else{ ?> value="<?php echo date('Y-m-d', strtotime($ds['tglakred'])) ?>" <?php } ?> style="color: black;">
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">Telepon</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nomor Telepon" name="telepon" maxlength="14" value="<?php echo $dr['telepon'] ?>" style="color: black;" required>
                     </div>
                     <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control input-sm" id="exampleInputEmail1" placeholder="Alamat Email" name="email" maxlength="99" value="<?php echo $dr['email'] ?>" style="color: black;" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Alamat</label>
                     <textarea class="form-control input-sm" name="alamat" placeholder="Alamat Lengkap" rows="4" style="resize: none;color: black;" required><?php echo $dr['alamat'] ?></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="modal" id="ubahumum" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Ubah Detail Keadaan Umum Lembaga</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/laporan/ubahumum') ?>">
               <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
               <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
               <input type="hidden" name="rekap" value="<?php echo $dr['koderekap'] ?>">
               <input type="hidden" name="relasi" value="<?php echo $relasi ?>">
               <input type="hidden" name="buka" value="umum">
               <input type="hidden" name="aspek" value="<?php echo htmlspecialchars(serialize($aspek)) ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahan data. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  
                  <div role="tabpanel">
                     <ul class="nav nav-tabs" role="tablist">
                        <?php
                        $x = 1;
                        foreach ($aspek as $a) {
                           ?>
                           <li role="presentation" <?php if($x == 1){ ?> class="active" <?php } ?>><a href="#tab<?php echo $x++ ?>" role="tab" data-toggle="tab"><?php echo $a['aspek'] ?></a></li>
                        <?php } ?>
                     </ul>
                     <div class="tab-content">
                        <?php
                        $x = 1;
                        foreach ($aspek as $a) {
                           ?>
                           <div role="tabpanel" class="tab-pane <?php if($x == 1){echo "active";} ?>" id="tab<?php echo $x++ ?>">
                              <div class="row">
                                 <?php $subaspek = $db->query("select * from aspek where aspek = '".$a['aspek']."' order by subaspek asc")->getResultArray();?>
                                 <?php
                                 foreach ($subaspek as $sa) {
                                    $cek = $db->query("select ifnull(count(*),0) as jumlah from skema where kodeaspek = '".$sa['kodeaspek']."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
                                    if($cek > 0){
                                       ?>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <label class="col-sm-6 control-label" style="margin-top: 5px;"><?php echo $sa['subaspek'] ?></label>
                                             <div class="col-sm-6">
                                                <?php if($sa['jenis'] == 'teks'){ ?>
                                                   <input type="text" class="form-control input-sm" placeholder="Detail <?php echo $sa['subaspek']?>" maxlength="99" name="a<?php echo $sa['kodeaspek'] ?>" style="color: black;" required>
                                                <?php }else if($sa['jenis'] == 'angka'){ ?>
                                                   <input type="number" class="form-control input-sm" placeholder="Detail <?php echo $sa['subaspek']?> (<?php echo $sa['satuan'] ?>)" maxlength="99" name="a<?php echo $sa['kodeaspek'] ?>" style="color: black;" required>
                                                <?php }else if($sa['jenis'] == 'tanggal'){ ?>
                                                   <input type="date" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" name="a<?php echo $sa['kodeaspek'] ?>" style="color: black;" required>
                                                <?php }else if($sa['jenis'] == 'deskripsi'){ ?>
                                                   <textarea class="form-control input-sm" placeholder="Detail <?php echo $sa['subaspek']?>" name="a<?php echo $sa['kodeaspek'] ?>" rows="2" style="resize: none;color: black;" required></textarea>
                                                <?php }else{
                                                   $pilihan = $db->query("select * from pilihan where kodeaspek = '".$sa['kodeaspek']."'")->getResultArray();
                                                   ?>
                                                   <select class="form-control input-sm" name="a<?php echo $sa['kodeaspek'] ?>" style="color: black;" required>
                                                      <?php foreach ($pilihan as $pl) {?>
                                                         <option><?php echo $pl['pilihan'] ?></option>
                                                      <?php } ?>
                                                   </select>
                                                <?php } ?>
                                             </div>
                                             <br>
                                          </div>
                                       </div>
                                    <?php } ?>
                                 <?php } ?>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="modal" id="ubahsiswa" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Ubah Detail Keadaan Anak Didik</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/laporan/ubahsiswa') ?>">
               <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
               <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
               <input type="hidden" name="rekap" value="<?php echo $dr['koderekap'] ?>">
               <input type="hidden" name="buka" value="siswa">
               <input type="hidden" name="rombel" value="<?php echo htmlspecialchars(serialize($rombel)) ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahan data. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <?php foreach ($rombel as $r) {?>
                     <div class="row">
                        <div class="col-md-12">
                           <h5>Rombel <?php echo $r['rombel'] ?></h5>
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-6 control-label" style="margin-top: 5px;">Laki-Laki (Awal / Masuk / Keluar)</label>
                                 <div class="col-sm-2">
                                    <input type="number" name="l1<?php echo $r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="number" name="l2<?php echo $r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="number" name="l3<?php echo $r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                 </div>
                              </div>
                              <br>
                           </div>
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-6 control-label" style="margin-top: 5px;">Perempuan (Awal / Masuk / Keluar)</label>
                                 <div class="col-sm-2">
                                    <input type="number" name="p1<?php echo $r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="number" name="p2<?php echo $r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="number" name="p3<?php echo $r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                 </div>
                              </div>
                              <br>
                           </div>
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-6 control-label" style="margin-top: 5px;">Persentase Absensi (%)</label>
                                 <div class="col-sm-6">
                                    <input type="number" name="abs<?php echo $r['koderombel'] ?>" class="form-control input-sm" min="0" max="100" value="0" placeholder="Absesnsi" required>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php } ?>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="modal" id="ubahagama" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Ubah Detail Keadaan Agama Anak Didik</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/laporan/ubahagama') ?>">
               <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
               <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
               <input type="hidden" name="rekap" value="<?php echo $dr['koderekap'] ?>">
               <input type="hidden" name="buka" value="agama">
               <input type="hidden" name="rombel" value="<?php echo htmlspecialchars(serialize($rombel)) ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahan data. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <?php foreach ($rombel as $r) {?>
                     <div class="row">
                        <div class="col-md-12">
                           <h5>Rombel <?php echo $r['rombel'] ?></h5>
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label" style="margin-top: 5px;"></label>
                                 <?php for ($i=0; $i < 5; $i++) {?>
                                    <label class="col-sm-2 control-label" style="margin-top: 5px;"><?php echo $daftaragama[$i] ?></label>
                                 <?php } ?>
                              </div>
                              <br>
                           </div>
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label" style="margin-top: 5px;">Laki-Laki</label>
                                 <?php
                                 for ($i=0; $i < 5; $i++) {
                                    $x = strtolower($daftaragama[$i]);
                                    ?>
                                    <div class="col-sm-2">
                                       <input type="number" name="l<?php echo $x.$r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                    </div>
                                 <?php } ?>
                              </div>
                              <br>
                           </div>
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label" style="margin-top: 5px;">Perempuan</label>
                                 <?php
                                 for ($i=0; $i < 5; $i++) {
                                    $x = strtolower($daftaragama[$i]);
                                    ?>
                                    <div class="col-sm-2">
                                       <input type="number" name="p<?php echo $x.$r['koderombel'] ?>" class="form-control input-sm" min="0" value="0" placeholder="Awal Bulan" required>
                                    </div>
                                 <?php } ?>
                              </div>
                              <br>
                           </div>
                        </div>
                     </div>
                  <?php } ?>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="modal" id="tambahptk" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Tambah Data PTK</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/laporan/tambahptk') ?>">
               <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
               <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
               <input type="hidden" name="rekap" value="<?php echo $dr['koderekap'] ?>">
               <input type="hidden" name="buka" value="ptk">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahan data. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <div role="tabpanel">
                     <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tabptk1" role="tab" data-toggle="tab">Identitas PTK</a></li>
                        <li role="presentation"><a href="#tabptk2" role="tab" data-toggle="tab">SK dan Tunjangan</a></li>
                        <li role="presentation"><a href="#tabptk3" role="tab" data-toggle="tab">Absensi</a></li>
                     </ul>
                     <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tabptk1">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">NIP</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control input-sm" placeholder="Nomor Induk Pegawai (NIP)" maxlength="18" name="nip" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Nama</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control input-sm" placeholder="Nama Lengkap" maxlength="63" name="nama" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Tpt. Lahir</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control input-sm" placeholder="Tempat Lahir" maxlength="72" name="tpt" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Tgl. Lahir</label>
                                    <div class="col-sm-8">
                                       <input type="date" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" name="tgl" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Jenis Kelamin</label>
                                    <div class="col-sm-8">
                                       <select class="form-control input-sm" name="jekel" style="color: black;" required>
                                          <option value="L">Laki - Laki</option>
                                          <option value="P">Perempuan</option>
                                       </select>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Agama</label>
                                    <div class="col-sm-8">
                                       <select class="form-control input-sm" name="agama" style="color: black;" required>
                                          <?php for ($i=0; $i < 5; $i++) {?>
                                             <option><?php echo $daftaragama[$i] ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Ijazah</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control input-sm" placeholder="Ijazah Terakhir" maxlength="27" name="ijazah" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Kelas</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control input-sm" placeholder="Mengajar Kelas" maxlength="18" name="kelas" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Jabatan</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control input-sm" placeholder="Jabatan" maxlength="18" name="jabatan" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Gol. R</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control input-sm" placeholder="Golongan" maxlength="6" name="golongan" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Bulan</label>
                                    <div class="col-sm-8">
                                       <input type="number" class="form-control input-sm" placeholder="Masa Kerja" min="0" name="masabulan" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Tahun</label>
                                    <div class="col-sm-8">
                                       <input type="number" class="form-control input-sm" placeholder="Masa Kerja" min="0" name="masatahun" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label class="col-sm-2 control-label" style="margin-top: 5px;">Keterangan</label>
                                    <div class="col-sm-10">
                                       <textarea class="form-control input-sm" placeholder="Keterangan Tambahan" name="keterangan" rows="3" style="resize: none;color: black;"></textarea>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tabptk2">
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-3 control-label" style="margin-top: 5px;">SK Pengangkatan</label>
                                 <div class="col-sm-5">
                                    <input type="text" class="form-control input-sm" placeholder="Nomor SK" maxlength="27" name="noangkat" style="color: black;">
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="date" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" name="tglangkat" style="color: black;">
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="date" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" name="tmtangkat" style="color: black;">
                                 </div>
                                 <br>
                              </div>
                           </div>
                           <div class="row">
                              <div class="form-group">
                                 <label class="col-sm-3 control-label" style="margin-top: 5px;">SK Terakhir</label>
                                 <div class="col-sm-5">
                                    <input type="text" class="form-control input-sm" placeholder="Nomor SK" maxlength="27" name="noakhir" style="color: black;">
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="date" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" name="tglakhir" style="color: black;">
                                 </div>
                                 <div class="col-sm-2">
                                    <input type="date" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" name="tmtakhir" style="color: black;">
                                 </div>
                                 <br>
                              </div>
                           </div>
                           <hr>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="col-sm-4 control-label" style="margin-top: 5px;">TPG</label>
                                 <div class="col-sm-8">
                                    <input type="number" class="form-control input-sm" placeholder="Nominal TPG" min="0" value="0" name="tpg" style="color: black;" required>
                                 </div>
                                 <br>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="col-sm-4 control-label" style="margin-top: 5px;">Insentif</label>
                                 <div class="col-sm-8">
                                    <input type="number" class="form-control input-sm" placeholder="Nominal Insentif" min="0" value="0" name="insentif" style="color: black;" required>
                                 </div>
                                 <br>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="col-sm-4 control-label" style="margin-top: 5px;">Kesra</label>
                                 <div class="col-sm-8">
                                    <input type="number" class="form-control input-sm" placeholder="Nominal Kesra" min="0" value="0" name="kesra" style="color: black;" required>
                                 </div>
                                 <br>
                              </div>
                           </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tabptk3">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Sakit</label>
                                    <div class="col-sm-8">
                                       <input type="number" class="form-control input-sm" placeholder="Absensi Sakit" min="0" value="0" name="sakit" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Ijin</label>
                                    <div class="col-sm-8">
                                       <input type="number" class="form-control input-sm" placeholder="Absensi Ijin" min="0" value="0" name="ijin" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Alfa</label>
                                    <div class="col-sm-8">
                                       <input type="number" class="form-control input-sm" placeholder="Absensi Alfa" min="0" value="0" name="alfa" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label" style="margin-top: 5px;">Perjalan Dinas</label>
                                    <div class="col-sm-8">
                                       <input type="number" class="form-control input-sm" placeholder="Absensi Perjalan Dinas" min="0" value="0" name="dinas" style="color: black;" required>
                                    </div>
                                    <br>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="modal" id="tambahberkas" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Tambah Berkas Lampiran</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/laporan/tambahberkas') ?>" enctype="multipart/form-data">
               <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
               <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
               <input type="hidden" name="rekap" value="<?php echo $dr['koderekap'] ?>">
               <input type="hidden" name="buka" value="berkas">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Berkas</label>
                     <input type="file" class="form-control input-sm" id="exampleInputEmail1" name="berkas" accept="application/pdf,application/vnd.ms-excel,zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" style="color: black;" required>
                     <small>file dengan format *.zip, *.rar, atau *.pdf (ukuran bebas)</small>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Deskripsi</label>
                     <textarea class="form-control input-sm" name="deskripsi" placeholder="Deskripsi Berkas" rows="4" style="resize: none;color: black;" required></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="modal" id="kirimrekap" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Kirim Laporan Bulanan</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/laporan/kirim') ?>">
               <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
               <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
               <input type="hidden" name="rekap" value="<?php echo $dr['koderekap'] ?>">
               <input type="hidden" name="pengguna" value="<?php echo session()->get('low') ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">
                     <code>PENTING!!!</code> Pastikan semua detail laporan bulanan sudah diteliti dan diisi sesuai dengan kebutuhan, lalu pilih tombol <code>Kirim Data</code> untuk mengirimkan data dan dilakukan verifikasi. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Kirim Data</button>
               </div>
            </form>
         </div>
      </div>
   </div>
<?php } ?>
</html>