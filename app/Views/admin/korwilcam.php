<?php
$db = db_connect();
$kecamatan = ['Bojong','Buaran','Doro','Kajen','Kandangserang','Karanganyar','Karangdadap','Kedungwuni','Kesesi','Lebakbarang','Paninggaran','Petungkriyono','Siwalan','Sragi','Talun','Tirto','Wiradesa','Wonokerto','Wonopringgo'];
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
               <h2>Halaman Pengolah Data Korwilcam</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Koordinator Wilayah Kecamatan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('a/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('a/korwilcam') ?>">Data Master Korwilcam</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Data Korwilcam</h2>
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
                           <th>Nama</th>
                           <th>Kecamatan</th>
                           <th>Username</th>
                           <th>Status</th>
                           <th>**</th>
                        </tr>
                     </thead>
                     <tbody style="color: black;">
                        <?php
                        $n = 1;
                        foreach ($data as $d) {
                           $cek = $db->query("select count(*) as jumlah from verifikasi where kodepengguna = '".$d['kodepengguna']."'")->getRowArray()['jumlah'];
                           ?>
                           <tr>
                              <td align="center"><?php echo $n++ ?></td>
                              <td><?php echo $d['nama'] ?></td>
                              <td><?php echo $d['kecamatan'] ?></td>
                              <td><?php echo $d['username'] ?></td>
                              <td align="center">
                                 <?php if($d['status'] == 'Aktif'){ ?>
                                    <span class="label label-success"><?php echo strtolower($d['status']) ?></span>
                                 <?php }else{ ?>
                                    <span class="label label-danger"><?php echo strtolower($d['status']) ?></span>
                                 <?php } ?>
                              </td>
                              <td align="center">
                                 <a href="#detail<?php echo $d['kodepengguna'] ?>" data-toggle="modal" title="Klik untuk mengubah data"><i class="fa fa-edit" style="font-size: 1.5em;"></i></a>
                                 <?php if($cek == 0){ ?>
                                    &nbsp;
                                    <a href="<?php echo base_url('a/korwilcam/hapus/'.$d['kodepengguna']) ?>" title="Klik untuk menghapus data"><i class="fa fa-times" style="font-size: 1.5em;"></i></a>
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
         <form method="post" action="<?php echo base_url('a/korwilcam/simpan') ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <div class="form-group">
                  <label for="exampleInputEmail1">Nama</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Lengkap Korwilcam" name="nama" maxlength="63" style="color: black;" required>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Jenis Kelamin</label>
                  <select class="form-control input-sm" name="jekel" style="color: black;" required>
                     <option>Pria</option>
                     <option>Wanita</option>
                  </select>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Alamat</label>
                  <textarea class="form-control input-sm" name="alamat" rows="3" placeholder="Alamat Lengkap" style="resize: none;color: black;" required></textarea>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Telepon</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="nomor Telepon" name="telepon" maxlength="14" minlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="color: black;" required />
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Kecamatan</label>
                  <select class="form-control input-sm" name="kecamatan" style="color: black;" required>
                     <?php
                     for ($i=0; $i < count($kecamatan) ; $i++) {
                        $cek = $db->query("select * from pengguna where kecamatan = '".$kecamatan[$i]."' and level = 'high' and status = 'Aktif'")->getResultArray();
                        if(count($cek) == 0){
                           ?>
                           <option><?php echo $kecamatan[$i] ?></option>
                        <?php } ?>
                     <?php } ?>
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
<?php foreach ($data as $d) {?>
   <div class="modal" id="detail<?php echo $d['kodepengguna'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Ubah Detail Data</h4>
            </div>
            <form method="post" action="<?php echo base_url('a/korwilcam/ubah') ?>">
               <input type="hidden" name="kode" value="<?php echo $d['kodepengguna'] ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahan detail. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Nama</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Lengkap Korwilcam" name="nama" value="<?php echo $d['nama'] ?>" maxlength="63" style="color: black;" required>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Jenis Kelamin</label>
                     <select class="form-control input-sm" name="jekel" style="color: black;" required>
                        <option <?php if($d['jekel'] == 'Pria'){echo "selected";} ?>>Pria</option>
                        <option <?php if($d['jekel'] == 'Wanita'){echo "selected";} ?>>Wanita</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Alamat</label>
                     <textarea class="form-control input-sm" name="alamat" rows="3" placeholder="Alamat Lengkap" style="resize: none;color: black;" required><?php echo $d['alamat'] ?></textarea>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Telepon</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="nomor Telepon" name="telepon" value="<?php echo $d['telepon'] ?>" maxlength="14" minlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="color: black;" required />
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Kecamatan</label>
                     <select class="form-control input-sm" name="kecamatan" style="color: black;" required>
                        <?php for ($i=0; $i < count($kecamatan) ; $i++) {?>
                           <option <?php if($d['kecamatan'] == $kecamatan[$i]){echo "selected";} ?>><?php echo $kecamatan[$i] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Status</label>
                     <select class="form-control input-sm" name="status" style="color: black;" required>
                        <option <?php if($d['status'] == 'Aktif'){echo "selected";} ?>>Aktif</option>
                        <option <?php if($d['status'] == 'Nonaktif'){echo "selected";} ?>>Nonaktif</option>
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
<?php } ?>
</html>