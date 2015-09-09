<?php

/**
 * Class ini berisi fungsi untuk membuat parameter
 * menjadi lebih user friendly URL
 *
 * mungkin akan berisi banyak conditional statement,
 * tapi bisa disesuaikan dengan kebutuhan
 *
 * dan class ini yang mungkin akan mengalami banyak
 * sekali revisi / perubahan.
 */

namespace LRS\OfficeServer\Controller;

class Routes {

    /**
     * @var int
     *
     * @todo
     * buat ini otomatis dengan mendeteksi jumlah
     * index $_REQUEST dengan pattern `tingkat^`
     * sehingga nantinya hanya tinggal mengganti
     * kebutuhan tingkat di file .htaccess
     */
    private $x_tingkat = 5;

    /**
     * @var array
     *
     * berisi daftar tingkat yang akan dibutuhkan
     * dalam operasi selanjutnya dan disimpan
     * dalam bentuk array sehingga mempermudah pengaksesan
     */
    private $tingkats;

    /**
     * tentukan views apa saja yang akan digunakan,
     * dalam hal ini kita gunakan $tingkat[1] sebagai type untuk view
     */
    const view_admin = 'admin';
    const view_staff = 'staff';
    const view_beranda = 'beranda';

    const script_ext = '.php';
    const page_index = 'index.php';
    const page_404 = '404.php';

    /** @var Routes $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $params
     */
    function init_tingkat( $params ) {
        for( $i = 1; $i <= $this->x_tingkat; $i++ )
            if( isset( $params[ 'tingkat' . $i ] ) )
                $this->tingkats[ $i ] = $params[ 'tingkat' . $i ];
    }

    /**
     * @param $index
     * @return bool
     *
     * untuk mengambil nilai dari tingkat
     * dengan index tertentu
     */
    function get_tingkat( $index ) {
        return !empty( $this->tingkats[ $index ] ) ?
            $this->tingkats[ $index ] : '';
    }

    /**
     * @param $index
     * @return bool
     *
     * melakukan pengecekan terhadap tingkat
     * dengan index tertentu
     */
    function has_tingkat( $index ) {
        return '' != $this->get_tingkat( $index );
    }

    /**
     * @param $index
     * @param $value
     * @return bool
     *
     * melakukan pengecekan nilai apakah
     * terdapat pada tingkat dengan index tertentu
     */
    function is_tingkat( $index, $value ) {
        return $value == $this->get_tingkat( $index );
    }

    /**
     * @param $params
     */
    function parse_page( $params ) {

        /** init tingkat yang ada di params */
        $this->init_tingkat( $params );

        $view_path = LRS_VIEW_ABS_PATH;
        $file_to_include = $view_path . DS . self::page_404;

        /** kondisi untuk halaman publik utama atau beranda */
        if( !$this->has_tingkat( 1 ) || $this->get_tingkat( 1 ) == self::view_beranda ) {
            $file_to_include = $view_path . DS . self::page_index;

        /** untuk kondisi pertama, tentukan jenis nya terlebih dahulu */
        } else {

            $has_view = true;

            /** untuk tingkat 1 dari jenis view, langsung tampilkan index */
            switch( $this->get_tingkat( 1 ) ) {
                case self::view_admin :
                    Contents::get_instance()->set_view( self::view_admin ); break;
                case self::view_staff :
                    Contents::get_instance()->set_view( self::view_staff ); break;
                default :
                    $has_view = false;
            }

            /** jika kondisi diatas memenuhi diantara view yang telah ditentukan */
            if( $has_view ) {

                /**
                 * kondisi pertama jika memang yang diinginkan adalah halaman
                 * index dari tiap view
                 */
                $condition1 = Contents::get_instance()->get_view_path() . DS . self::page_index;

                /**
                 * kondisi kedua jika sub page dari tiap view tidak ketemu
                 * gunakan index dari tiap view
                 */
                $condition2 = Contents::get_instance()->get_view_path() . DS .
                    ( !$this->has_tingkat( 2 ) ? self::page_index : $this->get_tingkat( 2 ) . self::script_ext );

                /**
                 * lakukan pengecekan
                 */
                $file_to_include = is_readable( $condition2 ) ? $condition2 : $condition1;

            /** jika memang tidak ada di daftar view */
            } else {

                /**
                 * coba eksekusi tingkat1.php jika ada
                 */
                $file_to_include = $view_path . DS . $this->get_tingkat( 1 ) . self::script_ext;

                /**
                 * dan apabila memang tidak ada, langsung berikan halaman 404
                 */
                is_readable( $file_to_include ) || $file_to_include = $view_path . DS . self::page_404;

                /**
                 * jika memang file untuk di-include tidak ditemukan
                 * bersihkan variabel view yang sudah kedung tersimpan
                 * di objek Contents
                 */
                Contents::get_instance()->remove_view();
            }

        }

        //lrs_exit( $file_to_include );

        /** mulai meng-include */
        if( is_readable( $file_to_include ) ) include( $file_to_include );

        /** bahkan jika file 404.php tidak ada */
        else lrs_exit( 'Script yang side eksekusi belum ada.', 'alert' );

    }

} 