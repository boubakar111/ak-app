<?php

session_start();
error_reporting(0);

 if ( !isset($_SESSION['firstname']) || ($_SESSION['uType'] !== 1 && $_SESSION['uType'] !== 2)) {
    header('location:' . ADMIN_LOGIN_URL);
    exit();
} else { ?>

<!-- Top Bar Start -->
 <?php  include_once(__DIR__ . '/../../inc/topBarNav.php');?>
<!-- ========== Left Sidebar Start ========== -->
<?php   include_once(__DIR__ . '/../../inc/navigation.php');?>
<h2>Bienvenue dans le Panneau d'administration</h2>
<hr class="border-purple">
<style>
    #website-cover{
        width:100%;
        height:30em;
        object-fit:cover;
        object-position:center center;
    }
</style>
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-purple elevation-1"><i class="fas fa-th-list"></i></span>
            <div class="info-box-content">
            <span class="info-box-text">Total Categories</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $params['totalCat']["total_categories"];
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-secondary elevation-1"><i class="fas fa-folder"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Transactions en attente</span>
            <span class="info-box-number text-right">
                <?php 
                echo ($params['trans']['transactions']) ;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-folder"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Transactions en cours</span>
            <span class="info-box-number text-right">
                <?php 
                 //   echo $conn->query("SELECT * FROM `transaction_list` where `status` = 1 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-teal elevation-1"><i class="fas fa-folder"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Opérations terminées</span>
            <span class="info-box-number text-right">
                <?php 
                //    echo $conn->query("SELECT * FROM `transaction_list` where `status` = 2 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-maroon elevation-1"><i class="fas fa-coins"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Paiements d'aujourd'hui</span>
            <span class="info-box-number text-right">
               
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
      <img src="<?= base_url.'uploads/cover-1708417453.png' ?>" alt="Website Cover" class="img-fluid border w-100" id="website-cover">
    </div>
</div>

<?php } ?>