<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Helpers;
use LRS\OfficeServer\Controller\Routes;
use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Kontak;
use LRS\OfficeServer\Controller\Grup;
use LRS\OfficeServer\Controller\Outbox;
use LRS\OfficeServer\Model\Kontak as ModelKontak;
use LRS\OfficeServer\Model\Grup as ModelGrup;
use LRS\OfficeServer\Model\PesanKeluar;

$status = false;

$tingkat1 = Routes::get_instance()->get_tingkat( 1 );
$tingkat2 = Routes::get_instance()->get_tingkat( 2 );
$tingkat3 = Routes::get_instance()->get_tingkat( 3 );

$is_kontak_daftar = Routes::get_instance()->is_tingkat( 3, 'daftar' );
$is_kontak_grup = Routes::get_instance()->is_tingkat( 3, 'grup' );

$is_perbaiki = Routes::get_instance()->is_tingkat( 4, Helpers::aksi_perbaiki ) && Routes::get_instance()->has_tingkat( 5 );
$is_simpan = Routes::get_instance()->is_tingkat( 4, Helpers::aksi_simpan );
$is_hapus = Routes::get_instance()->is_tingkat( 4, Helpers::aksi_hapus );
$is_kirim_pesan = $is_kontak_daftar && Routes::get_instance()->is_tingkat( 4, Helpers::aksi_kirim_pesan );

$aksi = isset( $_REQUEST[ Helpers::aksi_param ] ) ? $_REQUEST[ Helpers::aksi_param ] : '';
$aksi_perbarui = $aksi == Helpers::aksi_perbarui;
$aksi_tambah = $aksi == Helpers::aksi_tambah;
$aksi_kirim_pesan = $aksi == Helpers::aksi_kirim_pesan;

$default_params = array(
    'Name'          => '',
    'GroupID'       => -1,
    'number'        => 10,
    'page'          => 1,
);
$list_params = sync_default_params( $default_params, $_GET );

Headers::get_instance()
    ->set_page_title( 'Kontak' )
    ->set_page_name( 'Kontak' )
    ->set_page_sub_name( $is_kontak_grup ? 'Grup' : 'Daftar' );
