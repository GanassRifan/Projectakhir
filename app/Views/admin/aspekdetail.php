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
                        <li class="active"><a href="<?php echo base_url('a/aspek/detail/'.$data['kodeaspek']) ?>">Detail Pilihan Aspek</a></li>
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
               <form method="post" action="<?php echo base_url('a/aspek/ubahpilihan') ?>">
                  <input type="hidden" name="kode" value="<?php echo $data['kodeaspek'] ?>">
                  <div class="c_content">
                     <div class="row">
                        <div class="form-group col-sm-4">
                           <label for="exampleInputEmail1">Aspek</label>
                           <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Aspek" name="aspek" maxlength="27" list="daftaraspek" value="<?php echo $data['aspek'] ?>" style="color: black;" required>
                           <datalist id="daftaraspek">
                              <?php foreach ($daftaraspek as $a) {?>
                                 <option><?php echo $a['aspek'] ?></option>
                              <?php } ?>
                           </datalist>
                        </div>
                        <div class="form-group col-sm-8">
                           <label for="exampleInputEmail1">Subaspek</label>
                           <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Subaspek" name="subaspek" maxlength="45" value="<?php echo $data['subaspek'] ?>" style="color: black;" required>
                        </div>
                     </div>
                  </div>
                  <div class="c_content">
                     <a href="<?php echo base_url('a/aspek') ?>" class="btn btn-warning btn-raised rippler rippler-default btn-sm">Kembali</a>
                     <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm" style="float: right;">Simpan Data</button>
                  </div>
               </form>
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
                        foreach ($pilihan as $p) {
                           $cek = $db->query("select * from rincian where kodeaspek = '".$p['kodeaspek']."'")->getResultArray();
                           if(count($cek) > 0){
                              $cek = $db->query("select * from rincian where kodeaspek = '".$p['kodeaspek']."'")->getRowArray()['rincian'];
                           }else{
                              $cek = "";
                           }
                           ?>
                           <tr>
                              <td align="center"><?php echo $n++ ?></td>
                              <td><?php echo $p['pilihan'] ?></td>
                              <td>
                                 <?php if(count($pilihan) > 1 && $cek != $p['pilihan']){ ?>
                                    <a href="<?php echo base_url('a/aspek/hapuspilihan/'.$p['kodepilihan']) ?>" title="Klik untuk menghapus data"><i class="fa fa-times" style="font-size: 1.5em;"></i></a>
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
   <?php echo view('admin/part_script') ?>
</body>
<div class="modal" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Tambah Pilihan Baru</h4>
         </div>
         <form method="post" action="<?php echo base_url('a/aspek/tambahpilihan') ?>">
            <input type="hidden" name="kode" value="<?php echo $data['kodeaspek'] ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <div class="form-group">
                  <label for="exampleInputEmail1">Pilihan</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Pilihan" name="pilihan" maxlength="36" style="color: black;" required>
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