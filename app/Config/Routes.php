<?php

use CodeIgniter\Router\RouteCollection;

/**
* @var RouteCollection $routes
*/
$routes->get('mahasiswa', 'MahasiswaController::index');                  
$routes->post('mahasiswa', 'MahasiswaController::store');               
$routes->put('mahasiswa/(:num)', 'MahasiswaController::update/$1');         
$routes->delete('mahasiswa/(:num)', 'MahasiswaController::delete/$1');   

$routes->get('dosen', 'DosenWaliController::index');                  
$routes->post('dosen', 'DosenWaliController::store');                 
$routes->put('dosen/(:num)', 'DosenWaliController::update/$1');        
$routes->delete('dosen/(:num)', 'DosenWaliController::delete/$1');   

$routes->get('notifikasi', 'NotifikasiController::index');                 
$routes->post('notifikasi', 'NotifikasiController::store');                 
$routes->put('notifikasi/(:num)', 'NotifikasiController::update/$1');         
$routes->delete('notifikasi/(:num)', 'NotifikasiController::delete/$1'); 

$routes->get('pertemuan', 'PertemuanPerwalianController::index');                   
$routes->post('pertemuan', 'PertemuanPerwalianController::store');                  
$routes->put('pertemuan/(:num)', 'PertemuanPerwalianController::update/$1');          
$routes->delete('pertemuan/(:num)', 'PertemuanPerwalianController::delete/$1');  

$routes->get('vmahasiswa', 'VMahasiswaController::index');          
$routes->post('vmahasiswa', 'VMahasiswaController::store');           
$routes->put('vmahasiswa/(:num)', 'VMahasiswaController::update/$1');  
$routes->delete('vmahasiswa/(:num)', 'VMahasiswaController::delete/$1');

$routes->get('vnotifikasi', 'VNotifikasiController::index');           
$routes->post('vnotifikasi', 'VNotifikasiController::store');           
$routes->put('vnotifikasi/(:num)', 'VNotifikasiController::update/$1');  

$routes->get('vpertemuan', 'VPertemuanPerwalianController::index');   

$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');
