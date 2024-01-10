<?php
$db = db_connect();
$daftarbulan = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$info = $db->query("select * from infosistem")->getRowArray();
$cek = $db->query("select ifnull(count(*),0) as jumlah from rekap where status = '6'")->getRowArray()['jumlah'];
?>
<header class="header fixed-top clearfix">
   <div class="brand">
      <a href="<?php echo base_url('a/') ?>" class="logo">Administrator</a>
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
               $data = $db->query("select * from rekap where status = '6' order by waktu asc")->getResultArray();
               ?>
               <ul id="menu" class="dropdown-menu list-unstyled msg_list animated fadeInUp" role="menu">
                  <?php
                  foreach ($data as $d) {
                     $sekolah = $db->query("select nama from sekolah where kodesekolah = '".$d['kodesekolah']."'")->getRowArray()['nama'];
                     $periode = $db->query("select * from relasi where koderelasi = '".$d['koderelasi']."'")->getRowArray();
                     $periode = "Laporan Bulanan ".$daftarbulan[$periode['bulan']]." ".$periode['tahun'];
                     ?>
                     <li>
                        <a href="<?php echo base_url('a/laporan/detail/'.$d['koderekap']) ?>" class="hvr-bounce-to-right">
                           <span>
                              <span><?php echo $sekolah ?></span>
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
               <img src="<?php echo base_url('assets/gambar/default/'.$info['logo']) ?>" alt="image">Administrator
               <span class=" fa fa-angle-down"></span>
            </a>
            <ul class="dropdown-menu dropdown-usermenu animated fadeInUp pull-right">
               <li><a href="<?php echo base_url('a/infosistem') ?>" class="hvr-bounce-to-right">  Sistem</a></li>
               <li><a href="<?php echo base_url('a/pengguna') ?>" class="hvr-bounce-to-right">  Pengguna</a></li>
               <li><a href="<?php echo base_url('a/akses') ?>" class="hvr-bounce-to-right">  Akses</a></li>
               <li><a href="<?php echo base_url('a/pin') ?>" class="hvr-bounce-to-right">  PIN</a></li>
               <li><a href="<?php echo base_url('proseslogout') ?>" class="hvr-bounce-to-right"><i class=" icon-login pull-right"></i> Log Out</a>
               </li>
            </ul>
         </li>
      </ul>
   </div>
</header>    