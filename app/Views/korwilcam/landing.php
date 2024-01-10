<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$bulan = date('m');
$tahun = date('Y');
$kecamatan = $db->query("select kecamatan from pengguna where kodepengguna = '".session()->get('high')."'")->getRowArray()['kecamatan'];
$lembaga = $db->query("select * from sekolah where kecamatan = '".$kecamatan."'")->getResultArray();
$lembaga1 = $db->query("select ifnull(count(*),0) as jumlah from sekolah where kecamatan = '".$kecamatan."'")->getRowArray()['jumlah'];
$laporan1 = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
if($laporan1 > 0){
   $laporan1 = $db->query("select koderelasi from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
   $laporan1 = $db->query("select ifnull(count(*),0) as jumlah from rekap join sekolah on rekap.kodesekolah = sekolah.kodesekolah where rekap.status = 'x' and rekap.koderelasi = '".$laporan1."' and sekolah.kecamatan = '".$kecamatan."'")->getRowArray()['jumlah'];
   if($laporan1 > 0 && $lembaga1 > 0){
      $laporan1 = $laporan1/$lembaga1 * 100;
   }
}else{
   $laporan1 = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php echo view('korwilcam/part_head') ?>
<body id="default-scheme">
   <section id="container">
      <?php echo view('korwilcam/part_header') ?>
      <?php echo view('korwilcam/part_sidebar') ?>
      <section id="main-content">
         <section class="wrapper">
            <div class="top-page-header">
               <h2>Status Data <?php echo $daftarbulan[(int)$bulan]." ".$tahun ?></h2>
               <small>Halaman ini menampilkan detail statistik data sistem pada periode aktif </small>
            </div>
            <div class="row">
               <div class="col-md-12 widgets-page">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="widget">
                           <div class="widget-content bg-white">
                              <div class="row padding-10">
                                 <div class="col-xs-6">
                                    <h3 class="counter font-bold font-size-38"><?php echo number_format($lembaga1) ?></h3>
                                 </div>
                                 <div class="col-xs-6">
                                    <p class="font-size-38"><span class="icon-graduation pull-right"></span></p>
                                 </div>
                              </div>
                              <p class="margin-left-10 margin-right-10">Lembaga PAUDNI Aktif</p>
                              <a href="#detaillembaga" data-toggle="modal" class="padding-8 hvr-bounce-to-right bg-info" style="width:100%;">Detail Data</a>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="widget">
                           <div class="widget-content bg-white">
                              <div class="row padding-10">
                                 <div class="col-xs-6">
                                    <h3 class="font-bold font-size-38"><?php echo number_format($laporan1)." %" ?></h3>
                                 </div>
                                 <div class="col-xs-6">
                                    <p class="font-size-38"><span class="icon-notebook pull-right"></span></p>
                                 </div>
                              </div>
                              <p class="margin-left-10 margin-right-10">Progress Laporan Bulanan</p>
                              <a href="#detailprogress" data-toggle="modal" class="padding-8 hvr-bounce-to-right bg-warning" style="width:100%;">Detail Data</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </section>
   </section>
   <div class="modal" id="detaillembaga" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Lembaga PAUDNI Aktif</h4>
            </div>
            <div class="modal-body">
               <p style="text-align: justify;">Detail Lembaga PAUDNI aktif di wilayah Kecamatan <?php echo $kecamatan ?></p>
               <table id="example2" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                  <thead>
                     <tr>
                        <th>No.</th>
                        <th>Sekolah</th>
                        <th>Th. Berdiri</th>
                        <th>Kepala Sekolah</th>
                        <th>Operator</th>
                     </tr>
                  </thead>
                  <tbody style="color: black;">
                     <?php
                     $n = 1;
                     foreach ($lembaga as $d) {
                        $ceks = $db->query("select count(*) as jumlah from rekap where kodesekolah = '".$d['kodesekolah']."'")->getRowArray()['jumlah'];
                        $kepsek = "";
                        $operator = "";
                        $cek = $db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getResultArray();
                        if(count($cek) > 0){
                           $kepsek = $db->query("select nama from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
                           $operator = $db->query("select nama from pengguna where level = 'low' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
                        }
                        ?>
                        <tr>
                           <td align="center"><?php echo $n++ ?></td>
                           <td><?php echo $d['nama'] ?></td>
                           <td align="center"><?php echo date('Y', strtotime($d['berdiri'])) ?></td>
                           <td><?php echo $kepsek ?></td>
                           <td><?php echo $operator ?></td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="modal" id="detailprogress" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Progress Laporan Bulanan</h4>
            </div>
            <div class="modal-body">
               <p style="text-align: justify;">Detail Progress Laporan Bulanan Lembaga PAUDNI di wilayah Kecamatan <?php echo $kecamatan ?></p>
               <table id="example1" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                  <thead>
                     <tr>
                        <th>No.</th>
                        <th>Sekolah</th>
                        <th>Operator</th>
                        <th>Status</th>
                        <th>Aktifitas Terakhir</th>
                     </tr>
                  </thead>
                  <tbody style="color: black;">
                     <?php
                     $n = 1;
                     $relasi = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
                     if($relasi > 0){
                        $relasi = $db->query("select koderelasi from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
                     }
                     foreach ($lembaga as $d) {
                        $status = "Belum";
                        $waktu = "";
                        $operator = "";
                        $cek = $db->query("select * from pengguna where level = 'low' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getResultArray();
                        if(count($cek) > 0){
                           $operator = $db->query("select nama from pengguna where level = 'low' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
                        }
                        if($relasi > 0){
                           $cek = $db->query("select ifnull(count(*),0) as jumlah from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
                           if($cek > 0){
                              $x = $db->query("select * from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray();
                              if($x['status'] == 'x'){
                                 $status = "Selesai";
                              }else if($x['status'] >= 6 ){
                                 $status = "Verifikasi Pusat";
                              }else if($x['status'] >= 3 ){
                                 $status = "Verifikasi Korwilcam";
                              }else{
                                 $status = "Proses Lembaga";
                              }
                              $waktu = $db->query("select waktu from verifikasi where koderekap = '".$x['koderekap']."' order by waktu desc")->getRowArray()['waktu'];
                              $waktu = date('d/m/Y H:i:s', strtotime($waktu));
                           }
                        }
                        ?>
                        <tr>
                           <td align="center"><?php echo $n++ ?></td>
                           <td><?php echo $d['nama'] ?></td>
                           <td><?php echo $operator ?></td>
                           <td><?php echo $status ?></td>
                           <td><?php echo $waktu ?></td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <?php echo view('korwilcam/part_script') ?>
</body>
</html>