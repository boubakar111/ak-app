<?php

use App\Exception\NotFoundException;
use Routes\Router;

require '../vendor/autoload.php';

$router = new Router($_GET['url']);

$router->get('/', 'App\Controllers\AuthController@index');
$router->post('login/auth', 'App\Controllers\AuthController@login');
$router->get('logout/auth', 'App\Controllers\AuthController@logout');
$router->get('admin/dashboard', 'App\Controllers\AdminController@dashboard');

$router->get('admin/product/listeProduct', 'App\Controllers\ProductController@listProduct');
$router->get('admin/product/manage_price', 'App\Controllers\ProductController@managePrice');
$router->post('admin/product/manage_price', 'App\Controllers\ProductController@managePrice');
$router->get('admin/product/editProduct/:id', 'App\Controllers\ProductController@editProduct');
$router->post('admin/product/editProduct/:id', 'App\Controllers\ProductController@editProduct');
// fournisseurs - clients
$router->get('admin/entity/listeSupplier', 'App\Controllers\EntityController@listSupplier');
$router->get('admin/entity/manageSupplier', 'App\Controllers\EntityController@manageEntity');
$router->get('admin/entity/editSupplier/:id', 'App\Controllers\EntityController@editSupplier');
$router->post('admin/entity/editSupplier', 'App\Controllers\EntityController@editSupplier');
//category
$router->get('admin/category/listeCategory',  'App\Controllers\CategoryController@listCategory');
$router->get('admin/category/manage_price',    'App\Controllers\CategoryController@managePrice');
$router->post('/admin/category/editCategory',  'App\Controllers\CategoryController@manageCategory');
$router->get('admin/category/editCategory/:id', 'App\Controllers\CategoryController@editCategory');
$router->get('/admin/category/editCategory',  'App\Controllers\CategoryController@editCategory');
// transaction
$router->get('/admin/transaction/manageTransaction',  'App\Controllers\TransactionController@manageTransaction');

try {
    $router->run();
} catch (NotFoundException $e) {
    echo $e->getMessage();
}
