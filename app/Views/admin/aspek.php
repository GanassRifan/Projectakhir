<?php
$db = db_connect();
$aspek = $db->query("select aspek from aspek group by aspek asc")->getResultArray();
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
                        <li class="active"><a href="<?php echo base_url('a/aspek') ?>">Data Aspek Laporan</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Data Aspek Laporan</h2>
                  <ul class="nav navbar-right panel_options">
                     <a href="#tambah" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus-square"></i> Tambah Data Baru</a>
                  </ul>
                  <div class="clearfix"></div>
               </div>
               <div class="c_content">
                  <table id="example" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                     <thead>
                        <tr>
                           <th width="5%">No.</th>
                           <th>Subaspek</th>
                           <th>Aspek</th>
                           <th>**</th>
                        </tr>
                     </thead>
                     <tbody style="color: black;">
                        <?php
                        $n = 1;
                        foreach ($data as $d) {
                           $cek = $db->query("select count(*) as jumlah from skema where kodeaspek = '".$d['kodeaspek']."'")->getRowArray()['jumlah'];
                           ?>
                           <tr>
                              <td align="center"><?php echo $n++ ?></td>
                              <td><?php echo $d['subaspek'] ?></td>
                              <td><?php echo $d['aspek'] ?></td>
                              <td align="center">
                                 <?php if($d['jenis'] == 'pilihan'){ ?>
                                    <a href="<?php echo base_url('a/aspek/detail/'.$d['kodeaspek']) ?>" title="Klik untuk mengubah data"><i class="fa fa-edit" style="font-size: 1.5em;"></i></a>
                                 <?php }else{ ?>
                                    <a href="#detail<?php echo $d['kodeaspek'] ?>" data-toggle="modal" title="Klik untuk mengubah data"><i class="fa fa-edit" style="font-size: 1.5em;"></i></a>
                                 <?php } ?>
                                 <?php if($cek == 0){ ?>
                                    &nbsp;
                                    <a href="<?php echo base_url('a/aspek/hapus/'.$d['kodeaspek']) ?>" title="Klik untuk menghapus data"><i class="fa fa-times" style="font-size: 1.5em;"></i></a>
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
            <h4 class="modal-title">Tambah Data Baru</h4>
         </div>
         <form method="post" action="<?php echo base_url('a/aspek/tambah') ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <div class="form-group">
                  <label for="exampleInputEmail1">Aspek</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Aspek" name="aspek" maxlength="27" list="daftaraspek" style="color: black;" required>
                  <datalist id="daftaraspek">
                     <?php foreach ($aspek as $a) {?>
                        <option><?php echo $a['aspek'] ?></option>
                     <?php } ?>
                  </datalist>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Subaspek</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Subaspek" name="subaspek" maxlength="45" style="color: black;" required>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Jenis Subaspek</label>
                  <select class="form-control input-sm" name="jenis" style="color: black;" required>
                     <option value="teks">Inputan Teks Pendek (0 - 99 Karakter)</option>
                     <option value="deskripsi">Inputan Teks Panjang (> 99 Karakter)</option>
                     <option value="angka">Inputan Angka</option>
                     <option value="tanggal">Inputan Tanggal</option>
                     <option value="pilihan">Inputan Pilihan</option>
                  </select>
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
<?php
foreach ($data as $d) {
   if($d['jenis'] != 'pilihan'){
      ?>
      <div class="modal" id="detail<?php echo $d['kodeaspek'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                  <h4 class="modal-title">Ubah Detail Data</h4>
               </div>
               <form method="post" action="<?php echo base_url('a/aspek/ubah') ?>">
                  <input type="hidden" name="kode" value="<?php echo $d['kodeaspek'] ?>">
                  <div class="modal-body">
                     <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahan data. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Aspek</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Aspek" name="aspek" maxlength="27" value="<?php echo $d['aspek'] ?>" list="daftaraspek" style="color: black;" required>
                        <datalist id="daftaraspek">
                           <?php foreach ($aspek as $a) {?>
                              <option><?php echo $a['aspek'] ?></option>
                           <?php } ?>
                        </datalist>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Subaspek</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Subaspek" name="subaspek" maxlength="45" value="<?php echo $d['subaspek'] ?>" style="color: black;" required>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Satuan</label>
                        <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Satuan Inputan" name="satuan" maxlength="9" value="<?php echo $d['satuan'] ?>" style="color: black;" <?php if($d['jenis'] == 'angka'){ ?> required <?php } ?>>
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
<?php } ?>
</html>