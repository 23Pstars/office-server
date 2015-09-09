<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Sessions;
use LRS\OfficeServer\Controller\Helpers;

$headers = Headers::get_instance()
    ->add_head_meta( array(
        array( 'charset' => 'utf-8' ),
        array( 'http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge' ),
        array( 'name' => 'viewport', 'content' => 'width=device-width, initial-scale=1' ),
        array( 'name' => 'description', 'content' => LRS_APP_DESCRIPTION ),
        array( 'name' => 'author', 'content' => LRS_APP_AUTHOR )
    ) )
    ->add_style( 'jquery-ui.css' )
    ->add_style( 'bootstrap.min.css' )
    ->add_style( 'bootstrapValidator.min.css' )
    ->add_style( 'font-awesome.min.css' )
    ->add_style( 'style.css' )
    ->add_head_script( 'jquery.min.js' )
    ->add_head_script( 'jquery.ui.min.js' )
    ->add_head_script( 'bootstrap.min.js' )
    ->add_head_script( 'bootstrapValidator.min.js' )
    ->add_favicon();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php echo $headers->get_head_meta(); ?>
    <?php echo $headers->get_head_title(); ?>
    <?php echo $headers->get_head_link(); ?>
    <?php echo $headers->get_head_script(); ?>
</head>

<body class="view admin">

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Ganti navigasi</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo LRS_URI_PATH; ?>"><?php echo LRS_APP_NAME; ?></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo LRS_URI_PATH; ?>"><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;Beranda</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i>&nbsp;&nbsp;Pengaturan <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo LRS_URI_PATH . DS . Sessions::get_instance()->_retrieve()->getView(); ?>"><i class="glyphicon glyphicon-lock"></i> Panel</a></li>
                        <li><a href="<?php echo LRS_URI_PATH; ?>/info/bantuan"><i class="glyphicon glyphicon-question-sign"></i> Bantuan</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo LRS_URI_PATH . DS . 'autentikasi' . DS . Helpers::aksi_keluar; ?>"><i class="glyphicon glyphicon-off"></i> Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>