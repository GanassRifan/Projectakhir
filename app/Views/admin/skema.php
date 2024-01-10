<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$daftartahun = date('Y');
$cek = $db->query("select * from sekolah")->getResultArray();
if(count($cek) > 0){
   $daftartahun = $db->query("select year(berdiri) as tahun from sekolah order by berdiri asc")->getRowArray()['tahun'];
}
$rekap = "";
$relasi = "";
$kunci = "";
$aspeklaporan = [];
$cek = $db->query("select * from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getResultArray();
if(count($cek) > 0){
   $relasi = $db->query("select * from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
   $rekap = $db->query("select ifnull(count(*),0) as jumlah from rekap where koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
   $kunci = $db->query("select * from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['status'];
   $aspeklaporan = $db->query("select * from skema where koderelasi = '".$relasi."'")->getResultArray();
}
$isian = 0;
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
               <h2>Halaman Pengolah Skema Aspek Laporan</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Skema Aspek Laporan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('a/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('a/skema') ?>">Skema Aspek Laporan</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Skema Aspek Laporan</h2>
                  <ul class="nav navbar-right panel_options">
                     <div class="row">
                        <div class="col-lg-12">
                           <form method="post" action="<?php echo base_url('a/skema/tampil') ?>">
                              <div class="col-sm-4">
                                 <select class="form-control input-sm" name="bulan" style="color: black;" required onchange="this.form.submit()">
                                    <?php for ($i=1; $i <= count($daftarbulan) ; $i++) {?>
                                       <option <?php if($bulan == $i){echo "selected";} ?> value="<?php echo $i ?>"><?php echo $daftarbulan[$i] ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                              <div class="col-sm-4">
                                 <select class="form-control input-sm" name="tahun" style="color: black;" required onchange="this.form.submit()">
                                    <?php for ($i=date('Y'); $i >= $daftartahun ; $i--) {?>
                                       <option <?php if($tahun == $i){echo "selected";} ?>><?php echo $i ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                              <?php if(($kunci == '0' || $kunci == '') && ($rekap == '' || $rekap == '0')){ ?>
                                 <div class="col-sm-2">
                                    <a href="#tambah" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus-square"></i> Ubah Skema</a>
                                 </div>
                              <?php }else if($kunci == '1' || $kunci == '2'){ ?>
                                 <div class="col-sm-2">
                                    <a href="#kunci" data-toggle="modal" class="btn btn-danger btn-sm"><i class="fa fa-plus-square"></i> Kunci Inputan</a>
                                 </div>
                              <?php }else{ ?>
                                 <div class="col-sm-2">
                                    <a href="#buka" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus-square"></i> Buka Inputan</a>
                                 </div>
                              <?php } ?>
                           </form>
                        </div>
                     </div>
                  </ul>
                  <div class="clearfix"></div>
               </div>
               <div class="c_content">
                  <table id="example" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                     <thead>
                        <tr>
                           <th width="5%">No.</th>
                           <th width="27%">Aspek</th>
                           <th>Subaspek</th>
                        </tr>
                     </thead>
                     <tbody style="color: black;">
                        <?php
                        $n = 1;
                        foreach ($aspeklaporan as $d) {
                           $a = $db->query("select * from aspek where kodeaspek = '".$d['kodeaspek']."'")->getRowArray();
                           ?>
                           <tr>
                              <td align="center"><?php echo $n++ ?></td>
                              <td><?php echo $a['aspek'] ?></td>
                              <td><?php echo $a['subaspek'] ?></td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('admin/part_script') ?>
</body>
<div class="modal" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Ubah Detail Skema Aspek Laporan</h4>
         </div>
         <form method="post" action="<?php echo base_url('a/skema/simpan') ?>">
            <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
            <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <div class="row">
                  <?php
                  foreach ($aspek as $a) {
                     $cek = $db->query("select count(*) as jumlah from skema where kodeaspek = '".$a['kodeaspek']."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
                     if($cek > 0){
                        $isian++;
                     }
                     ?>
                     <div class="col-sm-4" style="margin-bottom: 10px;">
                        <label class="checkbox-inline">
                           <input type="checkbox" id="inlineCheckbox1" name="aspek[]" value="<?php echo $a['kodeaspek'] ?>" <?php if($cek > 0){echo "checked";} ?>> <?php echo $a['subaspek'] ?>
                        </label>
                     </div>
                  <?php } ?>
               </div>
               <?php if($relasi != '' && $isian > 0){ ?>
                  <hr>
                  <p style="text-align: justify;">Pastikan semua inputan aspek yang dibutuhkan pada periode <?php echo $daftarbulan[$bulan]." ".$tahun ?> sudah sesuai. Lalu pilih tombol <code>Kunci Skema</code> untuk mengunci skema data dan membuka akses inputan laporan bulanan lembaga PAUDNI maupun verifikasi oleh Korwilcam </p>
               <?php } ?>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
               <?php if($relasi != '' && $isian > 0){ ?>
                  <a href="<?php echo base_url('a/skema/buka/'.$relasi) ?>" class="btn btn-success btn-raised rippler rippler-default btn-sm">Kunci Skema</a>
               <?php } ?>
               <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Simpan Data</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div class="modal" id="kunci" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Kunci Skema Data</h4>
         </div>
         <div class="modal-body">
            <p style="text-align: justify;">
               <?php if($kunci == '1'){ ?>
                  <code>KUNCI VERIFIKASI DATA BELUM AKTIF!</code><br>
               <?php }else{ ?>
                  <code>KUNCI VERIFIKASI DATA AKTIF!</code><br>
               <?php } ?>
               Fitur ini digunakan untuk mengunci data skema pada periode <?php echo $daftarbulan[$bulan].' '.$tahun ?>. Terdapat beberapa status penguncian data sebagai berikut : <br>
               <br><strong>Kunci Verifikasi</strong><br>
               Fitur ini akan mengunci inputan data laporan sementara (bisa diubah atau dibuka kembali), akan menutup akses inputan laporan bulanan lembaga PAUDNI maupun verifikasi oleh Korwilcam dan akan memfungsikan akses administrator melakukan verifikasi laporan bulanan yang masuk
               <br><br><strong>Kunci Inputan</strong><br>
               Fitur ini akan mengunci inputan data laporan selamanya (tidak bisa diubah atau dibuka kembali), akan menutup akses inputan laporan bulanan lembaga PAUDNI, verifikasi oleh Korwilcam, maupun akses administrator melakukan verifikasi laporan bulanan yang masuk
            </p>
         </div>
         <div class="modal-footer">
            <a href="<?php echo base_url('a/skema/verifikasi/'.$relasi) ?>" class="btn btn-warning btn-raised rippler rippler-default btn-sm">Kunci Verifikasi</a>
            <a href="<?php echo base_url('a/skema/kunci/'.$relasi) ?>" class="btn btn-danger btn-raised rippler rippler-default btn-sm">Kunci Inputan</a>
         </div>
      </div>
   </div>
</div>
<div class="modal" id="buka" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Buka Inputan Data</h4>
         </div>
         <form action="<?php echo base_url('a/skema/bukakunci') ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $relasi ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan PIN Sistem, lalu pilih tombol <code>Buka Kunci</code> untuk membuka akses inputan laporan bulanan lembaga PAUDNI</p>
               <div class="form-group">
                  <label for="exampleInputEmail1">PIN Sistem</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Masukkan PIN Sistem" name="pin" style="color: black;" required>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Buka Kunci</button>
            </div>
         </form>
      </div>
   </div>
</div>
</html>