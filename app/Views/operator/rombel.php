<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$daftartahun = date('Y');
$cek = $db->query("select * from sekolah")->getResultArray();
if(count($cek) > 0){
   $daftartahun = $db->query("select year(berdiri) as tahun from sekolah where kodesekolah = '".$sekolah."'")->getRowArray()['tahun'];
}
$relasi = "";
$data = [];
$cek = $db->query("select * from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getResultArray();
if(count($cek) > 0){
   $relasi = $db->query("select * from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
   $data = $db->query("select * from rombel where kodesekolah = '".$sekolah."' and koderelasi = '".$relasi."'")->getResultArray();
}
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
               <h2>Halaman Pengolah Rombongan Belajar</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Rombongan Belajar </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('o/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('o/rombel') ?>">Data Rombongan Belajar</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Data Rombongan Belajar</h2>
                  <ul class="nav navbar-right panel_options">
                     <div class="row">
                        <div class="col-lg-12">
                           <form method="post" action="<?php echo base_url('o/rombel/tampil') ?>">
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
                              <div class="col-sm-2">
                                 <a href="#tambah" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus-square"></i> Tambah Data</a>
                              </div>
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
                           <th>Rombel</th>
                           <th>Jml. Kelas</th>
                           <th width="5%">**</th>
                        </tr>
                     </thead>
                     <tbody style="color: black;">
                        <?php
                        $n = 1;
                        foreach ($data as $d) {
                           $cek = $db->query("select ifnull(count(*),0) as jumlah from kdagama where koderombel = '".$d['koderombel']."'")->getRowArray()['jumlah'];
                           if($cek == 0){
                              $cek = $db->query("select ifnull(count(*),0) as jumlah from kdsiswa where koderombel = '".$d['koderombel']."'")->getRowArray()['jumlah'];
                           }
                           ?>
                           <tr>
                              <td><?php echo $n++ ?></td>
                              <td><?php echo $d['rombel'] ?></td>
                              <td><?php echo number_format($d['jumlah']) ?></td>
                              <td>
                                 <?php if($cek == 0){ ?>
                                    <a href="#detail<?php echo $d['koderombel'] ?>" data-toggle="modal" title="Klik untuk mengubah data"><i class="fa fa-edit" style="font-size: 1.5em;"></i></a>
                                    <a href="<?php echo base_url('o/rombel/hapus/'.$d['koderombel']) ?>" title="Klik untuk menghapus data"><i class="fa fa-times" style="font-size: 1.5em;"></i></a>
                                 <?php } ?>
                              </td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('operator/part_script') ?>
</body>
<div class="modal" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Tambah Data Baru</h4>
         </div>
         <form method="post" action="<?php echo base_url('o/rombel/simpan') ?>">
            <input type="hidden" name="bulan" value="<?php echo (int)$bulan ?>">
            <input type="hidden" name="tahun" value="<?php echo $tahun ?>">
            <input type="hidden" name="sekolah" value="<?php echo $sekolah ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <div class="row">
                  <div class="form-group col-sm-8">
                     <label for="exampleInputEmail1">Rombongan Belajar</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Rombongan Belajar" name="rombel" maxlength="18" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-4">
                     <label for="exampleInputEmail1">Jumlah Kelas</label>
                     <input type="number" class="form-control input-sm" id="exampleInputEmail1" placeholder="Jumlah Kelas Rombel" name="jumlah" min="1" style="color: black;" required>
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
<?php foreach ($data as $d) {?>
   <div class="modal" id="detail<?php echo $d['koderombel'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Ubah Detail Data</h4>
            </div>
            <form method="post" action="<?php echo base_url('o/rombel/ubah') ?>">
               <input type="hidden" name="kode" value="<?php echo $d['koderombel'] ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <div class="row">
                     <div class="form-group col-sm-8">
                        <label for="exampleInputEmail1">Rombongan Belajar</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Rombongan Belajar" name="rombel" maxlength="18" value="<?php echo $d['rombel'] ?>" style="color: black;" required>
                     </div>
                     <div class="form-group col-sm-4">
                        <label for="exampleInputEmail1">Jumlah Kelas</label>
                        <input type="number" class="form-control input-sm" id="exampleInputEmail1" placeholder="Jumlah Kelas Rombel" name="jumlah" min="1" value="<?php echo $d['jumlah'] ?>" style="color: black;" required>
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
<?php } ?>
</html>