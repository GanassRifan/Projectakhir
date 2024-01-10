<?php
$db = db_connect();
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
               <h2>Halaman Pengolah Data Aspek Laporan</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Aspek Laporan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('a/') ?>">Beranda</a></li>
                        <li><a href="<?php echo base_url('a/aspek') ?>">Data Aspek Laporan</a></li>
                        <li class="active"><a href="#">Detail Pilihan Aspek</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Data Aspek Laporan</h2>
                  <ul class="nav navbar-right panel_options">
                     <a href="#tambah" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus-square"></i> Tambah Pilihan Baru</a>
                  </ul>
                  <div class="clearfix"></div>
               </div>
               <div class="c_content">
                  <p style="text-align: justify;">
                     Aspek : <?php echo $aspek['aspek'] ?><br>
                     Subaspek : <?php echo $aspek['subaspek'] ?>
                  </p>
               </div>
               <div class="c_content">
                  <h5><strong>Detail Pilihan</strong></h5>
                  <table class="table table-responsive">
                     <thead>
                        <tr>
                           <th width="5%">No.</th>
                           <th>Pilihan</th>
                           <th width="5%">**</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        $n = 1;
                        for ($i=0; $i < count($pilihan); $i++) {
                           ?>
                           <tr>
                              <td align="center"><?php echo $n++ ?></td>
                              <td><?php echo $pilihan[$i] ?></td>
                              <td><a href="<?php echo base_url('a/aspek/hapusdetail/'.htmlspecialchars(serialize($aspek)).'/'.htmlspecialchars(serialize($pilihan)).'/'.$i) ?>" title="Klik untuk menghapus data"><i class="fa fa-times" style="font-size: 1.5em;"></i></a></td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <?php if(count($pilihan) > 0){ ?>
                  <form action="<?php echo base_url('a/aspek/simpanpilihan') ?>" method="post">
                     <input type="hidden" name="aspek" value="<?php echo htmlspecialchars(serialize($aspek)) ?>">
                     <input type="hidden" name="pilihan" value="<?php echo htmlspecialchars(serialize($pilihan)) ?>">
                     <div class="c_content">
                        <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm" style="float: right;">Simpan Perubahan Data</button>
                     </div>
                  </form>
               <?php } ?>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('admin/part_script') ?>
</body>
<div class="modal" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Tambah Pilihan Baru</h4>
         </div>
         <form method="post" action="<?php echo base_url('a/aspek/tambahdetail') ?>">
            <input type="hidden" name="aspek" value="<?php echo htmlspecialchars(serialize($aspek)) ?>">
            <input type="hidden" name="pilihan" value="<?php echo htmlspecialchars(serialize($pilihan)) ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <div class="form-group">
                  <label for="exampleInputEmail1">Pilihan</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Pilihan" name="isian" maxlength="36" style="color: black;" required>
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
</html>