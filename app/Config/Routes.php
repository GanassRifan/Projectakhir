<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Admin');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->add('/', 'Root::index');
$routes->add('/proseslogin', 'Root::login');
$routes->add('/proseslogout', 'Root::logout');

// ADMINISTRATOR =======================================
$routes->add('/a', 'Admin_root::index');
$routes->add('/a/infosistem', 'Admin_root::tampilinfo');
$routes->add('/a/infosistem/ubah', 'Admin_root::ubahinfo');
$routes->add('/a/pengguna', 'Admin_root::tampilpengguna');
$routes->add('/a/pengguna/ubah', 'Admin_root::ubahpengguna');
$routes->add('/a/akses', 'Admin_root::tampilakses');
$routes->add('/a/akses/ubah', 'Admin_root::ubahakses');
$routes->add('/a/pin', 'Admin_root::tampilpin');
$routes->add('/a/pin/ubah', 'Admin_root::ubahpin');

$routes->add('/a/korwilcam', 'Admin_korwilcam::index');
$routes->add('/a/korwilcam/simpan', 'Admin_korwilcam::simpan');
$routes->add('/a/korwilcam/ubah', 'Admin_korwilcam::ubah');
$routes->add('/a/korwilcam/hapus/(:any)', 'Admin_korwilcam::hapus/$1');

$routes->add('/a/sekolah', 'Admin_sekolah::index');
$routes->add('/a/sekolah/simpan', 'Admin_sekolah::simpan');
$routes->add('/a/sekolah/ubah', 'Admin_sekolah::ubah');
$routes->add('/a/sekolah/hapus/(:any)', 'Admin_sekolah::hapus/$1');

$routes->add('/a/aspek', 'Admin_aspek::index');
$routes->add('/a/aspek/tambah', 'Admin_aspek::tambah');
$routes->add('/a/aspek/tambahdetail', 'Admin_aspek::tambahdetail');
$routes->add('/a/aspek/hapusdetail/(:any)/(:any)/(:any)', 'Admin_aspek::hapusdetail/$1/$2/$3');
$routes->add('/a/aspek/simpan', 'Admin_aspek::simpan');
$routes->add('/a/aspek/simpanpilihan', 'Admin_aspek::simpanpilihan');
$routes->add('/a/aspek/detail/(:any)', 'Admin_aspek::detail/$1');
$routes->add('/a/aspek/ubah', 'Admin_aspek::ubah');
$routes->add('/a/aspek/ubahpilihan', 'Admin_aspek::ubahpilihan');
$routes->add('/a/aspek/tambahpilihan', 'Admin_aspek::tambahpilihan');
$routes->add('/a/aspek/hapuspilihan/(:any)', 'Admin_aspek::hapuspilihan/$1');
$routes->add('/a/aspek/hapus/(:any)', 'Admin_aspek::hapus/$1');

$routes->add('/a/skema', 'Admin_skema::index');
$routes->add('/a/skema/tampil', 'Admin_skema::tampil');
$routes->add('/a/skema/simpan', 'Admin_skema::simpan');
$routes->add('/a/skema/buka/(:any)', 'Admin_skema::buka/$1');
$routes->add('/a/skema/verifikasi/(:any)', 'Admin_skema::verifikasi/$1');
$routes->add('/a/skema/kunci/(:any)', 'Admin_skema::kunci/$1');
$routes->add('/a/skema/bukakunci', 'Admin_skema::bukakunci');

$routes->add('/a/laporan', 'Admin_laporan::index');
$routes->add('/a/laporan/tampil', 'Admin_laporan::tampil');
$routes->add('/a/laporan/detail/(:any)', 'Admin_laporan::detail/$1');
$routes->add('/a/laporan/tolak', 'Admin_laporan::tolak');
$routes->add('/a/laporan/terima/(:any)', 'Admin_laporan::terima/$1');
$routes->add('/a/laporan/cetak', 'Admin_laporan::cetak');

