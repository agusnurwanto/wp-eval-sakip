<?php
if (!defined('WPINC')) {
	die;
}
$input = shortcode_atts(array(
	'tahun' => '2022'
), $atts);

global $wpdb;
echo 'detail per skpd ' .$input['tahun'];