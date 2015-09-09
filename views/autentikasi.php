<?php
/**
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Views\Publik;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;

use LRS\OfficeServer\Controller\Authentications;
use LRS\OfficeServer\Controller\Routes;
use LRS\OfficeServer\Controller\Helpers;
use LRS\OfficeServer\Controller\Sessions;

Headers::get_instance()
    ->set_page_title( 'Autentikasi' );

$is_lupa_kata_sandi = Routes::get_instance()->is_tingkat( 2, Helpers::aksi_lupa_kata_sandi );
$is_masuk = Routes::get_instance()->is_tingkat( 2, Helpers::aksi_masuk );
$is_keluar = Routes::get_instance()->is_tingkat( 2, Helpers::aksi_keluar );

$auth_status = 2;
$is_masuk_success = false;

if( $is_masuk ) {
    $username = isset( $_REQUEST[ 'username' ] ) ? $_REQUEST[ 'username' ] : '';
    $password = isset( $_REQUEST[ 'password' ] ) ? $_REQUEST[ 'password' ] : '';
    $is_masuk_success = Authentications::success == ( $auth_status = Authentications::get_instance()->_init( $username, $password )->_auth() );

    /**
     * jika target redirect nya ditentukan
     */
    $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ?

        /**
         * jika tidak, cek status masuknya apakah berhasil atau gagal
         */
        $_REQUEST[ 'redirect_to' ] : ( $is_masuk_success ?

            /**
             * jika berhasil, arahkan ke panel nya masing-masing,
             * jika gagal, buang ke halaman depan
             */
            LRS_URI_PATH . DS . Sessions::get_instance()->_retrieve()->getView() : LRS_URI_PATH
        );

    /**
     * sisipkan di header nya
     */
    Headers::get_instance()->add_head_meta( array(
        array( 'http-equiv' => 'refresh', 'content' => '0;url=' . $redirect_to )
    ) );
}

elseif( $is_keluar ) {

    /**
     * bersihkan Session nya
     */
    Sessions::get_instance()->_destroy();

    /**
     * periksa tujuan redirect
     */
    $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : LRS_URI_PATH;

    /**
     * sisipkan di headernya
     */
    Headers::get_instance()->add_head_meta( array(
        array( 'http-equiv' => 'refresh', 'content' => '0;url=' . $redirect_to )
    ) );
}

Contents::get_instance()->get_header( Sessions::get_instance()->_has() ? 'loggedin' : '' );

?>

    <div class="container autentikasi">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <?php if( $is_masuk ) :
                    if( $is_masuk_success ) : ?>
                        <div class="alert alert-success">
                            <p><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo Authentications::$error_messages[ $auth_status ]; ?>, beberapa saat lagi akan diarahkan ke halaman utama.</p>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-danger">
                            <p><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo Authentications::$error_messages[ $auth_status ]; ?>.</p>
                        </div>
                    <?php endif; ?>

                <?php

                /**
                 * @todo
                 * masih belum ada putusan pasti, bagaimana mekanisme reset password,
                 * sementara force semua permintaan reset password harus datang ke operator
                 * masing-masing prodi
                 */

                elseif( $is_lupa_kata_sandi ) : ?>

                    <h1>Autentikasi</h1>
                    <p>Untuk permintaan <em>Reset Password</em> silakan mengirim email ke <a href="mailto:hrd@lrsoft.org">hrd@lrsoft.org</a>.</p>

                <?php elseif( $is_keluar ) : ?>
                    <div class="alert alert-warning">
                        <p><i class="fa fa-warning"></i> Anda berhasil keluar, beberapa saat lagi anda akan diarahkan ke beranda.</p>
                    </div>
                    <a href="<?php echo LRS_URI_PATH; ?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-home"></i> Beranda</a>
                <?php else : ?>

                    <div class="alert alert-warning">
                        <p><i class="glyphicon glyphicon-remove"></i> <strong>Dasar Jones... </strong>nyari pacar sana, jangan nyari halaman macam-macam...</p>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>

<?php Contents::get_instance()->get_footer();