// KORWILCAM =======================================
$routes->add('/k', 'Korwilcam_root::index');
$routes->add('/k/pengguna', 'Korwilcam_root::tampilpengguna');
$routes->add('/k/pengguna/ubah', 'Korwilcam_root::ubahpengguna');
$routes->add('/k/akses', 'Korwilcam_root::tampilakses');
$routes->add('/k/akses/ubah', 'Korwilcam_root::ubahakses');

$routes->add('/k/laporan', 'Korwilcam_laporan::index');
$routes->add('/k/laporan/tampil', 'Korwilcam_laporan::tampil');
$routes->add('/k/laporan/detail/(:any)', 'Korwilcam_laporan::detail/$1');
$routes->add('/k/laporan/tolak', 'Korwilcam_laporan::tolak');
$routes->add('/k/laporan/terima/(:any)', 'Korwilcam_laporan::terima/$1');
$routes->add('/k/laporan/cetak', 'Korwilcam_laporan::cetak');

// KEPSEK =======================================
$routes->add('/s', 'Kepsek_root::index');
$routes->add('/s/pengguna', 'Kepsek_root::tampilpengguna');
$routes->add('/s/pengguna/ubah', 'Kepsek_root::ubahpengguna');
$routes->add('/s/akses', 'Kepsek_root::tampilakses');
$routes->add('/s/akses/ubah', 'Kepsek_root::ubahakses');

$routes->add('/s/operator', 'Kepsek_operator::index');
$routes->add('/s/operator/simpan', 'Kepsek_operator::simpan');
$routes->add('/s/operator/ubah', 'Kepsek_operator::ubah');
$routes->add('/s/operator/hapus/(:any)', 'Kepsek_operator::hapus/$1');

$routes->add('/s/laporan', 'Kepsek_laporan::index');
$routes->add('/s/laporan/tampil', 'Kepsek_laporan::tampil');
$routes->add('/s/laporan/detail/(:any)', 'Kepsek_laporan::detail/$1');
$routes->add('/s/laporan/tolak', 'Kepsek_laporan::tolak');
$routes->add('/s/laporan/terima/(:any)', 'Kepsek_laporan::terima/$1');
$routes->add('/s/laporan/cetak', 'Kepsek_laporan::cetak');

// OPERATOR =======================================
$routes->add('/o', 'Operator_root::index');
$routes->add('/o/pengguna', 'Operator_root::tampilpengguna');
$routes->add('/o/pengguna/ubah', 'Operator_root::ubahpengguna');
$routes->add('/o/akses', 'Operator_root::tampilakses');
$routes->add('/o/akses/ubah', 'Operator_root::ubahakses');

$routes->add('/o/rombel', 'Operator_rombel::index');
$routes->add('/o/rombel/tampil', 'Operator_rombel::tampil');
$routes->add('/o/rombel/simpan', 'Operator_rombel::simpan');
$routes->add('/o/rombel/ubah', 'Operator_rombel::ubah');
$routes->add('/o/rombel/hapus/(:any)', 'Operator_rombel::hapus/$1');

$routes->add('/o/laporan', 'Operator_laporan::index');
$routes->add('/o/laporan/tampil', 'Operator_laporan::tampil');
$routes->add('/o/laporan/detail/(:any)', 'Operator_laporan::detail/$1');
$routes->add('/o/laporan/simpan', 'Operator_laporan::simpan');
$routes->add('/o/laporan/ubah', 'Operator_laporan::ubah');
$routes->add('/o/laporan/ubahumum', 'Operator_laporan::ubahumum');
$routes->add('/o/laporan/ubahsiswa', 'Operator_laporan::ubahsiswa');
$routes->add('/o/laporan/ubahagama', 'Operator_laporan::ubahagama');
$routes->add('/o/laporan/tambahptk', 'Operator_laporan::tambahptk');
$routes->add('/o/laporan/tambahberkas', 'Operator_laporan::tambahberkas');
$routes->add('/o/laporan/kirim', 'Operator_laporan::kirim');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
