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
               <h2>Halaman Pengolah Data Pengguna Sistem</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Pengguna Sistem </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('s/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('s/pengguna') ?>">Pengguna Sistem</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Detail Pengguna Sistem</h2>
                  <div class="clearfix"></div>
               </div>
               <form method="post" action="<?php echo base_url('s/pengguna/ubah') ?>">
                  <div class="c_content">
                     <div class="form-group">
                        <label for="exampleInputEmail1">Nama Pengguna</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Pengguna" name="nama" maxlength="63" value="<?php echo $data['nama'] ?>" style="color: black;" required>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Telepon</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nomor Telepon" name="telepon" maxlength="14" minlength="10" value="<?php echo $data['telepon'] ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="color: black;" />
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Alamat Pengguna</label>
                        <textarea class="form-control input-sm" placeholder="Alamat Lengkap Pengguna" name="alamat" rows="4" style="resize: none;color: black;" required><?php echo $data['alamat'] ?></textarea>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Username Pengguna</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Username Pengguna" name="username" maxlength="99" value="<?php echo $data['username'] ?>" style="color: black;" required>
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