<?php
if (!defined('WPINC')) {
	die;
}
global $wpdb;

$input = shortcode_atts(array(
	'tahun' => '2022'
), $atts);

echo "Pengukuran Kinerja" . $input['tahun'];
