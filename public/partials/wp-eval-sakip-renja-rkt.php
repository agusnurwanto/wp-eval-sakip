<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
$input = shortcode_atts( array(
	'tahun' => '2022'
), $atts );

echo "RENJA RKT" .$input['tahun'];
global $wpdb;
?>