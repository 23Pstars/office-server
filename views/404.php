<?php

namespace LRS\OfficeServer\Views\Publik;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Sessions;

Headers::get_instance()
    ->set_page_title( 'Halaman tidak ditemukan' );

Contents::get_instance()->get_header( Sessions::get_instance()->_has() ? 'loggedin' : '' );

?>

    <div class="jumbotron">
        <div class="container">
            <h1>Aduh :(</h1>
            <p>Halaman yang side coba akses tidak ada atau sudah dihapus.</p>
            <p><a href="<?php echo LRS_URI_PATH; ?>" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i> Beranda</a></p>
        </div>
    </div>

<?php Contents::get_instance()->get_footer();