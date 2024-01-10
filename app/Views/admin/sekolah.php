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
               <h2>Halaman Pengolah Data Lembaga Sekolah</h2>
               <small>Halaman ini digunakan sebagai halaman pengolah data Lembaga Sekolah </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('a/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('a/sekolah') ?>">Data Master Lembaga Sekolah</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Data Lembaga Sekolah</h2>
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
                           <th>Sekolah</th>
                           <th>Kecamatan</th>
                           <th>Th. Berdiri</th>
                           <th>Kepala Sekolah</th>
                           <th>Status</th>
                           <th>**</th>
                        </tr>
                     </thead>
                     <tbody style="color: black;">
                        <?php
                        $n = 1;
                        foreach ($data as $d) {
                           $ceks = $db->query("select count(*) as jumlah from rekap where kodesekolah = '".$d['kodesekolah']."'")->getRowArray()['jumlah'];
                           $kepsek = "";
                           $cek = $db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getResultArray();
                           if(count($cek) > 0){
                              $kepsek = $db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getRowArray();
                              $kepsek = $kepsek['nama'].' ('.$kepsek['username'].')';
                           }
                           ?>
                           <tr>
                              <td align="center"><?php echo $n++ ?></td>
                              <td><?php echo $d['nama'] ?></td>
                              <td><?php echo $d['kecamatan'] ?></td>
                              <td><?php echo date('Y', strtotime($d['berdiri'])) ?></td>
                              <td><?php echo $kepsek ?></td>
                              <td align="center">
                                 <?php if($kepsek == ''){ ?>
                                    <span class="label label-danger">nonaktif</span>
                                 <?php }else{ ?>
                                    <span class="label label-success">aktif</span>
                                 <?php } ?>
                              </td>
                              <td align="center">
                                 <a href="#detail<?php echo $d['kodesekolah'] ?>" data-toggle="modal" title="Klik untuk mengubah data"><i class="fa fa-edit" style="font-size: 1.5em;"></i></a>
                                 <?php if($ceks == 0){ ?>
                                    &nbsp;
                                    <a href="<?php echo base_url('a/sekolah/hapus/'.$d['kodesekolah']) ?>" title="Klik untuk menghapus data"><i class="fa fa-times" style="font-size: 1.5em;"></i></a>
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
         <form method="post" action="<?php echo base_url('a/sekolah/simpan') ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan data baru. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
               <h5><strong>Detail Sekolah</strong></h5>
               <div class="row">
                  <div class="form-group col-sm-9">
                     <label for="exampleInputEmail1">Nama</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Lembaga Sekolah" name="nama" maxlength="63" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-3">
                     <label for="exampleInputEmail1">NPSN</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="NPSN" name="npsn" maxlength="8" minlength="8" style="color: black;" required>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-sm-3">
                     <label for="exampleInputEmail1">SK Pendirian</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="No. SK Pendirian" name="nobhi" maxlength="36" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-3">
                     <label for="exampleInputEmail1">Tanggal</label>
                     <input type="date" class="form-control input-sm" id="exampleInputEmail1" name="tglbhi" value="<?php echo date('Y-m-d') ?>" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-3">
                     <label for="exampleInputEmail1">SK Ijin Operasional</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="No. SK Ijin Operasional" name="noijin" maxlength="36" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-3">
                     <label for="exampleInputEmail1">Tanggal</label>
                     <input type="date" class="form-control input-sm" id="exampleInputEmail1" name="tglijin" value="<?php echo date('Y-m-d') ?>" style="color: black;" required>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-sm-4">
                     <label for="exampleInputEmail1">Lembaga</label>
                     <select class="form-control input-sm" name="lembaga" style="color: black;" required>
                        <option>Negeri</option>
                        <option>Swasta</option>
                     </select>
                  </div>
                  <div class="form-group col-sm-4">
                     <label for="exampleInputEmail1">Tgl. Berdiri</label>
                     <input type="date" class="form-control input-sm" id="exampleInputEmail1" name="tglberdiri" value="<?php echo date('Y-m-d') ?>" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-4">
                     <label for="exampleInputEmail1">Kecamatan</label>
                     <select class="form-control input-sm" name="kecamatan" style="color: black;" required>
                        <?php for ($i=0; $i < count($kecamatan) ; $i++) {?>
                           <option><?php echo $kecamatan[$i] ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <hr>
               <h5><strong>Detail Kepala Sekolah</strong></h5>
               <div class="row">
                  <div class="form-group col-sm-8">
                     <label for="exampleInputEmail1">Nama</label>
                     <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nama Lengkap Kepala Sekolah" name="kepsek" maxlength="63" style="color: black;" required>
                  </div>
                  <div class="form-group col-sm-4">
                     <label for="exampleInputEmail1">Jenis Kelamin</label>
                     <select class="form-control input-sm" name="jekel" style="color: black;" required>
                        <option>Pria</option>
                        <option>Wanita</option>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Alamat</label>
                  <textarea class="form-control input-sm" name="alamat" rows="3" placeholder="Alamat Lengkap" style="resize: none;color: black;" required></textarea>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Telepon</label>
                  <input type="text" class="form-control input-sm" id="exampleInputEmail1" placeholder="Nomor Telepon" name="telepon" maxlength="14" minlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="color: black;" />
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
   $kepsek = "";
   $cek = $db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getResultArray();
   if(count($cek) > 0){
      $kepsek = $db->query("select nama from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
   }
   ?>
   <div class="modal" id="detail<?php echo $d['kodesekolah'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
               <h4 class="modal-title">Ubah Detail Data</h4>
            </div>
            <form method="post" action="<?php echo base_url('a/sekolah/ubah') ?>">
               <input type="hidden" name="kode" value="<?php echo $d['kodesekolah'] ?>">
               <div class="modal-body">
                  <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Simpan Data</code> untuk menyimpan perubahandata. pilih tombol <code>Batal</code> untuk membatalkan perintah</p>
                  <h5><strong>Detail Sekolah</strong></h5>
                  <div class="row">
                     <div class="form-group col-sm-3">Nama</div>
                     <div class="form-group col-sm-9">: <?php echo $d['nama'] ?></div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-3">Tahun Berdiri</div>
                     <div class="form-group col-sm-9">: <?php echo date('Y', strtotime($d['berdiri'])) ?></div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-3">SK Pendirian</div>
                     <div class="form-group col-sm-9">: <?php echo $d['nobhi'].', '.date('d/m/Y', strtotime($d['tglbhi'])) ?></div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-3">SK Ijin Operasional</div>
                     <div class="form-group col-sm-9">: <?php echo $d['noijin'].', '.date('d/m/Y', strtotime($d['tglijin'])) ?></div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-3">Lembaga</div>
                     <div class="form-group col-sm-9">: <?php echo $d['lembaga'] ?></div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-3">Kecamatan</div>
                     <div class="form-group col-sm-9">: <?php echo $d['kecamatan'] ?></div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-3">Kepala Sekolah</div>
                     <div class="form-group col-sm-9">: <?php echo $kepsek ?></div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-3">Status</div>
                     <div class="form-group col-sm-9">
                        <select class="form-control input-sm" name="status" style="color: black;" required>
                           <option <?php if($kepsek != ''){echo "selected";} ?>>Aktif</option>
                           <option <?php if($kepsek == ''){echo "selected";} ?>>Nonaktif</option>
                        </select>
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