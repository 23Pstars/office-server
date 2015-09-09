<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Absents;
use LRS\OfficeServer\Controller\Options;
use LRS\OfficeServer\Controller\Routes;
use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Sessions;

use LRS\OfficeServer\Model\Absent;

Sessions::get_instance()->_validate( LRS_URI_PATH . '?redirect_to=' . $_SERVER[ 'REQUEST_URI' ] );

$tingkat1 = Routes::get_instance()->get_tingkat( 1 );
$tingkat2 = Routes::get_instance()->get_tingkat( 2 );
$tingkat3 = Routes::get_instance()->get_tingkat( 3 );

$is_absen_histori = Routes::get_instance()->is_tingkat( 3, 'histori' );
$is_absen_mulai = Routes::get_instance()->is_tingkat( 3, Absents::aksi_mulai );
$is_absen_berhenti = Routes::get_instance()->is_tingkat( 3, Absents::aksi_berhenti );
$is_absen_berhenti_sebelum_waktunya = Routes::get_instance()->is_tingkat( 3, Absents::aksi_berhenti_sebelum_waktunya );

$durasi_kerja = Options::get_instance()->get_option( 'durasi_kerja', 0 );
$durasi_kerja_jam = (int)$durasi_kerja / 60;

$obj_people = Sessions::get_instance()->_retrieve()->getObjPeople();

$this_day = date( 'Y-m-d' );

$obj_absen = Absents::get_instance()->_get( $this_day, $obj_people->getId() );
!is_null( $obj_absen->getStatus() ) || $obj_absen->setStatus( Absents::status_tidak_masuk );

if( $is_absen_mulai ) {
    $obj_absen
        ->setDate( $this_day )
        ->setPeopleId( $obj_people->getId() )
        ->setWorktimeStart( date( 'H:i:s' ) )
        ->setStatus( Absents::status_masuk );
    lrs_redirect(
        LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . '?status=' .
        ( Absents::get_instance()->_update( $obj_absen ) ? 1 : 999 )
    );
} elseif( $is_absen_berhenti ) {
    $obj_absen
        ->setWorktimeEnd( date( 'H:i:s' ) )
        ->setStatus( Absents::status_selesai );
    lrs_redirect(
        LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . '?status=' .
        ( Absents::get_instance()->_update( $obj_absen ) ? 2 : 999 )
    );
} elseif( $is_absen_berhenti_sebelum_waktunya ) {
    $obj_absen
        ->setWorktimeEnd( date( 'H:i:s' ) )
        ->setStatus( Absents::status_izin_pulang )
        ->setNote( $_REQUEST[ 'note' ] );
    lrs_redirect(
        LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . '?status=' .
        ( Absents::get_instance()->_update( $obj_absen ) ? 3 : 999 )
    );
}

$default_params = array(
    'range_date_start'      => '',
    'range_date_end'        => '',
    'number'                => 10,
    'page'                  => 1,
);
$list_params = sync_default_params( $default_params, $_GET );

$hari_libur = json_decode( Options::get_instance()->get_option( 'hari_libur', array() ), true );

Headers::get_instance()
    ->set_page_title( 'Absen' )
    ->set_page_name( 'Absen' )
    ->set_page_sub_name( $is_absen_histori ? 'Histori' : 'Absen' );
