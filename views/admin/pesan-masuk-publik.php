<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Helpers;
use LRS\OfficeServer\Controller\Inbox;
use LRS\OfficeServer\Controller\Outbox;
use LRS\OfficeServer\Controller\Routes;

use LRS\OfficeServer\Model\PesanMasuk;
use LRS\OfficeServer\Model\PesanKeluar;

Headers::get_instance()
    ->set_page_title( 'Pesan Masuk ' . Inbox::public_prefix )
    ->set_page_name( 'Pesan Masuk ' . Inbox::public_prefix );

$tingkat1 = Routes::get_instance()->get_tingkat( 1 );
$tingkat2 = Routes::get_instance()->get_tingkat( 2 );

$status = false;
$is_hapus = Routes::get_instance()->is_tingkat( 3, Helpers::aksi_hapus ) && Routes::get_instance()->has_tingkat( 4 );
$is_lihat = Routes::get_instance()->is_tingkat( 3, Helpers::aksi_lihat ) && Routes::get_instance()->has_tingkat( 4 );
$is_tampilkan = Routes::get_instance()->is_tingkat( 3, Helpers::aksi_tampilkan ) && Routes::get_instance()->has_tingkat( 4 );

if( $is_hapus ) $status = Inbox::get_instance()->_delete( Routes::get_instance()->get_tingkat( 4 ) );
elseif( $is_tampilkan ) $status = Inbox::get_instance()->_set_processed( Routes::get_instance()->get_tingkat( 4 ), Inbox::processed_display );

$daftar_pesan = Inbox::get_instance()->_gets( array(
    'public_prefix'     => Inbox::public_prefix,
    'number'            => -1
) );
$daftar_pesan_count = Inbox::get_instance()->_count();

Contents::get_instance()->get_header();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3 col-sm-2 sidebar">
            <?php Contents::get_instance()->get_sidebar(); ?>
        </div>
        <div class="col-xs-9 col-sm-10 main">
            <?php if( $is_lihat ) :

                $obj_pesan = Inbox::get_instance()->_get( Routes::get_instance()->get_tingkat( 4 ) );
                if( $obj_pesan->hasProcessedFalse() ) Inbox::get_instance()->_set_processed( $obj_pesan->getID(), Inbox::processed_true ); ?>
                <h1 class="page-header">
                    Pesan Masuk
                    <small>Lihat</small>
                </h1>

                <?php $aksi = isset( $_REQUEST[ Helpers::aksi_param ] ) ? $_REQUEST[ Helpers::aksi_param ] : '';
                if( $aksi == Helpers::aksi_kirim_pesan ) :
                    $pesan_keluar = new PesanKeluar();
                    $pesan_keluar
                        ->setDestinationNumber( $_REQUEST[ 'DestinationNumber' ] )
                        ->setTextDecoded( $_REQUEST[ 'TextDecoded' ] )
                        ->setCreatorID( $obj_pesan->getID() );
                    if( Outbox::get_instance()->_insert( $pesan_keluar ) ) : ?>
                        <div class="alert alert-success">
                            <p><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;Pesan sedang di kirim, klik <a href="<?php echo LRS_URI_PATH . DS . $tingkat1; ?>/pesan-keluar">Pesan Keluar</a> untuk melihat status pesan.</p>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-danger">
                            <p><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Terjadi kesalahan, harap menghubungi administrator.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-6">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Waktu</th>
                                <td><?php echo $obj_pesan->getReceivingDateTime(); ?></td>
                            </tr>
                            <tr>
                                <th>Pengirim</th>
                                <td><?php echo $obj_pesan->getSenderNumber(); ?></td>
                            </tr>
                            <tr>
                                <th>Pesan</th>
                                <td><?php echo $obj_pesan->getAppendedTextDecoded(); ?></td>
                            </tr>
                            <tr>
                                <th>Balas</th>
                                <td>
                                    <form class="form-kirim-pesan" method="post">
                                        <input type="hidden" name="<?php echo Helpers::aksi_param; ?>" value="<?php echo Helpers::aksi_kirim_pesan ?>">
                                        <input type="hidden" name="DestinationNumber" value="<?php echo $obj_pesan->getSenderNumber(); ?>">
                                        <div class="form-group">
                                            <textarea class="form-control" name="TextDecoded" rows="5"></textarea>
                                        </div>
                                        <a href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2; ?>" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a>
                                        <button class="btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-send"></i> Kirim</button>
                                    </form>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        $('.form-kirim-pesan').bootstrapValidator({
                            message: 'Data salah',
                            feedbackIcons: {
                                valid: 'glyphicon glyphicon-ok',
                                invalid: 'glyphicon glyphicon-remove',
                                validating: 'glyphicon glyphicon-refresh'
                            },
                            fields: {
                                TextDecoded: {
                                    validators: {
                                        notEmpty: {
                                            message: 'Pesan tidak boleh kosong'
                                        },
                                        stringLength: {
                                            max: 159,
                                            message: 'Pesan tidak boleh lebih dari 160 karakter'
                                        }

                                    }
                                }
                            }
                        });
                    });
                </script>

            <?php else : ?>
                <h1 class="page-header">
                    Pesan
                    <small>Masuk</small>
                </h1>
                <?php if( $is_hapus ) :
                    if( $status ) : ?>
                        <div class="alert alert-info">
                            <p><strong><i class="glyphicon glyphicon-ok"></i> Berhasil</strong> hapus pesan.</p>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-danger">
                            <p><strong><i class="glyphicon glyphicon-remove"></i> Gagal</strong> hapus pesan.</p>
                        </div>
                    <?php endif; ?>
                <?php elseif( $is_tampilkan ) :
                    if( $status ) : ?>
                        <div class="alert alert-info">
                            <p><strong><i class="glyphicon glyphicon-ok"></i> Berhasil</strong> tampilkan pesan.</p>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-danger">
                            <p><strong><i class="glyphicon glyphicon-remove"></i> Gagal</strong> tampilkan pesan.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengirim</th>
                        <th>Pesan</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var $pesan PesanMasuk */
                    foreach( $daftar_pesan as $pesan ) : ?>
                        <tr <?php if( $pesan->hasProcessedFalse() ) : ?>class="success"<?php endif; ?>>
                            <td><?php echo $pesan->getReceivingDateTime(); ?></td>
                            <td><?php echo $pesan->hasContactName() ? $pesan->getContactName() : $pesan->getSenderNumber(); ?></td>
                            <td>
                                <?php echo $pesan->getAppendedTextDecoded( 40 );
                                if( $pesan->hasRepliedTextDecoded() ) : ?>
                                    <p class="text-success"><strong>Balasan : </strong><?php echo $pesan->getRepliedTextDecoded(); ?></p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a title="Hapus" href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . Helpers::aksi_hapus . DS . $pesan->getID(); ?>">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </a>
                                <a title="Lihat/Balas" href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . Helpers::aksi_lihat . DS . $pesan->getID(); ?>">
                                    <i class="glyphicon glyphicon-comment"></i>
                                </a>
                                <?php if( !$pesan->hasProcessedDisplay() ) : ?>
                                    <a title="Tampilkan di Web" href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . Helpers::aksi_tampilkan . DS . $pesan->getID(); ?>">
                                        <i class="glyphicon glyphicon-globe"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php Contents::get_instance()->get_footer();