Contents::get_instance()->get_header();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3 col-sm-2 sidebar">
            <?php Contents::get_instance()->get_sidebar(); ?>
        </div>
        <div class="col-xs-9 col-sm-10 main">

            <h1 class="page-header">Kontak</h1>

            <?php if( $is_kontak_grup ) :

                $obj_grup = $is_perbaiki ? Grup::get_instance()->_get( Routes::get_instance()->get_tingkat( 5 ) ) : new ModelGrup();

                if( $is_simpan ) {
                    $obj_grup->_init( $_REQUEST );
                    if( $aksi_perbarui ) $status = Grup::get_instance()->_update( $obj_grup );
                    elseif( $aksi_tambah ) $status = Grup::get_instance()->_insert( $obj_grup );
                    if( $status ) : ?>

                        <div class="alert alert-success">
                            <p><i class="glyphicon glyphicon-ok"></i> Grup berhasil disimpan.</p>
                        </div>

                    <?php endif;

                } elseif( $is_hapus ) {
                    if( Grup::get_instance()->_delete( Routes::get_instance()->get_tingkat( 5 ) ) ) : ?>
                        <div class="alert alert-warning">
                            <p><i class="glyphicon glyphicon-ok"></i> Grup berhasil dihapus.</p>
                        </div>
                    <?php endif;
                }

                $daftar_grup = Grup::get_instance()->_gets( array( 'Name' => $list_params[ 'Name' ], 'number' => -1 ) );
                $daftar_grup_count = Grup::get_instance()->_count(); ?>

                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-4 col-md-4 col-lg-3">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-search"></i></div>
                                <input name="Name" class="form-control" type="text" value="<?php echo $list_params[ 'Name' ]; ?>" placeholder="Cari grup">
                            </div>
                        </div>
                        <div class="col-sm-2 col-lg-1">
                            <button class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-filter"></i> Filter</button>
                        </div>
                    </div>
                </form>

                <br/>

                <div class="row">

                    <!-- list beserta action bagian kiri -->
                    <div class="col-sm-8">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php /** @var $grup ModelGrup */
                            foreach( $daftar_grup as $grup ) : ?>
                                <tr>
                                    <td><?php echo $grup->getID(); ?></td>
                                    <td><?php echo $grup->getName(); ?></td>
                                    <td>
                                        <a href="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view() . DS . $tingkat2 . DS . $tingkat3 . DS . Helpers::aksi_perbaiki . DS . $grup->getID(); ?>" title="Perbaiki"><i class="glyphicon glyphicon-pencil"></i></a>
                                        <a href="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view() . DS . $tingkat2 . DS . $tingkat3 . DS . Helpers::aksi_hapus . DS . $grup->getID();; ?>" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;
                            if( !$daftar_grup_count ) : ?>
                                <tr>
                                    <td colspan="4"><i>Tidak ada data</i></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if( $daftar_grup_count ) : ?>
                            <p><i>Total <?php echo $daftar_grup_count; ?> data</i></p>
                        <?php endif; ?>
                    </div>

                    <!-- editor sebelah kanan -->
                    <div class="col-sm-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <strong><i class="glyphicon glyphicon-plus"></i> <?php if( $is_perbaiki || $is_simpan ) : ?>Perbaiki<?php else : ?>Tambah<?php endif; ?> Grup</strong>
                            </div>
                            <div class="panel-body">
                                <form class="form-horizontal form-grup" role="form" action="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view() . DS . $tingkat2 . DS . $tingkat3 . DS . Helpers::aksi_simpan; ?>" method="post">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nama</label>
                                        <div class="col-sm-8">
                                            <input name="Name" type="text" class="form-control" placeholder="Nama kontak" value="<?php echo $obj_grup->getName(); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <input type="hidden" name="<?php echo Helpers::aksi_param; ?>" value="<?php echo $is_perbaiki || $is_simpan ? Helpers::aksi_perbarui : Helpers::aksi_tambah; ?>">
                                            <input type="hidden" name="ID" value="<?php echo $obj_grup->getID(); ?>">
                                            <button class="btn btn-sm btn-primary" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
                                            <?php if( $is_perbaiki || $is_simpan ) : ?>
                                                <a class="btn btn-sm btn-primary" href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . $tingkat3; ?>"><i class="glyphicon glyphicon-plus"></i> Baru</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        $('.form-grup').bootstrapValidator({
                            message: 'Data salah',
                            feedbackIcons: {
                                valid: 'glyphicon glyphicon-ok',
                                invalid: 'glyphicon glyphicon-remove',
                                validating: 'glyphicon glyphicon-refresh'
                            },
                            fields: {
                                Name: {
                                    validators: {
                                        notEmpty: {
                                            message: 'Nama grup tidak boleh kosong'
                                        },
                                        regexp: {
                                            regexp: /^[a-zA-Z0-9\s]+$/,
                                            message: 'Hanya alphanumeric saja'
                                        }

                                    }
                                }
                            }
                        });
                    });
                </script>

            <?php elseif( $is_kontak_daftar ) :

                $obj_kontak = $is_perbaiki || $is_kirim_pesan ? Kontak::get_instance()->_get( Routes::get_instance()->get_tingkat( 5 ) ) : new ModelKontak();

                if( $is_kirim_pesan ) : ?>

                    <?php if( $aksi_kirim_pesan ) :
                        $pesan_keluar = new PesanKeluar();
                        $pesan_keluar
                            ->setDestinationNumber( $_REQUEST[ 'DestinationNumber' ] )
                            ->setTextDecoded( $_REQUEST[ 'TextDecoded' ] )
                            ->setCreatorID( LRS_APP_AUTHOR );
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
                                    <th>Nama</th>
                                    <td><?php echo $obj_kontak->getName(); ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor</th>
                                    <td><?php echo $obj_kontak->getNumber(); ?></td>
                                </tr>
                                <tr>
                                    <th>Pesan</th>
                                    <td>
                                        <form method="post" class="form-kirim-pesan">
                                            <input type="hidden" name="<?php echo Helpers::aksi_param; ?>" value="<?php echo Helpers::aksi_kirim_pesan ?>">
                                            <input type="hidden" name="DestinationNumber" value="<?php echo $obj_kontak->getNumber(); ?>">
                                            <div class="form-group">
                                                <textarea class="form-control" id="TextDecoded" name="TextDecoded" rows="5"></textarea>
                                            </div>
                                            <a href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . $tingkat3; ?>" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a>
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

                <?php else :

                    if( $is_simpan ) {
                        $obj_kontak->_init( $_REQUEST );
                        if( $aksi_perbarui ) $status = Kontak::get_instance()->_update( $obj_kontak );
                        elseif( $aksi_tambah ) $status = Kontak::get_instance()->_insert( $obj_kontak );
                        if( $status ) : ?>

                        <div class="alert alert-success">
                            <p><i class="glyphicon glyphicon-ok"></i> Kontak berhasil disimpan.</p>
                        </div>

                    <?php endif;

                    } elseif( $is_hapus ) {
                        if( Kontak::get_instance()->_delete( Routes::get_instance()->get_tingkat( 5 ) ) ) : ?>
                            <div class="alert alert-warning">
                                <p><i class="glyphicon glyphicon-ok"></i> Kontak berhasil dihapus.</p>
                            </div>
                        <?php endif;
                    }

                    $daftar_grup = Grup::get_instance()->_gets( array( 'number' => -1 ) );
                    $daftar_kontak = Kontak::get_instance()->_gets( array(
                        'Name'              => $list_params[ 'Name' ],
                        'GroupID'           => $list_params[ 'GroupID' ],
                        'number'            => $list_params[ 'number' ],
                        'offset'            => ( $list_params[ 'page' ] - 1 ) * $list_params[ 'number' ]
                    ) );
                    $daftar_kontak_count = Kontak::get_instance()->_count(); ?>

                    <form class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-4 col-lg-3">
                                <select name="GroupID" class="form-control">
                                    <option value="-1">-- pilih --</option>
                                    <?php /** @var $grup ModelGrup */
                                    foreach( $daftar_grup as $grup ) : ?>
                                        <option value="<?php echo $grup->getID(); ?>" <?php if( $list_params[ 'GroupID' ] == $grup->getID() ) : ?>selected<?php endif; ?> ><?php echo $grup->getName(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-3">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="glyphicon glyphicon-search"></i></div>
                                    <input name="Name" class="form-control" type="text" value="<?php echo $list_params[ 'Name' ]; ?>" placeholder="Cari nama">
                                </div>
                            </div>
                            <div class="col-sm-2 col-lg-1">
                                <button class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-filter"></i> Filter</button>
                            </div>
                        </div>
                    </form>

                    <br/>

                    <div class="row">

                        <!-- list beserta action bagian kiri -->
                        <div class="col-sm-8">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Nomor</th>
                                    <th>Grup</th>
                                    <th>#</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var $kontak ModelKontak */
                                foreach( $daftar_kontak as $kontak ) : ?>
                                    <tr>
                                        <td><?php echo $kontak->getID(); ?></td>
                                        <td><?php echo $kontak->getName(); ?></td>
                                        <td><?php echo $kontak->getNumber(); ?></td>
                                        <td><?php echo $kontak->getGroupName(); ?></td>
                                        <td>
                                            <a href="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view() . DS . $tingkat2 . DS . $tingkat3 . DS . Helpers::aksi_perbaiki . DS . $kontak->getID(); ?>" title="Perbaiki"><i class="glyphicon glyphicon-pencil"></i></a>
                                            <a href="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view() . DS . $tingkat2 . DS . $tingkat3 . DS . Helpers::aksi_hapus . DS . $kontak->getID();; ?>" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>
                                            <a href="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view() . DS . $tingkat2 . DS . $tingkat3 . DS . Helpers::aksi_kirim_pesan . DS . $kontak->getID();; ?>" title="Kirim pesan"><i class="glyphicon glyphicon-comment"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                                if( !$daftar_kontak_count ) : ?>
                                    <tr>
                                        <td colspan="5"><i>Tidak ada data</i></td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <?php if( $daftar_kontak_count ) : ?>
                                <p><i>Total <?php echo $daftar_kontak_count; ?> data</i></p>
                            <?php endif;
                            if( $daftar_kontak_count > $list_params[ 'number' ] ) :
                                $total_page = ceil( $daftar_kontak_count / $list_params[ 'number' ] );
                                $base_link = LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . $tingkat3 . '?';
                                $base_link .= '&GroupID=' . $list_params[ 'GroupID' ];
                                $base_link .= '&Name=' . $list_params[ 'Name' ]; ?>
                                <ul class="pagination">
                                    <li class="<?php echo $list_params[ 'page' ] == 1 ? 'disabled' : ''; ?>" >
                                        <a href="<?php echo $base_link; ?>&page=1">
                                            &laquo;
                                        </a>
                                    </li>
                                    <?php for( $i = 1; $i <= $total_page; $i++ ) : ?>
                                        <li class="<?php echo $list_params[ 'page' ] == $i ? 'active' : ''; ?>">
                                            <a href="<?php echo $base_link; ?>&page=<?php echo $i; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="arrow <?php echo $list_params[ 'page' ] == $total_page ? 'disable' : ''; ?>">
                                        <a href="<?php echo $base_link; ?>&page=<?php echo $total_page; ?>">
                                            &raquo;
                                        </a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <!-- editor sebelah kanan -->
                        <div class="col-sm-4">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <strong><i class="glyphicon glyphicon-plus"></i> <?php if( $is_perbaiki || $is_simpan ) : ?>Perbaiki<?php else : ?>Tambah<?php endif; ?> Kontak</strong>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal form-kontak" role="form" action="<?php echo LRS_URI_PATH . DS . Contents::get_instance()->get_view() . DS . $tingkat2 . DS . $tingkat3 . DS . Helpers::aksi_simpan; ?>" method="post">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Grup</label>
                                            <div class="col-sm-8">
                                                <select name="GroupID" class="form-control">
                                                    <option value>-- pilih --</option>
                                                    <?php /** @var $grup ModelGrup */
                                                    foreach( $daftar_grup as $grup ) : ?>
                                                        <option value="<?php echo $grup->getID(); ?>" <?php if( $obj_kontak->getGroupID() == $grup->getID() ) : ?>selected<?php endif; ?> ><?php echo $grup->getName(); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nama</label>
                                            <div class="col-sm-8">
                                                <input name="Name" type="text" class="form-control" placeholder="Nama kontak" value="<?php echo $obj_kontak->getName(); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nomor</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon">+</div>
                                                    <input name="Number" class="form-control" type="text" placeholder="62..." value="<?php echo $obj_kontak->getNumber(); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">

                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-8 col-sm-offset-4">
                                                <input type="hidden" name="<?php echo Helpers::aksi_param; ?>" value="<?php echo $is_perbaiki || $is_simpan ? Helpers::aksi_perbarui : Helpers::aksi_tambah; ?>">
                                                <input type="hidden" name="ID" value="<?php echo $obj_kontak->getID(); ?>">
                                                <button class="btn btn-sm btn-primary" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</button>
                                                <?php if( $is_perbaiki || $is_simpan ) : ?>
                                                    <a class="btn btn-sm btn-primary" href="<?php echo LRS_URI_PATH . DS . $tingkat1 . DS . $tingkat2 . DS . $tingkat3; ?>"><i class="glyphicon glyphicon-plus"></i> Baru</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <script>
                    $(document).ready(function() {
                        $('.form-kontak').bootstrapValidator({
                            message: 'Data salah',
                            feedbackIcons: {
                                valid: 'glyphicon glyphicon-ok',
                                invalid: 'glyphicon glyphicon-remove',
                                validating: 'glyphicon glyphicon-refresh'
                            },
                            fields: {
                                Name: {
                                    validators: {
                                        notEmpty: {
                                            message: 'Nama tidak boleh kosong'
                                        },
                                        regexp: {
                                            regexp: /^[a-zA-Z0-9\s]+$/,
                                            message: 'Hanya alphanumeric saja'
                                        }

                                    }
                                },
                                'Number': {
                                    validators: {
                                        notEmpty: {
                                            message: 'Nomor tidak boleh kosong'
                                        },
                                        regexp: {
                                            regexp: /^[0-9]+$/,
                                            message: 'Hanya angka saja, format lengkap dimulai kode negara'
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

                <?php endif;
            endif; ?>
        </div>
    </div>
</div>

<?php Contents::get_instance()->get_footer();