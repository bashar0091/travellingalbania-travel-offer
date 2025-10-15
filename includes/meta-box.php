<?php

if (file_exists(dirname(__FILE__) . '/cmb2/init.php')) {
	require_once dirname(__FILE__) . '/cmb2/init.php';
} elseif (file_exists(dirname(__FILE__) . '/CMB2/init.php')) {
	require_once dirname(__FILE__) . '/CMB2/init.php';
}

add_action('cmb2_admin_init', 'TravelAlbania_register_demo_metabox');

function TravelAlbania_register_demo_metabox()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_flight_info',
		'title'         => esc_html__('Flight Information', 'tta-travel-offer'),
		'object_types'  => array('term'),
		'taxonomies'       => array('tta_travel_flights'),
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Flight Name', 'tta-travel-offer'),
		'id'         => 'flight_name',
		'type'       => 'text',
	));

	$group_id = $cmb_init->add_field([
		'id'          => 'TravelAlbania_flight_repeat',
		'type'        => 'group',
		'options'     => [
			'group_title'   => esc_html__('Flight Information {#}', 'tta-travel-offer'),
			'add_button'    => esc_html__('Add', 'tta-travel-offer'),
			'remove_button' => esc_html__('Remove', 'tta-travel-offer'),
		],
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Date', 'tta-travel-offer'),
		'id'         => 'date',
		'type'       => 'text_datetime_timestamp',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('From', 'tta-travel-offer'),
		'id'         => 'from',
		'type'       => 'text',
	]);
	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('To', 'tta-travel-offer'),
		'id'         => 'to',
		'type'       => 'text',
	]);
	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Flight Number', 'tta-travel-offer'),
		'id'         => 'flight_number',
		'type'       => 'text',
	]);
	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Class', 'tta-travel-offer'),
		'id'         => 'flight_class',
		'type'       => 'text',
	]);
}
