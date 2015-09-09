<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */


/**
 * mengakhiri eksekusi script/halaman dengan tambahan
 * opsi tampilan
 *
 * @param string $messages
 * @param string $type
 */
function lrs_exit( $messages = '', $type = 'info' ) {

    $out = '';

    if( !empty( $messages ) ) {

        ob_start(); ?>

        <html>
        <head>
            <title>SIAKAD Universitas Mataram</title>
            <style>
                .container {
                    margin: 20px auto;
                    text-align: center;
                }
                .message {
                    color: #fff;
                    display: inline;
                    padding: 10px;
                    font-size: 11px;
                    font-family: sans-serif;
                    -moz-border-radius: 3px;
                    border-radius: 3px;
                    background-color: #008cba;
                }
                .message.info {
                    background-color: #43ac6a;
                }
                .message.alert {
                    background-color: #f04124;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <p class="message <?php echo $type; ?>"><?php echo $messages; ?></p>
        </div>
        </body>
        </html>

        <?php $out = ob_get_clean();

    }

    exit( $out );
}

/**
 * berfungsi untuk me-redirect ke halaman tertentu,
 * dengan mengeksekusi exit() di akhir untuk memastikan
 * tidak ada program yang berjalan selanjutnya
 *
 * @param string $target
 */
function lrs_redirect( $target = LRS_URI_PATH ) {
    header( 'Location: ' . $target );
    lrs_exit();
}

/**
 * @param $default
 * @param $destination
 * @return array
 */
function sync_default_params( $default, $destination ) {
    $return = array();
    foreach( $default as $k => $d ) {
        if( isset( $destination[ $k ] ) ) $return[ $k ] = $destination[ $k ];
        else $return[ $k ] = $d;
    }
    return $return;
}


/**
 * @param $base_url
 * @param $total_data
 * @param $current_page
 * @param int $data_per_page
 * @param int $range_data
 */
function lrs_paging_nav( $base_url, $total_data, $current_page, $data_per_page = 10, $range_data = 3 ) {

    /** cek apakah memungkinkan untuk paging */
    if( $total_data > $data_per_page ) :
        $total_page = ceil( $total_data / $data_per_page );

        /** batas minimum */
        $ii = ( $ii = $current_page - $range_data ) < 1 ? 1 : $ii;

        /** batas maksimum */
        $iii = ( $iii = $current_page + $range_data ) > $total_page ? $total_page : $iii; ?>

        <div class="pull-right">
            <ul class="pagination">

                <?php /** tampilkan left arrow */
                if( $current_page == 1 ) : ?>
                    <li class="disabled">
                        <span>&laquo;</span>
                    </li>
                <?php else : ?>
                    <li>
                        <a href="<?php echo $base_url; ?>&page=1">
                            &laquo;
                        </a>
                    </li>
                <?php endif; ?>

                <?php /** jika tidak mepet dengan nilai minimum, tampilkan titik-titik */
                if( $ii != 1 ) : ?>
                    <li class="disabled">
                        <span>...</span>
                    </li>
                <?php endif; ?>

                <?php /** mulai iterasi sesuai range yang telah ditentukan */
                for( $i = $ii; $i <= $iii; $i++ ) : ?>
                    <li class="<?php echo $current_page == $i ? 'active' : ''; ?>">
                        <a href="<?php echo $base_url; ?>&page=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php /** jika tidak mepet dengan nilai maksimum, tampilkan titik-titik */
                if( $iii != $total_page ) : ?>
                    <li class="disabled">
                        <span>...</span>
                    </li>
                <?php endif; ?>

                <?php /** tampilkan right arrow */
                if( $current_page == $total_page ) : ?>
                    <li class="disabled">
                        <span>&raquo;</span>
                    </li>
                <?php else : ?>
                    <li>
                        <a href="<?php echo $base_url; ?>&page=<?php echo $total_page; ?>">
                            &raquo;
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>

    <?php endif;

    if( $total_data ) : ?>
        <p class="text-muted"><i>Total <?php echo $total_data; ?> data</i></p>
    <?php else : ?>
        <p class="text-danger"><i>Data kosong</i></p>
    <?php endif;

}