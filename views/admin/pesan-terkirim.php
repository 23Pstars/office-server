<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\SentItem;
use LRS\OfficeServer\Controller\Routes;

use LRS\OfficeServer\Model\PesanTerkirim;

Headers::get_instance()
    ->set_page_title( 'Pesan Terkirim' )
    ->set_page_name( 'Pesan Terkirim' );

$tingkat1 = Routes::get_instance()->get_tingkat( 1 );

$daftar_pesan = SentItem::get_instance()->_gets( array( 'number' => -1 ) );
$daftar_pesan_count = SentItem::get_instance()->_count();

Contents::get_instance()->get_header();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3 col-sm-2 sidebar">
            <?php Contents::get_instance()->get_sidebar(); ?>
        </div>
        <div class="col-xs-9 col-sm-10 main">
            <h1 class="page-header">
                Pesan
                <small>Terkirim</small>
            </h1>
            <p>Berikut adalah daftar pesan yang telah terkirim.</p>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tujuan</th>
                    <th style="width: 600px">Pesan</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var $pesan PesanTerkirim */
                foreach( $daftar_pesan as $pesan ) : ?>
                    <tr>
                        <td><?php echo $pesan->getSendingDateTime(); ?></td>
                        <td><?php echo $pesan->hasContactName() ? $pesan->getContactName() : $pesan->getDestinationNumber(); ?></td>
                        <td><?php echo $pesan->getTextDecoded(); ?></td>
                        <td><?php echo $pesan->getStatus(); ?></td>
                    </tr>
                <?php endforeach;
                if( !$daftar_pesan_count ) : ?>
                    <tr><td colspan="4"><i>Tidak ada pesan.</i></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?php if( $daftar_pesan_count ) : ?>
                <i>Total <?php echo $daftar_pesan_count; ?> pesan</i>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php Contents::get_instance()->get_footer();