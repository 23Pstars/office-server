<?php

namespace LRS\OfficeServer\Views\Admin;

use LRS\OfficeServer\Controller\Headers;
use LRS\OfficeServer\Controller\Contents;
use LRS\OfficeServer\Controller\Inbox;
use LRS\OfficeServer\Controller\Outbox;

$base_link = LRS_URI_PATH . DS . Contents::get_instance()->get_view(); ?>
<ul class="nav nav-sidebar">
    <li <?php echo 'Admin' == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>"><i class="fa fa-linux"></i>&nbsp;&nbsp;Admin</a></li>
    <li <?php echo 'Absen' == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> >
        <a href="<?php echo $base_link; ?>/absen"><i class="fa fa-calendar"></i>&nbsp;&nbsp;Absen</a>
        <ul class="nav nav-sidebar">
            <li <?php echo ( $page = 'Histori' ) == Headers::get_instance()->get_page_sub_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/absen/histori"><?php echo $page; ?></a></li>
        </ul>
    </li>
    <li <?php echo 'Kontak' == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> >
        <a href="<?php echo $base_link; ?>/kontak/daftar"><i class="fa fa-users"></i>&nbsp;&nbsp;Kontak</a>
        <ul class="nav nav-sidebar">
            <li <?php echo ( $page = 'Daftar' ) == Headers::get_instance()->get_page_sub_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/kontak/daftar"><?php echo $page; ?></a></li>
            <li <?php echo ( $page = 'Grup' ) == Headers::get_instance()->get_page_sub_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/kontak/grup"><?php echo $page; ?></a></li>
        </ul>
    </li>
    <li <?php echo ( $page = 'Broadcast' ) == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/broadcast"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;<?php echo $page; ?></a></li>
    <li <?php echo ( $page = 'Pesan Keluar' ) == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/pesan-keluar"><i class="glyphicon glyphicon-open"></i>&nbsp;&nbsp;<?php echo $page; ?><?php if( $x = Outbox::get_instance()->_count() ) : ?><span class="badge pull-right"><?php echo $x; ?></span><?php endif; ?></a></li>
    <li <?php echo ( $page = 'Pesan Masuk' ) == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/pesan-masuk"><i class="glyphicon glyphicon-save"></i>&nbsp;&nbsp;<?php echo $page; ?><?php if( $x = Inbox::get_instance()->_unread_count() ) : ?><span class="badge pull-right"><?php echo $x; ?></span><?php endif; ?></a></li>
    <li <?php echo ( $page = 'Pesan Masuk ' . Inbox::public_prefix ) == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/pesan-masuk-publik"><i class="glyphicon glyphicon-save"></i>&nbsp;&nbsp;<?php echo $page; ?><?php if( $x = Inbox::get_instance()->_public_unread_count() ) : ?><span class="badge pull-right"><?php echo $x; ?></span><?php endif; ?></a></li>
    <li <?php echo ( $page = 'Pesan Terkirim' ) == Headers::get_instance()->get_page_name() ? 'class="active"' : ''; ?> ><a href="<?php echo $base_link; ?>/pesan-terkirim"><i class="glyphicon glyphicon-saved"></i>&nbsp;&nbsp;<?php echo $page; ?></a></li>
</ul>