<?php
$db = db_connect();
$info = $db->query("select * from infosistem")->getRowArray();
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
                     <span class="name">Nama Admin</span><br>
                     <span class="position" style="font-family: monospace;">Administrator</span>
                  </p>
               </div>
            </li>
            <li class="menu"><a href="<?php echo base_url('a/') ?>" class="hvr-bounce-to-right-sidebar-parent"><span class='icon-sidebar icon-home fa-2x'></span><span>Beranda</span></a></li>

            <li class="sub-menu"><a href="1" class="hvr-bounce-to-right-sidebar-parent"><span class='icon-sidebar pe-7s-portfolio fa-2x'></span><span>Data Master</span></a>
               <ul class='sub'>
                  <li><a href="<?php echo base_url('a/korwilcam') ?>">Korwilcam</a></li>
                  <li><a href="<?php echo base_url('a/sekolah') ?>">Sekolah</a></li>
               </ul>
            </li>

            <li class="sub-menu"><a href="1" class="hvr-bounce-to-right-sidebar-parent"><span class='icon-sidebar pe-7s-display2 fa-2x'></span><span>Aspek Pelaporan</span></a>
               <ul class='sub'>
                  <li><a href="<?php echo base_url('a/aspek') ?>">Aspek</a></li>
                  <li><a href="<?php echo base_url('a/skema') ?>">Skema</a></li>
               </ul>
            </li>

            <li class='<?php echo base_url('') ?>'><a href="<?php echo base_url('a/laporan') ?>" class="hvr-bounce-to-right-sidebar-parent"><span class='icon-sidebar icon-note fa-2x'></span><span>Laporan Bulanan</span></a></li>

         </ul>        
      </div>
   </div>
</aside>