<?php
$db = db_connect();
$kodesekolah = $db->query("select kodesekolah from pengguna where kodepengguna = '".session()->get('low')."'")->getRowArray()['kodesekolah'];
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$info = $db->query("select * from infosistem")->getRowArray();
$cek = $db->query("select ifnull(count(*),0) as jumlah from rekap where (status = '2' or status = '5' or status = '8') and kodesekolah = '".$kodesekolah."'")->getRowArray()['jumlah'];
?>
<header class="header fixed-top clearfix">
   <div class="brand">
      <a href="<?php echo base_url('o/') ?>" class="logo">Operator</a>
      <div class="sidebar-toggle-box">
         <div class="fa fa-bars"></div>
      </div>
   </div>
   <div class="top-nav">
      <ul class="nav navbar-nav navbar-right">
         <li role="presentation" class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
               <span class="pe-7s-bell" style="font-size:22.9px;"></span>
               <?php if($cek > 0){ ?>
                  <span class="badge bg-color label-danger"><?php echo number_format($cek) ?></span>
               <?php } ?>
            </a>
            <?php
            if($cek > 0){
               $data = $db->query("select * from rekap where (status = '2' or status = '5' or status = '8') and kodesekolah = '".$kodesekolah."' order by waktu asc")->getResultArray();
               ?>
               <ul id="menu" class="dropdown-menu list-unstyled msg_list animated fadeInUp" role="menu">
                  <?php
                  foreach ($data as $d) {
                     $subjek = "[Kepala Sekolah]";
                     if($d['status'] == 5){
                        $subjek = "[Korwilcam]";
                     }else if($d['status'] == '8'){
                        $subjek = "[Pusat]";
                     }
                     $catatan = $db->query("select catatan from verifikasi where koderekap = '".$d['koderekap']."' order by waktu desc limit 1")->getRowArray()['catatan'];
                     $periode = $db->query("select * from relasi where koderelasi = '".$d['koderelasi']."'")->getRowArray();
                     $periode = "Laporan Bulanan ".$daftarbulan[$periode['bulan']]." ".$periode['tahun'];
                     ?>
                     <li>
                        <a href="<?php echo base_url('o/laporan/detail/'.$d['koderekap']) ?>" class="hvr-bounce-to-right">
                           <span>
                              <span><?php echo $subjek.' '.$catatan ?></span>
                           </span>
                           <br>
                           <span class="message mt-5">
                              <?php echo $periode ?>
                           </span>
                        </a>
                     </li>
                  <?php } ?>
               </ul>
            <?php } ?>
         </li>
         <li class="dropdown">
            <a href="javascript:void(0);" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
               <img src="<?php echo base_url('assets/gambar/default/'.$info['logo']) ?>" alt="image">Operator
               <span class=" fa fa-angle-down"></span>
            </a>
            <ul class="dropdown-menu dropdown-usermenu animated fadeInUp pull-right">
               <li><a href="<?php echo base_url('o/pengguna') ?>" class="hvr-bounce-to-right">  Pengguna</a></li>
               <li><a href="<?php echo base_url('o/akses') ?>" class="hvr-bounce-to-right">  Akses</a></li>
               <li><a href="<?php echo base_url('proseslogout') ?>" class="hvr-bounce-to-right"><i class=" icon-login pull-right"></i> Log Out</a>
               </li>
            </ul>
         </li>
      </ul>
   </div>
</header>    