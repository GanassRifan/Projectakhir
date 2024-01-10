<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$daftartahun = date('Y');
$cek = $db->query("select * from sekolah where kecamatan = '".$kecamatan."'")->getResultArray();
if(count($cek) > 0){
   $daftartahun = $db->query("select year(berdiri) as tahun from sekolah where kecamatan = '".$kecamatan."' order by berdiri asc")->getRowArray()['tahun'];
}
$relasi = "";
$laporan = [];
$cek = $db->query("select * from relasi where bulan = '".$bulan."' and tahun = '".$tahun."'")->getResultArray();
if(count($cek) > 0){
   $relasi = $db->query("select * from relasi where bulan = '".$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
}
?>
<!DOCTYPE html>
<html lang="en">
<?php echo view('korwilcam/part_head') ?>
<body id="default-scheme">
   <section id="container">
      <?php echo view('korwilcam/part_header') ?>
      <?php echo view('korwilcam/part_sidebar') ?>
      <section id="main-content">
         <section class="wrapper">
            <div class="top-page-header">
               <h2>Halaman Pengolah Laporan Bulanan</h2>
               <a href="#cetak" data-toggle="modal" class="btn btn-success btn-sm" style="float: right;"><i class="fa fa-print"></i> Cetak Laporan Data</a>
               <small>Halaman ini digunakan sebagai halaman pengolah data Laporan Bulanan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('k/') ?>">Beranda</a></li>
                        <li class="active"><a href="<?php echo base_url('k/laporan') ?>">Laporan Bulanan</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_title">
                  <h2>Laporan Bulanan</h2>
                  <ul class="nav navbar-right panel_options">
                     <form method="post" action="<?php echo base_url('k/laporan/tampil') ?>">
                        <input type="hidden" name="kecamatan" value="<?php echo $kecamatan ?>">
                        <li>
                           <div class="row col-lg-13">
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
                           </div>
                        </li>
                     </form>
                  </ul>
                  <div class="clearfix"></div>
               </div>
               <div class="c_content">
                  <table id="example" class="table table-striped table-bordered" style="border-spacing:0px; width:100%">
                     <thead>
                        <tr>
                           <th width="5%">No.</th>
                           <th width="22%">Sekolah</th>
                           <th width="15%">Kepala Sekolah</th>
                           <th width="12%">Waktu Kirim</th>
                           <th>Status</th>
                           <th width="5%">**</th>
                        </tr>
                     </thead>
                     <tbody style="color: black;">
                        <?php
                        $n = 1;
                        foreach ($sekolah as $d) {
                           $kode = "";
                           $kepsek = "";
                           $wktkirim = "-";
                           $st = "";
                           $status = "Belum";
                           $cek = $db->query("select * from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getResultArray();
                           if(count($cek) > 0){
                              $kepsek = $db->query("select nama from pengguna where level = 'mid' and kodesekolah = '".$d['kodesekolah']."' and status = 'Aktif'")->getRowArray()['nama'];
                           }
                           $cek = $db->query("select * from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getResultArray();
                           if(count($cek) > 0){
                              $kode = $db->query("select koderekap from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['koderekap'];
                              $st = $db->query("select status from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['status'];
                              $status = $db->query("select * from verifikasi where koderekap = '".$kode."' order by waktu desc")->getRowArray();
                              $wktkirim = $db->query("select waktu from rekap where kodesekolah = '".$d['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['waktu'];
                              $wktkirim = date('d/m/Y H:i:s', strtotime($wktkirim));
                              $status = $status['catatan'].' ('.date('d/m/Y H:i:s', strtotime($status['waktu'])).')';
                           }
                           if($d['kecamatan'] == $kecamatan){
                              ?>
                              <tr>
                                 <td align="center"><?php echo $n++ ?></td>
                                 <td><?php echo $d['nama'] ?></td>
                                 <td><?php echo $kepsek ?></td>
                                 <td>
                                    <?php
                                    if($st == ''){
                                       echo "-";
                                    }else{
                                       echo $wktkirim;
                                    }
                                    ?>
                                 </td>
                                 <td><?php echo $status ?></td>
                                 <td align="center">
                                    <?php if(($st >= 3 && $st < 6) || $st == 'x'){ ?>
                                       <a href="<?php echo base_url('k/laporan/detail/'.$kode) ?>" title="Klik untuk mengubah data"><i class="fa fa-edit" style="font-size: 1.5em;"></i></a>
                                    <?php } ?>
                                 </td>
                              </tr>
                           <?php } ?>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('korwilcam/part_script') ?>
</body>
<div class="modal" id="cetak" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Cetak Laporan</h4>
         </div>
         <form method="post" action="<?php echo base_url('a/laporan/cetak') ?>" target="blank">
            <?php $kecamatan = $db->query("select kecamatan from pengguna where kodepengguna = '".session()->get('high')."'")->getRowArray()['kecamatan'] ?>
            <input type="hidden" name="kecamatan" value="<?php echo $kecamatan ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Masukkan detail data sesuai form inputan, lalu pilih tombol <code>Cetak Data</code> untuk menampilkan laporan data</p>
               <div class="form-group">
                  <label for="exampleInputEmail1">Jenis Laporan</label>
                  <select class="form-control input-sm" name="jenis" style="color: black;" required>
                     <option>Excel</option>
                     <option>Pdf</option>
                  </select>
               </div>
               <div class="row">
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Bulan</label>
                     <select class="form-control input-sm" name="bulan" style="color: black;" required>
                        <?php for ($i=1; $i <= count($daftarbulan) ; $i++) {?>
                           <option value="<?php echo $i ?>"><?php echo $daftarbulan[$i] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Tahun</label>
                     <select class="form-control input-sm" name="tahun" style="color: black;" required>
                        <?php for ($i=date('Y'); $i >= $daftartahun ; $i--) {?>
                           <option><?php echo $i ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-warning btn-raised rippler rippler-default btn-sm" data-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-success btn-raised rippler rippler-default btn-sm">Cetak Data</button>
            </div>
         </form>
      </div>
   </div>
</div>
</html>