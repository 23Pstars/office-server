<?php
/**  
 * LRsoft Corp.
 * http://lrsoft.co.id
 *
 * Author : Zaf
 */

namespace LRS\OfficeServer\Controller;


class Helpers {

    const aksi_perbaiki = 'perbaiki';
    const aksi_perbarui = 'perbarui';
    const aksi_tambah = 'tambah';
    const aksi_hapus = 'hapus';
    const aksi_simpan = 'simpan';
    const aksi_cari = 'cari';
    const aksi_lihat = 'lihat';
    const aksi_tampilkan = 'tampilkan';
    const aksi_kirim_pesan = 'kirim-pesan';

    const aksi_masuk = 'masuk';
    const aksi_keluar = 'keluar';
    const aksi_lupa_kata_sandi = 'lupa-kata-sandi';

    const cari_param = 'q';
    const aksi_param = 'aksi';
    const status_param = 'status';

    /** @var Helpers $instance */
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $message_object = 'Objek';

    function set_message_object( $object ) {
        $this->message_object = $object;
        return $this;
    }

    function display_message( $status ) {
        $class = 'alert-danger';
        $icon = 'remove';
        $message = 'Terjadi kesalahan, harap menghubungi developer';
        switch( $status ) {
            case 1  :
                $class = 'alert-success'; $icon = 'ok';
                $message = $this->message_object . ' berhasil ditambahkan.';
                break;
            case 2  :
                $class = 'alert-info'; $icon = 'ok';
                $message = $this->message_object . ' berhasil diperbarui.';
                break;
            case 3  :
                $class = 'alert-warning'; $icon = 'ok';
                $message = $this->message_object . ' berhasil dihapus.';
                break;
        }
        echo '<div class="alert ' . $class . '"><p><i class="glyphicon glyphicon-' . $icon . '"></i> ' . $message . '</p></div>';
    }


} 