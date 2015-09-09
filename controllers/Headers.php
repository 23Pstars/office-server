<?php

/**
 * Class ini berisi fungsi untuk keperluan
 * kostumisasi header
 */

namespace LRS\OfficeServer\Controller;

class Headers {

    const add_position_before = 'before';
    const add_position_after = 'after';

    var $page_name;
    var $page_sub_name;

    var $head_title;
    var $page_title;
    var $page_title_postfix = LRS_APP_NAME;
    var $page_title_separator = '&raquo;';

    var $head_meta;
    /**
     * favicon dan style masuk juga dalam kategory link
     */
    var $head_link;
    var $head_script;

    function __construct() {
        $this->set_head_title();
    }

    /** @var Headers $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * untuk keperluan identifikasi halaman
     * biasanya untuk menentukan halaman yang sedang aktif
     * kemudian memberikan class tertentu, misalnya di Menu
     *
     * @param string $page_name
     * @return $this
     */
    function set_page_name( $page_name = '' ) {
        $this->page_name = $page_name;
        return $this;
    }
    function get_page_name() {
        return $this->page_name;
    }

    /**
     * ada kalanya suatu page merupakan sub dari suatu page,
     * sehingga untuk identifikasi halaman dapat dilakukan
     * dua - duanya antara parent dan child page nya
     *
     * @param string $page_sub_name
     * @return $this
     */
    function set_page_sub_name( $page_sub_name = '' ) {
        $this->page_sub_name = $page_sub_name;
        return $this;
    }
    function get_page_sub_name() {
        return $this->page_sub_name;
    }

    /**
     * menentukan string untuk ditempatkan di tag <title>
     */
    function set_head_title() {
        $this->head_title = '<title>' . $this->page_title . ' ' . $this->page_title_separator . ' ' . $this->page_title_postfix . '</title>' . PHP_EOL;
        return $this;
    }
    function set_page_title( $page_title = '' ) {
        $this->page_title = $page_title;
        return $this->set_head_title();
    }
    function set_page_title_postfix( $post_title_postfix = LRS_APP_NAME ) {
        $this->page_title_postfix = $post_title_postfix;
        return $this->set_head_title();
    }
    function set_page_title_separator( $post_title_separator = '&raquo;' ) {
        $this->page_title_separator = $post_title_separator;
        return $this->set_head_title();
    }
    function get_head_title() {
        return $this->head_title;
    }

    /**
     * contoh :
     * $args = array(
     *      array( 'name' => 'description', 'content' => 'string deskripsi' ),
     *      array( 'name' => 'keyword', 'content' => 'bagian, string, keyword' )
     *  )
     *
     * keluaran :
     * <meta name="description" content="string deskripsi" />
     * <meta name="keyword" content="bagian, string, keyword" />
     *
     * @param array $args
     * @param string $position
     * @return $this
     */
    function add_head_meta( $args = array(), $position = self::add_position_after ) {
        if( is_array( $args ) ) {
            foreach( $args as $tag ) {
                if( is_array( $tag ) ) {
                    $tmp = '<meta';
                    foreach( $tag as $tag_property => $tag_value ) {
                        $tmp .= ' ' . $tag_property . '="' . $tag_value . '"';
                    }
                    switch( $position ) {
                        case self::add_position_after : $this->head_meta .= $tmp . ' />' . PHP_EOL; break;
                        case self::add_position_before : $this->head_meta = $tmp . ' />' . PHP_EOL . $this->head_meta; break;
                    }
                }
            }
        }
        return $this;
    }
    function get_head_meta() {
        return $this->head_meta;
    }

    /**
     * contoh :
     * $args = array(
     *      array( 'rel' => 'canonical', 'href' => 'http://products.lrsoft.org/lrs-engine/' ),
     *      array( 'rel' => 'author', 'href' => 'http://plus.google.com/+AhmadZafrullah' )
     *  )
     *
     * keluaran :
     * <link rel="canonical" href="http://products.lrsoft.org/lrs-engine/"/>
     * <link rel="author" href="http://plus.google.com/+AhmadZafrullah" />
     *
     * @param array $args
     * @param string $position
     * @return $this;
     */
    function add_head_link( $args = array(), $position = self::add_position_after ) {
        if( is_array( $args ) ) {
            foreach( $args as $tag ) {
                if( is_array( $tag ) ) {
                    $tmp = '<link';
                    foreach( $tag as $tag_property => $tag_value ) {
                        $tmp .= ' ' . $tag_property . '="' . $tag_value . '"';
                    }
                    switch( $position ) {
                        case self::add_position_after : $this->head_link .= $tmp . ' />' . PHP_EOL; break;
                        case self::add_position_before : $this->head_link = $tmp . ' />' . PHP_EOL . $this->head_link; break;
                    }

                }
            }
        }
        return $this;
    }

    /**
     * contoh :
     * add_style( 'style.css' )
     *
     * keluaran :
     * <link rel="stylesheet" href="http://root/asset/css/style.css" />
     *
     * @param $file
     * @param $position
     * @return $this
     */
    function add_style( $file, $position = self::add_position_after ) {
        $href = LRS_STYLE_URI_PATH . DS . $file;
        $style = array( array( 'rel' => 'stylesheet', 'href' => $href ) );
        return $this->add_head_link( $style, $position );
    }

    /**
     * contoh:
     * add_favicon()
     *
     * keluaran:
     * <link rel="shortcut icon' type="image/png" href="http://root/asset/img/favicon.png" sizes="16x16" />
     * <link rel="apple-touch-icon' href="http://root/asset/img/favicon.png" />
     * <link rel="apple-touch-icon-precomposed' href="http://root/asset/img/favicon.png" />
     *
     * @param string $file
     * @param string $type
     * @param string $position
     * @return $this
     */
    function add_favicon( $file = 'favicon.png', $type = 'image/png', $position = self::add_position_after ) {
        $href = LRS_IMAGE_URI_PATH . DS . $file;
        $favicon = array(
            array( 'rel' => 'shortcut icon', 'type' => $type, 'href' => $href, 'sizes' => '16x16' ),
            array( 'rel' => 'apple-touch-icon', 'href' => $href ),
            array( 'rel' => 'apple-touch-icon-precomposed', 'href' => $href )
        );
        return $this->add_head_link( $favicon, $position );
    }
    function get_head_link() {
        return $this->head_link;
    }

    /**
     * contoh :
     * add_head_script( 'jquery.min.js' )
     *
     * keluaran :
     * <script type="text/javascript" src="http://root/asset/js/jquery.min.js" />
     *
     * @param $file
     * @param string $position
     * @return $this
     */
    function add_head_script( $file, $position = self::add_position_after ) {

        $src = LRS_SCRIPT_URI_PATH . DS . $file;
        switch( $position ) {
            case self::add_position_after : $this->head_script .= '<script type="text/javascript" src="' . $src . '"></script>' . PHP_EOL; break;
            case self::add_position_before : $this->head_script = '<script type="text/javascript" src="' . $src . '"></script>' . PHP_EOL . $this->head_script; break;
        }

        return $this;

    }
    function get_head_script() {
        return $this->head_script;
    }

}

?>