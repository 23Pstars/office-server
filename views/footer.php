<?php

namespace LRS\OfficeServer\Views\Publik;

use LRS\OfficeServer\Controller\Footers;

Footers::get_instance()
    ->add_script( 'jquery.min.js' )
    ->add_script( 'jquery.ui.min.js' )
    ->add_script( 'bootstrap.min.js' );

?>

    <div class="container">
        <footer>
            <p>&copy; Hak Cipta <?php echo date( 'Y' ); ?> <?php echo LRS_APP_AUTHOR; ?></p>
        </footer>
    </div>

    <?php echo Footers::get_instance()->get_script(); ?>

</body>
</html>