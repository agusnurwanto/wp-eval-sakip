<?php 
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'id_jadwal' => '',
), $atts);

echo "pengisian lke sakip id jadwal= " . $input['id_jadwal'];