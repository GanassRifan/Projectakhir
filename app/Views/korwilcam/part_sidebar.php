<?php
$db = db_connect();
$info = $db->query("select * from infosistem")->getRowArray();
$nama = $db->query("select nama from pengguna where kodepengguna = '".session()->get('high')."'")->getRowArray()['nama'];
?>
<aside>
   <div id="sidebar" class="nav-collapse md-box-shadowed">
      <div class="leftside-navigation leftside-navigation-scroll">
         <ul class="sidebar-menu" id="nav-accordion">
            <li class="sidebar-profile">
               <div class="profile-main">
                  <p class="image">
                     <img alt="image" src="<?php echo base_url('assets/gambar/default/'.$info['logo']) ?>" width="80">
                  </p>
                  <p>
                     <span class="name"><?php echo $nama ?></span><br>
                     <span class="position" style="font-family: monospace;">Korwilcam</span>
                  </p>
               </div>
            </li>
            <li class="menu"><a href="<?php echo base_url('k/') ?>" class="hvr-bounce-to-right-sidebar-parent"><span class='icon-sidebar icon-home fa-2x'></span><span>Beranda</span></a></li>

            <li class='<?php echo base_url('') ?>'><a href="<?php echo base_url('k/laporan') ?>" class="hvr-bounce-to-right-sidebar-parent"><span class='icon-sidebar icon-note fa-2x'></span><span>Laporan Bulanan</span></a></li>

         </ul>        
      </div>
   </div>
</aside>