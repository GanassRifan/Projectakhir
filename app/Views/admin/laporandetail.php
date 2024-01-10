<?php
$db = db_connect();
$rombel = $db->query("select * from rombel where kodesekolah = '".$sekolah['kodesekolah']."' and koderelasi = '".$relasi['koderelasi']."'")->getResultArray();
$agama = ['Budha','Hindu','Islam','Katolik','Kristen'];
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
               <h2>Halaman Pengolah Laporan Bulanan</h2>
               <small>Halaman ini digunakan sebagai halaman verifikasi pengolah data Laporan Bulanan </small>
               <div class="page-breadcrumb">
                  <nav class="c_breadcrumbs">
                     <ul>
                        <li><a href="<?php echo base_url('a/') ?>">Beranda</a></li>
                        <li><a href="<?php echo base_url('a/laporan') ?>">Laporan Bulanan</a></li>
                        <li class="active"><a href="<?php echo base_url('a/laporan/detail/'.$rekap['koderekap']) ?>">Verifikasi Laporan Bulanan</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="c_panel">
               <div class="c_content">
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="row col-lg-12">
                           <strong>Profil Sekolah</strong>
                           <br><br>
                           <div class="col-sm-6">
                              <div class="row">
                                 <div class="col-sm-4">Nama</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['nama'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">NPSN</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['npsn'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">SK Pendirian</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['nobhi'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">Tanggal</div>
                                 <div class="col-sm-8">: <?php echo date('d/m/Y', strtotime($sekolah['tglbhi'])) ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">SK Operasional</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['noijin'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">Tanggal</div>
                                 <div class="col-sm-8">: <?php echo date('d/m/Y', strtotime($sekolah['tglijin'])) ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">Akreditasi</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['stakred'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">Tanggal</div>
                                 <div class="col-sm-8">:
                                    <?php
                                    if($sekolah['stakred'] != ''){
                                       echo date('d/m/Y', strtotime($sekolah['tglakred']));
                                    }else{
                                       echo "-";
                                    }
                                    ?>
                                 </div>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="row">
                                 <div class="col-sm-4">Lembaga</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['lembaga'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">Yayasan</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['yayasan'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">Kecamatan</div>
                                 <div class="col-sm-8">: <?php echo $sekolah['kecamatan'] ?></div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">Tahun Berdiri</div>
                                 <div class="col-sm-8">: <?php echo date('Y', strtotime($sekolah['berdiri'])) ?></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <strong>Operator</strong>
                        <br><br>
                        <div class="col-sm-6">
                           <div class="row">
                              <div class="col-sm-4">Nama</div>
                              <div class="col-sm-8">: <?php echo $operator['nama'] ?></div>
                           </div>
                           <div class="row">
                              <div class="col-sm-4">Jenis Kelamin</div>
                              <div class="col-sm-8">: <?php echo $operator['jekel'] ?></div>
                           </div>
                           <div class="row">
                              <div class="col-sm-4">Telepon</div>
                              <div class="col-sm-8">: <?php echo $operator['telepon'] ?></div>
                           </div>
                           <div class="row">
                              <div class="col-sm-4">Alamat</div>
                              <div class="col-sm-8">: <?php echo $operator['alamat'] ?></div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <?php
                     foreach ($skema as $sk) {
                        $aspek = $db->query("select aspek.* from skema join aspek on skema.kodeaspek = aspek.kodeaspek where aspek.aspek = '".$sk['aspek']."' and skema.koderelasi = '".$relasi['koderelasi']."' order by aspek.kodeaspek asc")->getResultArray();
                        ?>
                        <div class="col-sm-3">
                           <div class="row col-lg-12">
                              <strong><?php echo $sk['aspek'] ?></strong>
                              <br><br>
                              <div class="col-sm-12">
                                 <?php
                                 foreach ($aspek as $a) {
                                    $isi = $db->query("select ifnull(count(*),0) as isi from rincian where kodeaspek = '".$a['kodeaspek']."' and koderekap = '".$rekap['koderekap']."'")->getRowArray()['isi'];
                                    if($isi == 0){
                                       $isi = "-";
                                    }else{
                                       $isi = $db->query("select rincian from rincian where kodeaspek = '".$a['kodeaspek']."' and koderekap = '".$rekap['koderekap']."'")->getRowArray()['rincian'];
                                       if($a['jenis'] == 'angka'){
                                          $isi .= " ".$a['satuan'];
                                       }
                                    }
                                    ?>
                                    <div class="row">
                                       <div class="col-sm-6"><?php echo $a['subaspek'] ?></div>
                                       <div class="col-sm-6">: <?php echo $isi ?></div>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>
                     <?php } ?>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-12">
                        <strong>Keadaan Anak Didik</strong>
                        <br><br>
                        <table class="table-bordered" style="width: 100%;">
                           <thead>
                              <tr style="height:30px">
                                 <th rowspan="2" style="text-align: center;">Rombel</th>
                                 <th rowspan="2" style="text-align: center;" width="5%">Jumlah</th>
                                 <th colspan="3" style="text-align: center;">Awal Bulan</th>
                                 <th colspan="3" style="text-align: center;">Masuk</th>
                                 <th colspan="3" style="text-align: center;">Keluar</th>
                                 <th colspan="3" style="text-align: center;">Akhir Bulan</th>
                                 <th rowspan="2" style="text-align: center;" width="7%">Persentase Absen</th>
                              </tr>
                              <tr style="height:30px">
                                 <th style="text-align: center;" width="6%">L</th>
                                 <th style="text-align: center;" width="6%">P</th>
                                 <th style="text-align: center;" width="6%">JML</th>
                                 <th style="text-align: center;" width="6%">L</th>
                                 <th style="text-align: center;" width="6%">P</th>
                                 <th style="text-align: center;" width="6%">JML</th>
                                 <th style="text-align: center;" width="6%">L</th>
                                 <th style="text-align: center;" width="6%">P</th>
                                 <th style="text-align: center;" width="6%">JML</th>
                                 <th style="text-align: center;" width="6%">L</th>
                                 <th style="text-align: center;" width="6%">P</th>
                                 <th style="text-align: center;" width="6%">JML</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              foreach ($rombel as $r) {
                                 $isi = $db->query("select * from kdsiswa where koderombel = '".$r['koderombel']."' and koderekap = '".$rekap['koderekap']."'")->getRowArray();
                                 ?>
                                 <tr style="height: 25px;">
                                    <td>&nbsp;<?php echo $r['rombel'] ?></td>
                                    <td align="center"><?php echo number_format($r['jumlah']) ?></td>
                                    <td align="center"><?php echo number_format($isi['awal_l']) ?></td>
                                    <td align="center"><?php echo number_format($isi['awal_p']) ?></td>
                                    <td align="center"><?php echo number_format($isi['awal_l'] + $isi['awal_p']) ?></td>
                                    <td align="center"><?php echo number_format($isi['masuk_l']) ?></td>
                                    <td align="center"><?php echo number_format($isi['masuk_p']) ?></td>
                                    <td align="center"><?php echo number_format($isi['masuk_l'] + $isi['masuk_p']) ?></td>
                                    <td align="center"><?php echo number_format($isi['keluar_l']) ?></td>
                                    <td align="center"><?php echo number_format($isi['keluar_p']) ?></td>
                                    <td align="center"><?php echo number_format($isi['keluar_l'] + $isi['keluar_p']) ?></td>
                                    <td align="center"><?php echo number_format($isi['awal_l'] + $isi['masuk_l'] - $isi['keluar_l']) ?></td>
                                    <td align="center"><?php echo number_format($isi['awal_p'] + $isi['masuk_p'] - $isi['keluar_p']) ?></td>
                                    <td align="center"><?php echo number_format(($isi['awal_l'] + $isi['awal_p']) + ($isi['masuk_l'] + $isi['masuk_p']) - ($isi['keluar_l'] + $isi['keluar_p'])) ?></td>
                                    <td align="center"><?php echo number_format($isi['absensi'])."%" ?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-12">
                        <strong>Keadaan Agama Anak Didik</strong>
                        <br><br>
                        <table class="table-bordered" style="width: 100%;">
                           <thead>
                              <tr style="height:30px">
                                 <th rowspan="2" style="text-align: center;">Rombel</th>
                                 <?php for ($i=0; $i < 5; $i++) {?>
                                    <th colspan="2" style="text-align: center;"><?php echo $agama[$i] ?></th>
                                 <?php } ?>
                                 <th colspan="3" style="text-align: center;">Jumlah</th>
                              </tr>
                              <tr style="height:30px">
                                 <?php for ($i=0; $i < 6; $i++) {?>
                                    <th style="text-align: center;" width="6%">L</th>
                                    <th style="text-align: center;" width="6%">P</th>
                                 <?php } ?>
                                 <th style="text-align: center;" width="6%">Total</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              foreach ($rombel as $r) {
                                 $isi = $db->query("select * from kdsiswa where koderombel = '".$r['koderombel']."' and koderekap = '".$rekap['koderekap']."'")->getRowArray();
                                 ?>
                                 <tr style="height: 25px;">
                                    <td>&nbsp;<?php echo $r['rombel'] ?></td>
                                    <?php
                                    $totl = 0;
                                    $totp = 0;
                                    for ($i=0; $i < 5; $i++) {
                                       $jl = $db->query("select ifnull(sum(jumlah_l),0) as jumlah from kdagama where agama = '".$agama[$i]."' and koderombel = '".$r['koderombel']."' and koderekap = '".$rekap['koderekap']."'")->getRowArray()['jumlah'];
                                       $jp = $db->query("select ifnull(sum(jumlah_p),0) as jumlah from kdagama where agama = '".$agama[$i]."' and koderombel = '".$r['koderombel']."' and koderekap = '".$rekap['koderekap']."'")->getRowArray()['jumlah'];
                                       $totl += $jl;
                                       $totp += $jp;
                                       ?>
                                       <td align="center"><?php echo number_format($jl) ?></td>
                                       <td align="center"><?php echo number_format($jp) ?></td>
                                    <?php } ?>
                                    <td align="center"><?php echo number_format($totl) ?></td>
                                    <td align="center"><?php echo number_format($totp) ?></td>
                                    <td align="center"><?php echo number_format($totl + $totp) ?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-12">
                        <strong>Keadaan Pendidik dan Tenaga Kependidikan (PTK)</strong>
                        <br><br>
                        <strong>Informasi Dasar PTK</strong>
                        <table class="table-bordered" style="width: 100%;">
                           <thead>
                              <tr style="height:30px">
                                 <th rowspan="2" style="text-align: center;" width="3%">No</th>
                                 <th rowspan="2" style="text-align: center;" width="20%">Nama / NIP</th>
                                 <th rowspan="2" style="text-align: center;" width="3%">L/P</th>
                                 <th rowspan="2" style="text-align: center;" width="18%">Tempat, Tanggal Lahir</th>
                                 <th rowspan="2" style="text-align: center;">Agama</th>
                                 <th rowspan="2" style="text-align: center;" width="18%">Ijazah Terakhir</th>
                                 <th rowspan="2" style="text-align: center;">Jabatan</th>
                                 <th rowspan="2" style="text-align: center;">Gol. R.</th>
                                 <th colspan="2" style="text-align: center;">Masa Kerja</th>
                              </tr>
                              <tr style="height:30px">
                                 <th style="text-align: center;" width="6%">Tahun</th>
                                 <th style="text-align: center;" width="6%">Bulan</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $n = 1;
                              foreach ($ptk as $p) {
                                 ?>
                                 <tr style="height: 25px;">
                                    <td align="center">&nbsp;<?php echo $n++ ?></td>
                                    <td>
                                       &nbsp;<?php echo $p['nama'] ?><br>
                                       &nbsp;<small><?php echo $p['nip'] ?></small>
                                    </td>
                                    <td align="center"><?php echo $p['jekel'] ?></td>
                                    <td>&nbsp;<?php echo $p['tpl'].', '.date('d/m/Y', strtotime($p['tgl'])) ?></td>
                                    <td align="center"><?php echo $p['agama'] ?></td>
                                    <td>&nbsp;<?php echo $p['ijazah'] ?></td>
                                    <td align="center"><?php echo $p['jabatan'] ?></td>
                                    <td align="center"><?php echo $p['golongan'] ?></td>
                                    <td align="center"><?php echo $p['bulan'] ?></td>
                                    <td align="center"><?php echo $p['tahun'] ?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                        <br>
                        <strong>Status SK PTK</strong>
                        <table class="table-bordered" style="width: 100%;">
                           <thead>
                              <tr style="height:30px">
                                 <th rowspan="2" style="text-align: center;" width="3%">No</th>
                                 <th rowspan="2" style="text-align: center;" width="20%">Nama / NIP</th>
                                 <th rowspan="2" style="text-align: center;" width="3%">L/P</th>
                                 <th colspan="3" style="text-align: center;">SK Pengangkatan</th>
                                 <th colspan="3" style="text-align: center;">SK Terakhir</th>
                              </tr>
                              <tr style="height:30px">
                                 <th style="text-align: center;">Nomor</th>
                                 <th style="text-align: center;" width="10%">Tgl. Surat</th>
                                 <th style="text-align: center;" width="10%">Tmt</th>
                                 <th style="text-align: center;">Nomor</th>
                                 <th style="text-align: center;" width="10%">Tgl. Surat</th>
                                 <th style="text-align: center;" width="10%">Tmt</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $n = 1;
                              foreach ($ptk as $p) {
                                 $sk = $db->query("select * from sk where kodeptk = '".$p['kodeptk']."'")->getRowArray();
                                 $no1 = '';
                                 $tgl1 = '-';
                                 $tmt1 = '-';
                                 $no2 = '';
                                 $tgl2 = '-';
                                 $tmt2 = '-';
                                 if($sk['noangkat'] != ''){
                                    $no1 = $sk['noangkat'];
                                    $tgl1 = date('d/m/Y', strtotime($sk['tglangkat']));
                                    $tmt1 = date('d/m/Y', strtotime($sk['tmtangkat']));
                                 }
                                 if($sk['noakhir'] != ''){
                                    $no1 = $sk['noakhir'];
                                    $tgl1 = date('d/m/Y', strtotime($sk['tglakhir']));
                                    $tmt1 = date('d/m/Y', strtotime($sk['tmtakhir']));
                                 }
                                 ?>
                                 <tr style="height: 25px;">
                                    <td align="center">&nbsp;<?php echo $n++ ?></td>
                                    <td>
                                       &nbsp;<?php echo $p['nama'] ?><br>
                                       &nbsp;<small><?php echo $p['nip'] ?></small>
                                    </td>
                                    <td align="center"><?php echo $p['jekel'] ?></td>
                                    <td>&nbsp;<?php echo $no1 ?></td>
                                    <td align="center"><?php echo $tgl1 ?></td>
                                    <td align="center"><?php echo $tmt1 ?></td>
                                    <td>&nbsp;<?php echo $no2 ?></td>
                                    <td align="center"><?php echo $tgl2 ?></td>
                                    <td align="center"><?php echo $tmt2 ?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                        <br>
                        <strong>Tunjangan PTK</strong>
                        <table class="table-bordered" style="width: 100%;">
                           <thead>
                              <tr style="height:30px">
                                 <th rowspan="2" style="text-align: center;" width="3%">No</th>
                                 <th rowspan="2" style="text-align: center;" width="20%">Nama / NIP</th>
                                 <th rowspan="2" style="text-align: center;" width="3%">L/P</th>
                                 <th rowspan="2" style="text-align: center;" width="5%">Kelas</th>
                                 <th colspan="3" style="text-align: center;">Tunjangan Lain</th>
                                 <th colspan="5" style="text-align: center;">Absensi</th>
                                 <th rowspan="2" style="text-align: center;" width="27%">Keterangan</th>
                              </tr>
                              <tr style="height:30px">
                                 <th style="text-align: center;" width="10%">TPG</th>
                                 <th style="text-align: center;" width="10%">Insentif</th>
                                 <th style="text-align: center;" width="10%">Kesra</th>
                                 <th style="text-align: center;" width="3%">S</th>
                                 <th style="text-align: center;" width="3%">I</th>
                                 <th style="text-align: center;" width="3%">A</th>
                                 <th style="text-align: center;" width="3%">DL</th>
                                 <th style="text-align: center;" width="5%">Jumlah</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $n = 1;
                              foreach ($ptk as $p) {
                                 $tj = $db->query("select * from tunjangan where kodeptk = '".$p['kodeptk']."'")->getRowArray();
                                 $ab = $db->query("select * from absensi where kodeptk = '".$p['kodeptk']."'")->getRowArray();
                                 $jml = $ab['sakit'] + $ab['ijin'] + $ab['alfa'] + $ab['dinas'];
                                 ?>
                                 <tr style="height: 25px;">
                                    <td align="center">&nbsp;<?php echo $n++ ?></td>
                                    <td>
                                       &nbsp;<?php echo $p['nama'] ?><br>
                                       &nbsp;<small><?php echo $p['nip'] ?></small>
                                    </td>
                                    <td align="center"><?php echo $p['jekel'] ?></td>
                                    <td align="center"><?php echo $p['kelas'] ?></td>
                                    <td align="right"><?php echo number_format($tj['tpg']) ?>&nbsp;</td>
                                    <td align="right"><?php echo number_format($tj['insentif']) ?>&nbsp;</td>
                                    <td align="right"><?php echo number_format($tj['kesra']) ?>&nbsp;</td>
                                    <td align="center"><?php echo number_format($ab['sakit']) ?></td>
                                    <td align="center"><?php echo number_format($ab['ijin']) ?></td>
                                    <td align="center"><?php echo number_format($ab['alfa']) ?></td>
                                    <td align="center"><?php echo number_format($ab['dinas']) ?></td>
                                    <td align="center"><?php echo number_format($jml) ?></td>
                                    <td>&nbsp;<?php echo $p['keterangan'] ?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-12">
                        <strong>Lampiran Berkas Laporan Bulanan</strong>
                        <br>
                        <?php if(count($berkas) == 0){
                           echo "tidak ada lampiran";
                        }else{ ?>
                           <br>
                           <?php foreach ($berkas as $b) {?>
                              <?php echo $b['deskripsi'] ?> <a href="<?php echo base_url('assets/file/'.$b['berkas']) ?>" download>Unduh</a>
                           <?php } ?>
                        <?php } ?>
                     </div>
                  </div>
               </div>
               <div class="c_content">
                  <a href="<?php echo base_url('a/laporan') ?>" class="btn btn-warning btn-raised rippler rippler-default btn-sm">Kembali</a>
                  <?php if($rekap['status'] >= 6 && $rekap['status'] != 'x' && $rekap['status'] != '8'){ ?>
                     <a href="#verifikasi" data-toggle="modal" class="btn btn-success btn-raised rippler rippler-default btn-sm" style="float: right;">Verifikasi Data</a>
                  <?php } ?>
               </div>
            </div>
         </section>
      </section>
   </section>
   <?php echo view('admin/part_script') ?>
</body>
<div class="modal" id="verifikasi" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">Verifikasi Data</h4>
         </div>
         <form action="<?php echo base_url('a/laporan/tolak') ?>" method="post">
            <input type="hidden" name="kode" value="<?php echo $rekap['koderekap'] ?>">
            <div class="modal-body">
               <p style="text-align: justify;">Jika laporan bulanan sudah sesuai, pilih tombol <code>Laporan Sesuai</code> untuk memverifikasi persetujuan. Atau masukkan catatan khusus jika laporan belum sesuai, lalu pilih tombol <code>Laporan Tidak Sesuai</code></p>
               <div class="form-group">
                  <label for="exampleInputEmail1">Catatan Verifikasi</label>
                  <textarea class="form-control input-sm" name="catatan" placeholder="Uraian Catatan Verifikasi Laporan Bulanan" rows="9" style="resize: none;" required></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <a href="<?php echo base_url('a/laporan/terima/'.$rekap['koderekap']) ?>" class="btn btn-success btn-raised rippler rippler-default btn-sm">Laporan Sesuai</a>
               <button type="submit" class="btn btn-warning btn-raised rippler rippler-default btn-sm">Laporan Tidak Sesuai</button>
            </div>
         </form>
      </div>
   </div>
</div>
</html>