<?php

namespace LRS\OfficeServer\Views\Publik;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Sessions;

Headers::get_instance()
    ->set_page_title( 'Beranda' );

Contents::get_instance()->get_header( Sessions::get_instance()->_has() ? 'loggedin' : '' );

?>

<div class="jumbotron unram-splash">
    <div class="container">
        <h1>LRsoft Office Server</h1>
        <p>Sistem LRsoft Office Server merupakan aplikasi yang dikembangkan untuk menunjang kinerja dan disiplin rekan/staff di CV. LRsoft.</p>
    </div>
</div>

<?php Contents::get_instance()->get_footer();