<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Sessions;
use LRS\OfficeServer\Controller\Options;
use LRS\OfficeServer\Controller\Helpers;
use LRS\OfficeServer\Controller\Routes;

$status = isset( $_REQUEST[ Helpers::status_param] ) ? $_REQUEST[ Helpers::status_param ] : '';

$tingkat1 = Routes::get_instance()->get_tingkat( 1 );
$tingkat2 = Routes::get_instance()->get_tingkat( 2 );

$is_simpan = Routes::get_instance()->is_tingkat( 2, Helpers::aksi_simpan );


if( $is_simpan ) {
    Options::get_instance()->set_option( 'hari_libur', mysql_real_escape_string( json_encode( $_REQUEST[ 'hari_libur' ] ) ) );
    Options::get_instance()->set_option( 'durasi_kerja', $_REQUEST[ 'durasi_kerja' ] );
    lrs_redirect( LRS_URI_PATH . DS . $tingkat1 . DS . '?status=2' );
}

$hari_libur = json_decode( Options::get_instance()->get_option( 'hari_libur', array() ), true );
$durasi_kerja = Options::get_instance()->get_option( 'durasi_kerja', 0 );

$obj_people = Sessions::get_instance()->_retrieve()->getObjPeople();

$days_a_week_lists = array(
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
    7 => 'Sunday'
);

Headers::get_instance()
    ->set_page_title( 'Admin' )
    ->set_page_name( 'Admin' );

Contents::get_instance()->get_header();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3 col-sm-2 sidebar">
            <?php Contents::get_instance()->get_sidebar(); ?>
        </div>
        <div class="col-xs-9 col-sm-10 main">

            <h1 class="page-header">
                Admin
                <small><?php echo $obj_people->getFirstName() . ' ' . $obj_people->getLastName(); ?></small>
            </h1>

            <?php if( $status ) Helpers::get_instance()->set_message_object( 'Pengaturan' )->display_message( $status ); ?>

            <form class="form-horizontal" role="form" method="post" action="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . Helpers::aksi_simpan; ?>">
                <div class="form-group">
                    <label for="modul_aktif" class="col-sm-6 col-lg-3 control-label">Hari Libur</label>
                    <div class="col-sm-6 col-lg-3">
                        <?php foreach( $days_a_week_lists as $number => $name ) : ?>
                            <input type="checkbox" name="hari_libur[]" value="<?php echo $number; ?>" id="hari_libur_<?php echo $number; ?>" <?php if( in_array( $number, $hari_libur ) ) : ?>checked<?php endif; ?> >&nbsp;&nbsp;<label for="hari_libur_<?php echo $number; ?>"><?php echo $name; ?></label><br/>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="durasi_kerja" class="col-sm-6 col-lg-3 control-label">Durasi Kerja</label>
                    <div class="col-sm-6 col-lg-3">
                        <div class="input-group">
                            <input class="form-control" type="text" id="durasi_kerja" name="durasi_kerja" value="<?php echo $durasi_kerja; ?>">
                            <div class="input-group-addon">menit</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-6 col-lg-3 col-lg-offset-3">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<?php Contents::get_instance()->get_footer();