Contents::get_instance()->get_header();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3 col-sm-2 sidebar">
            <?php Contents::get_instance()->get_sidebar(); ?>
        </div>
        <div class="col-xs-9 col-sm-10 main">

            <?php if( $is_absen_histori ) : ?>

                <h1 class="page-header">
                    Absen
                    <small>Histori</small>
                </h1>

                <?php $daftar_absen_histori = Absents::get_instance()->_gets( array(
                    'range_date_start'  => $list_params[ 'range_date_start' ],
                    'range_date_end'    => $list_params[ 'range_date_end' ],
                    'people_id'         => -1,
                    'number'            => $list_params[ 'number' ],
                    'offset'            => ( $list_params[ 'page' ] - 1 ) * $list_params[ 'number' ]
                ) );
                $daftar_absen_histori_count = Absents::get_instance()->_count(); ?>

                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-4 col-lg-3">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                                <input name="range_date_start" class="form-control" type="text" value="<?php echo $list_params[ 'range_date_start' ]; ?>" placeholder="Semua">
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                                <input name="range_date_end" class="form-control" type="text" value="<?php echo $list_params[ 'range_date_end' ]; ?>" placeholder="Semua">
                            </div>
                        </div>
                        <div class="col-sm-2 col-lg-1">
                            <button class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-filter"></i> Filter</button>
                        </div>
                    </div>
                </form>

                <br/>

                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var $absen Absent */
                    foreach( $daftar_absen_histori as $absen ) :
                        if( in_array( $absen->getDate( 'N' ), $hari_libur ) ) : ?>
                            <tr>
                                <td colspan="5"><span class="text-danger">Libur</span></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td><?php echo $absen->getFirstName(); ?> <?php echo $absen->getLastName(); ?></td>
                                <td><?php echo $absen->getDate() ?></td>
                                <td><?php echo $absen->getStatusKerja(); ?></td>
                                <td><?php echo $absen->getWorktimeStart(); ?></td>
                                <td><?php echo $absen->getWorktimeEnd(); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php $base_link = LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . $tingkat3 . '?';
                $base_link .= '&range_date_start=' . $list_params[ 'range_date_start' ];
                $base_link .= '&range_date_end=' . $list_params[ 'range_date_end' ];
                lrs_paging_nav( $base_link, $daftar_absen_histori_count, $list_params[ 'page' ], 20 );
            else : ?>

                <h1 class="page-header">
                    Absen
                    <small>for <?php echo $obj_absen->getDate( 'D M jS, Y' ); ?></small>
                </h1>

                <div class="row">
                    <div class="col-sm-8">

                        <?php if( isset( $_REQUEST[ 'status' ] ) ) :
                            $status = $_REQUEST[ 'status' ]; ?>
                            <div class="alert alert-info">
                                <p>
                                    <?php switch( $status ) {
                                        case 1 : echo 'Selamat bekerja!'; break;
                                        case 2 : echo 'Kerja bagus!'; break;
                                        case 3 : echo 'Tidak masalah, masih ada waktu besok.'; break;
                                        default : echo 'Error.... harap segera lapor!';
                                    } ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <table class="table">
                            <tbody>
                            <tr>
                                <td><strong>Durasi Kerja</strong></td>
                                <td><span class="jam_kerja"><?php echo $durasi_kerja_jam; ?> Jam</span></td>
                            </tr>
                            <?php if( $obj_absen->isStatusTidakMasuk() ) : ?>
                                <tr>
                                    <td><strong>Waktu Sekarang</strong></td>
                                    <td><span class="waktu_mulai"></span></td>
                                </tr>
                            <?php elseif( $obj_absen->isStatusMasuk() ) : ?>
                                <tr>
                                    <td><strong>Waktu Mulai</strong></td>
                                    <td><?php echo $obj_absen->getWorktimeStart(); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Selesai</strong></td>
                                    <td>
                                        <span class="waktu_selesai"><?php echo $obj_absen->getWorktimeLimit( $durasi_kerja ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sisa Waktu</strong></td>
                                    <td>
                                        <span class="sisa_waktu_show"></span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr><td></td><td></td></tr>
                            </tbody>
                        </table>
                        <p class="text-info">
                            Klik tombol <strong>Mulai</strong> untuk memulai waktu bekerja, jika sisa waktu sudah menunjukkan <strong>00:00:00</strong> baru menekan tombol <strong>Berhenti</strong>.
                            Kondisi lain adalah jika ada suatu hal yang memaksa untuk berhenti lebih awal, tidak masalah menekan tombol <strong>Berhenti</strong> dengan catatan harus mengisi kotak <strong>Keterangan</strong> yang muncul dibawahnya.
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <strong>Aksi</strong>
                            </div>
                            <div class="panel-body">
                                <a href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . Absents::aksi_mulai; ?>" class="btn btn-primary <?php echo $obj_absen->isStatusTidakMasuk() ? '' : 'disabled'; ?>"><i class="fa fa-play"></i> Mulai</a>
                                <a href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . Absents::aksi_berhenti; ?>" class="btn btn-berhenti btn-danger <?php echo $obj_absen->isStatusMasuk() ? '' : 'disabled'; ?>"><i class="fa fa-stop"></i> Berhenti</a>
                                <div class="note" style="display: none">
                                    <hr>
                                    <form action="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . Absents::aksi_berhenti_sebelum_waktunya; ?>" method="post">
                                        <p><em>Side berhenti sebelum jam kerja berakhir, untuk itu silakan mengisi form keterangan berikut:</em></p>
                                        <label for="note">Keterangan</label>
                                        <textarea id="note" class="form-control" name="note"></textarea>
                                        <br/>
                                        <button class="btn btn-sm btn-success"><i class="fa fa-send"></i> Kirim</button>
                                        <span class="pull-right">
                                            <a href="#tutup" class="note-close"><i class="fa fa-angle-double-up"></i> tutup</a>
                                        </span>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(function() {

                        <?php if( $obj_absen->isStatusMasuk() ) : ?>

                            /** sisa waktu **/
                            $('.sisa_waktu_show').countdown({
                                date: '<?php echo $obj_absen->getWorktimeLimit( $durasi_kerja, 'Y-m-d H:i:s' ); ?>',
                                render: function(data) {
                                    var el = $(this.el);
                                    el.empty()
                                        .append(this.leadingZeros(data.hours, 2) + ":")
                                        .append(this.leadingZeros(data.min, 2) + ":")
                                        .append(this.leadingZeros(data.sec, 2));
                                }
                            });

                            $('.btn-berhenti').click(function(){
                                if($('.sisa_waktu_show').html() !== '00:00:00') {
                                    $('.note').slideDown();
                                    $('.note-close').click(function(){$('.note').slideUp(); return false;});
                                    return false;
                                } else return true;
                            });

                        <?php elseif( $obj_absen->isStatusTidakMasuk() ) : ?>

                            /** waktu mulai **/
                            setInterval( function(){
                                var currentTime = new Date ( );
                                var currentHours = currentTime.getHours ( );
                                var currentMinutes = currentTime.getMinutes ( );
                                var currentSeconds = currentTime.getSeconds ( );
                                currentHours = ( currentHours < 10 ? "0" : "" ) + currentHours;
                                currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
                                currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
                                $(".waktu_mulai").html(currentHours + ":" + currentMinutes + ":" + currentSeconds);
                            }, 1000 );

                        <?php endif; ?>
                    });
                </script>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php Contents::get_instance()->get_footer();