<?php
$db = db_connect();
$daftaraspek = $db->query("select aspek from aspek group by aspek asc")->getResultArray();
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
               <h2>Halaman Pengolah Data Informasi Sistem</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Informasi Sistem </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('a/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('a/infosistem') ?>">Informasi Sistem</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Detail Informasi Sistem</h2>
                  <div class="clearfix"></div>
               </div>
               <form method="post" action="<?php echo base_url('a/infosistem/ubah') ?>">
                  <div class="c_content">
                     <div class="form-group">
                        <label for="exampleInputEmail1">Nama Sistem</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Sistem" name="nama" maxlength="18" value="<?php echo $data['nama'] ?>" style="color: black;" required>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Tagline</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Tagline Sistem" name="tagline" maxlength="63" value="<?php echo $data['tagline'] ?>" style="color: black;" required>
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
   <?php echo view('admin/part_script') ?>
</body>
</html>