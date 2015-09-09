<?php

namespace LRS\OfficeServer\Views\Staff;

use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Contents;

$base_link = LRS_URI_PATH . DS . Contents::get_instance()->get_view(); ?>
<ul class="nav nav-sidebar">
    <li <?php echo 'Staff' == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>"><i class="fa fa-user"></i>&nbsp;&nbsp;Staff</a></li>
    <li <?php echo 'Absen' == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> >
        <a href="<?php echo $base_link; ?>/absen"><i class="fa fa-calendar"></i>&nbsp;&nbsp;Absen</a>
        <ul class="nav nav-sidebar">
            <li <?php echo ( $page = 'Histori' ) == Headers::get_instance()->get_page_sub_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/absen/histori"><?php echo $page; ?></a></li>
        </ul>
    </li>
</ul>