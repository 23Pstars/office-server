<?php

namespace LRS\OfficeServer\Views\Staff;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Helpers;
use LRS\OfficeServer\Controller\Outbox;
use LRS\OfficeServer\Controller\Grup;
use LRS\OfficeServer\Controller\Kontak;

use LRS\OfficeServer\Model\Grup as ModelGrup;
use LRS\OfficeServer\Model\Kontak as ModelKontak;

$aksi = isset( $_REQUEST[ Helpers::aksi_param ] ) ? $_REQUEST[ Helpers::aksi_param ] : '';
$aksi_kirim_pesan = $aksi == Helpers::aksi_kirim_pesan;

$daftar_grup = Grup::get_instance()->_gets( array( 'number' => -1 ) );

Headers::get_instance()
    ->set_page_title( 'Broadcast' )
    ->set_page_name( 'Broadcast' );

Contents::get_instance()->get_header();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3 col-sm-2 sidebar">
            <?php Contents::get_instance()->get_sidebar(); ?>
        </div>
        <div class="col-xs-9 col-sm-10 main">
            <h1 class="page-header">
                <?php echo Headers::get_instance()->get_page_name(); ?>
                <small><?php echo Headers::get_instance()->get_page_sub_name(); ?></small>
            </h1>
            <?php if( $aksi_kirim_pesan ) :
                $pesan_keluars = array();
                foreach( $_REQUEST[ 'GroupID' ] as $GroupID ) {
                    $daftar_kontak = Kontak::get_instance()->_gets( array(
                        'GroupID'   => $GroupID,
                        'number'    => -1
                    ) );
                    /** @var $kontak ModelKontak */
                    foreach( $daftar_kontak as $kontak ) {
                        $pesan_keluars[] = array(
                            $kontak->getNumber(),
                            $_REQUEST[ 'TextDecoded' ],
                            LRS_APP_AUTHOR
                        );
                    }
                }
                if( Outbox::get_instance()->_inserts( $pesan_keluars ) ) : ?>
                    <div class="alert alert-success">
                        <p><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;Pesan sedang di kirim, klik <a href="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view(); ?>/pesan-keluar">Pesan Keluar</a> untuk melihat status pesan.</p>
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger">
                        <p><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Terjadi kesalahan, harap menghubungi administrator.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <form method="post" class="form-broadcast">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Isi Pesan</label>
                        <textarea class="form-control" rows="5" name="TextDecoded"></textarea>
                    </div>
                    <input type="hidden" name="<?php echo Helpers::aksi_param; ?>" value="<?php echo Helpers::aksi_kirim_pesan ?>">
                    <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-send"></i> Kirim</button>
                </div>
                <div class="col-md-6">
                    <p><strong>Grup Tujuan</strong></p>
                    <?php /** @var $grup ModelGrup */
                    foreach( $daftar_grup as $grup ) : ?>
                        <div class="checkbox">
                            <label><input type="checkbox" name="GroupID[]" value="<?php echo $grup->getID(); ?>"> <?php echo $grup->getName(); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            $('.form-broadcast').bootstrapValidator({
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
                    },
                    'GroupID[]': {
                        validators: {
                            choice: {
                                min: 1,
                                message: 'Pilih minimal 1 grup tujuan'
                            }
                        }
                    }
                }
            });
        });
    </script>

<?php Contents::get_instance()->get_footer();