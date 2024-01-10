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
               <h2>Halaman Pengolah Data Aspek Laporan</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Aspek Laporan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('a/') ?>">Beranda</a></li>
                        <li><a href="<?php echo base_url('a/aspek') ?>">Data Aspek Laporan</a></li>
                        <li class="active"><a href="#">Tambah Data Baru</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Data Aspek Laporan</h2>
                  <div class="clearfix"></div>
               </div>
               <form method="post" action="<?php echo base_url('a/aspek/simpan') ?>">
                  <input type="hidden" name="jenis" value="<?php echo $aspek['jenis'] ?>">
                  <div class="c_content">
                     <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="exampleInputEmail1">Aspek</label>
                           <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Aspek" name="aspek" maxlength="27" list="daftaraspek" value="<?php echo $aspek['aspek'] ?>" style="color: black;" required>
                           <datalist id="daftaraspek">
                              <?php foreach ($daftaraspek as $a) {?>
                                 <option><?php echo $a['aspek'] ?></option>
                              <?php } ?>
                           </datalist>
                        </div>
                        <div class="form-group col-sm-5">
                           <label for="exampleInputEmail1">Subaspek</label>
                           <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Subaspek" name="subaspek" maxlength="45" value="<?php echo $aspek['subaspek'] ?>" style="color: black;" required>
                        </div>
                        <div class="form-group col-sm-3">
                           <label for="exampleInputEmail1">Satuan</label>
                           <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Satuan Inputan" name="satuan" maxlength="9" style="color: black;" <?php if($aspek['jenis'] == 'angka'){ ?> required <?php } ?>>
                        </div>
                     </div>
                  </div>
                  <div class="c_content">
                     <a href="<?php echo base_url('a/aspek') ?>" class="btn btn-warning btn-raised rippler rippler-default btn-sm">Kembali</a>
                     <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm" style="float: right;">Simpan Data</button>
                  </div>
               </form>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('admin/part_script') ?>
</body>
</html>