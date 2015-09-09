<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Outbox;
use LRS\OfficeServer\Controller\Routes;

use LRS\OfficeServer\Model\PesanKeluar;

Headers::get_instance()
    ->set_page_title( 'Pesan Keluar' )
    ->set_page_name( 'Pesan Keluar' );

$tingkat1 = Routes::get_instance()->get_tingkat( 1 );

$daftar_pesan = Outbox::get_instance()->_gets( array( 'number' => -1 ) );
$daftar_pesan_count = Outbox::get_instance()->_count();

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
                <small>Keluar</small>
            </h1>
            <p>Berikut adalah daftar pesan yang telah disubmit namun masih dalam proses pengiriman, klik <a href="<?php echo LRS_URI_PATH . DS . $tingkat1; ?>/pesan-terkirim">Pesan Terkirim</a> untuk melihat pesan yang sudah terkirim.</p>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tujuan</th>
                    <th style="width: 600px">Pesan</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var $pesan PesanKeluar */
                foreach( $daftar_pesan as $pesan ) : ?>
                    <tr>
                        <td><?php echo $pesan->getSendingDateTime(); ?></td>
                        <td><?php echo $pesan->hasContactName() ? $pesan->getContactName() : $pesan->getDestinationNumber(); ?></td>
                        <td><?php echo $pesan->getTextDecoded(); ?></td>
                    </tr>
                <?php endforeach;
                if( !$daftar_pesan_count ) : ?>
                    <tr><td colspan="3"><i>Tidak ada pesan.</i></td></tr>
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