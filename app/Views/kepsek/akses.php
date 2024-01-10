<?php
$db = db_connect();
$daftaraspek = $db->query("select aspek from aspek group by aspek asc")->getResultArray();
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
               <h2>Halaman Pengolah Data Akses Pengguna Sistem</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Akses Pengguna Sistem </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('s/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('s/akses') ?>">Akses Pengguna Sistem</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Detail Akses Pengguna Sistem</h2>
                  <div class="clearfix"></div>
               </div>
               <form method="post" action="<?php echo base_url('s/akses/ubah') ?>">
                  <div class="c_content">
                     <div class="form-group">
                        <label for="exampleInputEmail1">Password Lama</label>
                        <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password Lama (Sekarang)" name="p1" style="color: black;" required>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Password Baru</label>
                        <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password Baru" name="p2" style="color: black;" required>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Password Baru (Ulangi)</label>
                        <input type="password" class="form-control input-sm" id="exampleInputEmail1" placeholder="Password Baru (Ulangi)" name="p3" style="color: black;" required>
                     </div>
                  </div>
                  <div class="c_content">
                     <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm" style="float: right;">Simpan Perubahan Data</button>
                  </div>
               </form>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('kepsek/part_script') ?>
</body>
</html>