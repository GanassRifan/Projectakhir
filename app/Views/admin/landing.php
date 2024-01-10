<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$bulan = date('m');
$tahun = date('Y');
$kecamatan = ['Bojong','Buaran','Doro','Kajen','Kandangserang','Karanganyar','Karangdadap','Kedungwuni','Kesesi','Lebakbarang','Paninggaran','Petungkriyono','Siwalan','Sragi','Talun','Tirto','Wiradesa','Wonokerto','Wonopringgo'];
$lembaga = $db->query("select * from sekolah")->getResultArray();
$lembaga1 = $db->query("select ifnull(count(*),0) as jumlah from sekolah")->getRowArray()['jumlah'];
$laporan1 = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
if($laporan1 > 0){
   $laporan1 = $db->query("select koderelasi from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
   $laporan1 = $db->query("select ifnull(count(*),0) as jumlah from rekap where koderelasi = '".$laporan1."' and status = 'x'")->getRowArray()['jumlah'];
   if($laporan1 > 0 && $lembaga1 > 0){
      $laporan1 = $laporan1/$lembaga1 * 100;
   }
}else{
   $laporan1 = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php echo view('admin/part_head') ?>
<body id="default-scheme">
   <section id="container">
      <?php echo view('admin/part_header') ?>
      <?php echo view('admin/part_sidebar') ?>
      <section id="main-content">
         <section class="wrapper">
            <div class="top-page-header">
               <h2>Status Data <?php echo $daftarbulan[(int)$bulan]." ".$tahun ?></h2>
               <small>Halaman ini menampilkan detail statistik data sistem pada periode aktif </small>
            </div>
            <div class="row">
               <div class="col-md-12 widgets-page">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="widget">
                           <div class="widget-content bg-white">
                              <div class="row padding-10">
                                 <div class="col-xs-6">
                                    <h3 class="counter font-bold font-size-38"><?php echo number_format(count($kecamatan)) ?></h3>
                                 </div>
                                 <div class="col-xs-6">
                                    <p class="font-size-38"><span class="icon-directions pull-right"></span></p>
                                 </div>
                              </div>
                              <p class="margin-left-10 margin-right-10">Daftar Kecamatan</p>
                              <a href="#detailkecamatan" data-toggle="modal" class="padding-8 hvr-bounce-to-right bg-success" style="width:100%;">Detail Data</a>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4">
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
                     <div class="col-md-4">
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
   <div class="modal" id="detailkecamatan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Daftar Kecamatan</h4>
            </div>
            <div class="modal-body">
               <p style="text-align: justify;">Detail Lembaga PAUDNI wilayah Kecamatan di Kabupaten Pekalongan</p>
               <table id="example" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                  <thead>
                     <tr>
                        <th width="5%">No.</th>
                        <th>Kecamatan</th>
                        <th>Aktif</th>
                        <th>Nonaktif</th>
                        <th>Total</th>
                     </tr>
                  </thead>
                  <tbody style="color: black;">
                     <?php
                     $n = 1;
                     for ($i=0; $i < count($kecamatan); $i++) {
                        $j1 = 0;
                        $j2 = 0;
                        $data = $db->query("select * from sekolah where kecamatan = '".$kecamatan[$i]."'")->getResultArray();
                        foreach ($data as $d) {
                           $cek = $db->query("select ifnull(count(*),0) as jumlah from pengguna where level = 'mid' and status = 'Aktif' and kodesekolah = '".$d['kodesekolah']."'")->getRowArray()['jumlah'];
                           if($cek == 0){
                              $j2++;
                           }else{
                              $j1++;
                           }
                        }
                        ?>
                        <tr>
                           <td align="center"><?php echo $n++ ?></td>
                           <td><?php echo $kecamatan[$i] ?></td>
                           <td><?php echo number_format($j1) ?></td>
                           <td><?php echo number_format($j2) ?></td>
                           <td><?php echo number_format($j1 + $j2) ?></td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="modal" id="detaillembaga" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Lembaga PAUDNI Aktif</h4>
            </div>
            <div class="modal-body">
               <p style="text-align: justify;">Detail Lembaga PAUDNI aktif di wilayah Kabupaten Pekalongan</p>
               <table id="example2" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                  <thead>
                     <tr>
                        <th>No.</th>
                        <th>Sekolah</th>
                        <th>Kecamatan</th>
                        <th>Th. Berdiri</th>
                        <th>Kepala Sekolah</th>
                     </tr>
                  </thead>
                  <tbody style="color: black;">
                     <?php
                     $n = 1;
                     foreach ($lembaga as $d) {
                        $ceks = $db->query("select count(*) as jumlah from rekap where kodesekolah = '".$d['kodesekolah']."'")->getRowArray()['jumlah'];
                        $kepsek = "";
                        $cek = $db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getResultArray();
                        if(count($cek) > 0){
                           $kepsek = $db->query("select nama from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
                        }
                        ?>
                        <tr>
                           <td align="center"><?php echo $n++ ?></td>
                           <td><?php echo $d['nama'] ?></td>
                           <td><?php echo $d['kecamatan'] ?></td>
                           <td align="center"><?php echo date('Y', strtotime($d['berdiri'])) ?></td>
                           <td><?php echo $kepsek ?></td>
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
               <p style="text-align: justify;">Detail Progress Laporan Bulanan Lembaga PAUDNI di wilayah Kabupaten Pekalongan</p>
               <table id="example1" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                  <thead>
                     <tr>
                        <th>No.</th>
                        <th>Kecamatan</th>
                        <th>Jumlah</th>
                        <th>Proses Lembaga</th>
                        <th>Verifikasi Korwilcam</th>
                        <th>Verifikasi Pusat</th>
                        <th>Selesai</th>
                     </tr>
                  </thead>
                  <tbody style="color: black;">
                     <?php
                     $n = 1;
                     $relasi = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
                     if($relasi > 0){
                        $relasi = $db->query("select koderelasi from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
                     }
                     for ($i=0; $i < count($kecamatan); $i++) {
                        $j = $db->query("select ifnull(count(*),0) as jumlah from sekolah where kecamatan = '".$kecamatan[$i]."'")->getRowArray()['jumlah'];
                        $j1 = 0;
                        $j2 = 0;
                        $j3 = 0;
                        $j4 = 0;
                        $p1 = 0;
                        $p2 = 0;
                        $p3 = 0;
                        $p4 = 0;
                        if($relasi > 0){
                           $data = $db->query("select * from sekolah where kecamatan = '".$kecamatan[$i]."'")->getResultArray();
                           foreach ($data as $d) {
                              $cek = $db->query("select ifnull(count(*),0) as jumlah from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
                              if($cek > 0){
                                 $x = $db->query("select * from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray();
                                 if($x['status'] == 'x'){
                                    $j4++;
                                 }else if($x['status'] >= 6 ){
                                    $j3++;
                                 }else if($x['status'] >= 3 ){
                                    $j2++;
                                 }else{
                                    $j1++;
                                 }
                              }else{
                                 $j1++;
                              }
                           }
                           if($j1 > 0 && $j > 0){
                              $p1 = $j1/$j*100;
                           }
                           if($j2 > 0 && $j > 0){
                              $p2 = $j2/$j*100;
                           }
                           if($j3 > 0 && $j > 0){
                              $p3 = $j3/$j*100;
                           }
                           if($j4 > 0 && $j > 0){
                              $p4 = $j4/$j*100;
                           }
                        }
                        ?>
                        <tr>
                           <td align="center"><?php echo $n++ ?></td>
                           <td><?php echo $kecamatan[$i] ?></td>
                           <td><?php echo number_format($j) ?></td>
                           <td><?php echo number_format($j1)?> <code><?php echo " (".number_format($p1,2)."%)" ?></code></td>
                           <td><?php echo number_format($j2)?> <code><?php echo " (".number_format($p2,2)."%)" ?></code></td>
                           <td><?php echo number_format($j3)?> <code><?php echo " (".number_format($p3,2)."%)" ?></code></td>
                           <td><?php echo number_format($j4)?> <code><?php echo " (".number_format($p4,2)."%)" ?></code></td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <?php echo view('admin/part_script') ?>
</body>
</html>