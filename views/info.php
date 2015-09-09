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
use LRS\OfficeServer\Controller\Sessions;

Headers::get_instance()
    ->set_page_title( 'Pusat Informasi' );

Contents::get_instance()->get_header( Sessions::get_instance()->_has() ? 'loggedin' : '' );

?>

    <div class="container info">
        <div class="row">
            <div class="col-sm-8 main">
                <div class="item">
                    <h2 class="title">Ada masalah?</h2>
                    <div class="content">
                        <p>Malu berjalan, sesat bertanya.</p>
                        <hr/>
                        <h3>
                            Project Manager
                            <small>Research and Development (R&D)</small>
                        </h3>
                        <p>
                            <i class="fa fa-suitcase"></i>&nbsp;&nbsp;Ahmad Zafrullah
                            &nbsp;&mdash;&nbsp;
                            <i class="fa fa-phone"></i>&nbsp;&nbsp;+6287864052132
                            &nbsp;&mdash;&nbsp;
                            <i class="fa fa-envelope"></i>&nbsp;&nbsp;<a href="mailto:zaf@elektro08.com">zaf@elektro08.com</a>
                        </p>
                        <p></p>
                        <p></p>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 sidebar">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <strong>Partners</strong>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><a href="http://lrsoft.co.id">CV. LRsoft</a></li>
                            <li><a href="http://www.indotravelonline.com">CV. Indo Travel Online</a></li>
                            <li><a href="http://www.lombokreisen.com">CV. Lombok Reisen</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php Contents::get_instance()->get_footer();