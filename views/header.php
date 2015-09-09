<?php

namespace LRS\OfficeServer\Views\Publik;

use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Helpers;

$headers = Headers::get_instance()
    ->add_head_meta( array(
        array( 'charset' => 'utf-8' ),
        array( 'http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge' ),
        array( 'name' => 'viewport', 'content' => 'width=device-width, initial-scale=1' ),
        array( 'name' => 'description', 'content' => LRS_APP_DESCRIPTION ),
        array( 'name' => 'author', 'content' => LRS_APP_AUTHOR )
    ) )
    ->add_style( 'bootstrap.min.css' )
    ->add_style( 'bootstrap-theme.min.css' )
    ->add_style( 'font-awesome.min.css' )
    ->add_style( 'style.css' )
    ->add_favicon();

?>
<!-- Dikembangkan oleh <?php echo LRS_APP_DEVELOPER; ?> -->

<!DOCTYPE html>
<html lang="id">
<head>
    <?php echo $headers->get_head_meta(); ?>
    <?php echo $headers->get_head_title(); ?>
    <?php echo $headers->get_head_link(); ?>
    <?php echo $headers->get_head_script(); ?>
</head>

<body class="publik">

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
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
                <li><a href="<?php echo LRS_URI_PATH; ?>"><i class="glyphicon glyphicon-home"></i> Beranda</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> Masuk <b class="caret"></b></a>
                    <ul class="dropdown-menu form-masuk">
                        <li>
                            <div class="col-xs-12">
                                <form role="form" action="<?php echo LRS_URI_PATH . DS . 'autentikasi' . DS . Helpers::aksi_masuk; ?>" method="post">
                                    <div class="form-group">
                                        <label>
                                            <input type="text" class="form-control" name="username" placeholder="ID Pengguna">
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="password" class="form-control" name="password" placeholder="Kata Kunci">
                                        </label>
                                    </div>
                                    <input type="hidden" name="redirect_to" value="<?php echo isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : LRS_URI_PATH; ?>">
                                    <button type="submit" class="btn btn-primary">Masuk</button>
                                    <span class="pull-right">
                                        <a href="<?php echo LRS_URI_PATH; ?>/info/bantuan/autentikasi">butuh bantuan?</a><br/>
                                        <a href="<?php echo LRS_URI_PATH . DS . 'autentikasi' . DS . Helpers::aksi_lupa_kata_sandi; ?>">lupa kata sandi?</a>
                                    </span>
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!--/.navbar-collapse -->
    </div>
</div>
