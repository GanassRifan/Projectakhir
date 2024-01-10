<?php
$db = db_connect();
$sekolah = $db->query("select kodesekolah from pengguna where kodepengguna = '".session()->get('low')."'")->getRowArray()['kodesekolah'];
$sekolah = $db->query("select * from sekolah where kodesekolah = '".$sekolah."'")->getRowArray();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$bulan = date('m');
$tahun = date('Y');
$cek = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
if($cek > 0){
   $cek = $db->query("select koderelasi from relasi where bulan = '".(int)$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
   $cek = $db->query("select ifnull(count(*),0) as jumlah from rekap where kodesekolah = '".$sekolah['kodesekolah']."' and koderelasi = '".$cek."'")->getRowArray()['jumlah'];
}
?>
<!DOCTYPE html>
<html lang="en">
<?php echo view('operator/part_head') ?>
<body id="default-scheme">
   <section id="container">
      <?php echo view('operator/part_header') ?>
      <?php echo view('operator/part_sidebar') ?>
      <section id="main-content">
         <section class="wrapper">
            <div class="profile-page">
               <div class="row profile-cover" style="background-image: url('<?php echo base_url('assets/gambar/default/timeline.jpg') ?>');">
                  <div class="row">
                     <div class="col-md-3 profile-image">
                        <div class="profile-image-container">
                           <img src="<?php echo base_url('assets/gambar/default/logo.png') ?>" style="border: none;"/>
                        </div>
                     </div>
                     <div class="col-md-12 profile-info">
                        <div class=" profile-info-value">
                           <h3><?php echo $sekolah['nama'] ?></h3>
                           <p><?php echo $sekolah['npsn'].' - '.$sekolah['kecamatan'] ?></p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 profile-under-cover-style">
                     &nbsp;
                  </div>
               </div>

               <div class="clearfix"></div>

               <div class="row margin-top-70">
                  <div class="col-md-12" style="background:rgba(255, 255, 255, 0.5);">
                     <div class="row margin-top-16 margin-bottom-16">   
                        <div class="col-md-12">
                           <div class="panel panel-profile">
                              <div class="panel-heading overflow-hidden">
                                 <h2 class="panel-title heading-sm pull-left"><i class="fa fa-cubes"></i>Riwayat Laporan Bulanan Terakhir</h2>
                              </div>
                              <div class="panel-body">
                                 <div class="row projects">
                                    <?php
                                    $bulan = (int)$bulan;
                                    for ($i=0; $i < 4; $i++) {
                                       $cek = $db->query("select ifnull(count(*),0) as jumlah from relasi where bulan = '".$bulan."' and tahun = '".$tahun."'")->getRowArray()['jumlah'];
                                       if($cek > 0) {
                                          $relasi = $db->query("select koderelasi from relasi where bulan = '".$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
                                          $cek = $db->query("select ifnull(count(*),0) as jumlah from rekap where kodesekolah = '".$sekolah['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray()['jumlah'];
                                       }
                                       $operator = "-";
                                       $waktu = "-";
                                       $status = "-";
                                       $log = "-";
                                       $waktulog = "-";
                                       if($cek > 0){
                                          $relasi = $db->query("select koderelasi from relasi where bulan = '".$bulan."' and tahun = '".$tahun."'")->getRowArray()['koderelasi'];
                                          $rk = $db->query("select * from rekap where kodesekolah = '".$sekolah['kodesekolah']."' and koderelasi = '".$relasi."'")->getRowArray();
                                          $operator = $db->query("select nama from pengguna where kodepengguna = '".$rk['kodepengguna']."'")->getRowArray()['nama'];
                                          $status = $rk['status'];
                                          $waktu = date('d-m-Y H:i:s', strtotime($rk['waktu']));
                                          $cek = $db->query("select ifnull(count(*),0) as jumlah from verifikasi where koderekap = '".$rk['koderekap']."'")->getRowArray()['jumlah'];
                                          if($cek > 0){
                                             $log = $db->query("select * from verifikasi where koderekap = '".$rk['koderekap']."' order by waktu desc")->getRowArray();
                                             $waktulog = date('d-m-Y H:i:s', strtotime($log['waktu']));
                                             $log = $log['catatan'];
                                          }
                                       }
                                       ?>
                                       <div class="col-md-6">
                                          <div class="project-box bordered">
                                             <h4 class="project-title"><?php echo $daftarbulan[$bulan].' '.$tahun ?></h4>
                                             <p class="project-edited">Operator : <span><?php echo $operator ?></span></p>
                                             <p class="project-timezone">Dikirim Pada : <?php echo $waktu ?></p>
                                             <hr/>
                                             <div class="row" style="margin-top:-10px; margin-bottom:-14px;">
                                                <div class="col-md-4 project-total-hours">
                                                   <p>LOG AKTIFITAS</p>
                                                   <p>
                                                      <?php
                                                      if($status >= 0 && $status < 3){
                                                         echo "Verifikasi Lembaga";
                                                      }else if($status >= 3 && $status < 6){
                                                         echo "Verifikasi Korwilcam";
                                                      }else if($status >= 6 && $status < 9){
                                                         echo "Verifikasi Pusat";
                                                      }else {
                                                         echo "-";
                                                      }
                                                      ?>
                                                   </p>
                                                </div>  
                                                <div class="col-md-8 project-description">
                                                   <p><?php echo $log ?></p>
                                                   <p><?php echo $waktulog ?></p>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-6">
                                                </div>
                                                <div class="col-md-6">
                                                   <?php if($status == ''){ ?>
                                                      <p class="pull-right"><i class="fa fa-circle text-info"></i> Proses Rekap</p>
                                                   <?php }else if($status == 'x'){ ?>
                                                      <p class="pull-right"><i class="fa fa-circle text-success"></i> Selesai</p>
                                                   <?php }else if($status == '-'){ ?>
                                                      <p class="pull-right"><i class="fa fa-circle text-danger"></i> Belum</p>
                                                   <?php }else if($status == 2 || $status == 5 || $status == 8){ ?>
                                                      <p class="pull-right"><i class="fa fa-circle text-info"></i> Penyesuaian</p>
                                                   <?php }else{ ?>
                                                      <p class="pull-right"><i class="fa fa-circle text-warning"></i> Verifikasi</p>
                                                   <?php } ?>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <?php
                                       if($bulan == 1){
                                          $bulan = 12;
                                          $tahun--;
                                       }else{
                                          $bulan--;
                                       }
                                       ?>
                                    <?php } ?>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>  
         </section>
      </section>
   </section>
   <?php echo view('operator/part_script') ?>
</body>
</html>