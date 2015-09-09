<?php

namespace LRS\OfficeServer\Views\Staff;

use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Sessions;

$obj_people = Sessions::get_instance()->_retrieve()->getObjPeople();

Headers::get_instance()
    ->set_page_title( 'Staff' )
    ->set_page_name( 'Staff' );

Contents::get_instance()->get_header();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3 col-sm-2 sidebar">
            <?php Contents::get_instance()->get_sidebar(); ?>
        </div>
        <div class="col-xs-9 col-sm-10 main">
            <h1 class="page-header">
                Hi, <?php echo $obj_people->getFirstName(); ?>
            </h1>
        </div>
    </div>
</div>

<?php Contents::get_instance()->get_footer();