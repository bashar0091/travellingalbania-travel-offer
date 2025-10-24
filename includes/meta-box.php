<?php

if (file_exists(dirname(__FILE__) . '/cmb2/init.php')) {
	require_once dirname(__FILE__) . '/cmb2/init.php';
} elseif (file_exists(dirname(__FILE__) . '/CMB2/init.php')) {
	require_once dirname(__FILE__) . '/CMB2/init.php';
}

//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_flight_metabox');
function TravelAlbania_register_flight_metabox()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_flight_info',
		'title'         => esc_html__('Flight Information', 'tta-travel-offer'),
		'object_types'  => array('term'),
		'taxonomies'       => array('tta_travel_flights'),
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Price', 'tta-travel-offer'),
		'id'         => 'price',
		'type'       => 'text_money',
		'desc' => '/ Person',
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Is Package Included', 'tta-travel-offer'),
		'id'         => 'is_package_included',
		'type'       => 'radio_inline',
		'options' => array(
			'yes' => __('Yes', 'tta-travel-offer'),
			'no'   => __('No', 'tta-travel-offer'),
		),
		'default' => 'no',
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
		'name'       => esc_html__('Flight Logo', 'tta-travel-offer'),
		'id'         => 'flight_logo',
		'type'       => 'file',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Flight Name', 'tta-travel-offer'),
		'id'         => 'flight_name',
		'type'       => 'text',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Date', 'tta-travel-offer'),
		'id'         => 'date',
		'type'       => 'text_date',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Start Time', 'tta-travel-offer'),
		'id'         => 'start_time',
		'type'       => 'text_time',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('End Time', 'tta-travel-offer'),
		'id'         => 'end_time',
		'type'       => 'text_time',
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

//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_accommodation_metabox');
function TravelAlbania_register_accommodation_metabox()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_accommodation_info',
		'title'         => esc_html__('Accommodation Information', 'tta-travel-offer'),
		'object_types'  => array('term'),
		'taxonomies'       => array('tta_travel_accommodations'),
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Is Package Included', 'tta-travel-offer'),
		'id'         => 'is_package_included',
		'type'       => 'radio_inline',
		'options' => array(
			'yes' => __('Yes', 'tta-travel-offer'),
			'no'   => __('No', 'tta-travel-offer'),
		),
		'default' => 'no',
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Price', 'tta-travel-offer'),
		'id'         => 'price',
		'type'       => 'text_money',
		'desc' => '/ Room',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Image', 'tta-travel-offer'),
		'id'         => 'image',
		'type'       => 'file',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Location', 'tta-travel-offer'),
		'id'         => 'location',
		'type'       => 'text',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Website', 'tta-travel-offer'),
		'id'         => 'website',
		'type'       => 'text',
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Rating', 'tta-travel-offer'),
		'id'         => 'rating',
		'type'       => 'text_small',
		'attributes' => array(
			'type'  => 'number',
			'min'   => 0,
			'max'   => 5,
			'step'  => 0.1,
		),
	));


	$cmb_init->add_field(array(
		'name'       => esc_html__('Room Type', 'tta-travel-offer'),
		'id'         => 'room_type',
		'type'       => 'textarea',
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Basic', 'tta-travel-offer'),
		'id'         => 'basic',
		'type'       => 'textarea',
	));
}


//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_excursion_metabox');
function TravelAlbania_register_excursion_metabox()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_excursion_info',
		'title'         => esc_html__('Excursion Information', 'tta-travel-offer'),
		'object_types'  => array('term'),
		'taxonomies'       => array('tta_travel_excursions'),
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Is Package Included', 'tta-travel-offer'),
		'id'         => 'is_package_included',
		'type'       => 'radio_inline',
		'options' => array(
			'yes' => __('Yes', 'tta-travel-offer'),
			'no'   => __('No', 'tta-travel-offer'),
		),
		'default' => 'no',
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Price', 'tta-travel-offer'),
		'id'         => 'price',
		'type'       => 'text_money',
		'desc' => '/ Person',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Image', 'tta-travel-offer'),
		'id'         => 'image',
		'type'       => 'file',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Location', 'tta-travel-offer'),
		'id'         => 'location',
		'type'       => 'text',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Description', 'tta-travel-offer'),
		'id'         => 'content',
		'type'       => 'textarea',
	));
}


//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_transport_metabox');
function TravelAlbania_register_transport_metabox()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_transport_info',
		'title'         => esc_html__('Transport Information', 'tta-travel-offer'),
		'object_types'  => array('term'),
		'taxonomies'       => array('tta_travel_transports'),
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Is Package Included', 'tta-travel-offer'),
		'id'         => 'is_package_included',
		'type'       => 'radio_inline',
		'options' => array(
			'yes' => __('Yes', 'tta-travel-offer'),
			'no'   => __('No', 'tta-travel-offer'),
		),
		'default' => 'no',
	));

	$cmb_init->add_field(array(
		'name'       => esc_html__('Price', 'tta-travel-offer'),
		'id'         => 'price',
		'type'       => 'text_money',
		'desc' => '/ Day',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Image', 'tta-travel-offer'),
		'id'         => 'image',
		'type'       => 'file',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Location', 'tta-travel-offer'),
		'id'         => 'location',
		'type'       => 'text',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Included', 'tta-travel-offer'),
		'id'         => 'included',
		'type'       => 'wysiwyg',
	));
	$cmb_init->add_field(array(
		'name'       => esc_html__('Excluded', 'tta-travel-offer'),
		'id'         => 'excluded',
		'type'       => 'wysiwyg',
	));
}


//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_offer_metabox');
function TravelAlbania_register_offer_metabox()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_offer_field',
		'title'         => esc_html__('Offer Field', 'tta-travel-offer'),
		'object_types'  => array('tta_travel_offer'),
	));

	$cmb_init->add_field(array(
		'name'    => esc_html__('Program Template', 'tta-travel-offer'),
		'id'      => 'elementor_program_template',
		'type'    => 'select',
		'options' => TravelAlbania_get_elementor_template(),
		'default' => '',
	));

	$cmb_init->add_field(array(
		'name'    => esc_html__('Connect With WooCommerce', 'tta-travel-offer'),
		'id'      => 'connect_woocommerce',
		'type'    => 'select',
		'options' => TravelAlbania_get_woocommerce_products(),
		'default' => '',
	));

	$cmb_init->add_field(array(
		'name'    => esc_html__('Departure', 'tta-travel-offer'),
		'id'      => 'departure',
		'type'    => 'text_date',
		'default' => '',
	));

	$cmb_init->add_field(array(
		'name'    => esc_html__('Return', 'tta-travel-offer'),
		'id'      => 'return',
		'type'    => 'text_date',
		'default' => '',
	));

	$cmb_init->add_field(array(
		'name'    => esc_html__('Number of people', 'tta-travel-offer'),
		'id'      => 'number_of_people',
		'type'       => 'text_small',
		'attributes' => array(
			'type'  => 'number',
			'min'   => 0,
			'max'   => 100,
		),
	));
}
function TravelAlbania_get_woocommerce_products()
{
	if (!class_exists('WooCommerce')) {
		return array('' => 'WooCommerce not active');
	}

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
	);

	$products = get_posts($args);
	$options = array('' => '-- Select Product --');

	foreach ($products as $product) {
		$options[$product->ID] = $product->post_title;
	}

	return $options;
}

function TravelAlbania_get_elementor_template()
{
	// if (!class_exists('Elementor')) {
	// 	return array('' => 'Elementor not active');
	// }

	$args = array(
		'post_type'      => 'elementor_library',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
	);

	$templates = get_posts($args);
	$options = array('' => '-- Select Template --');

	foreach ($templates as $template) {
		$options[$template->ID] = $template->post_title;
	}

	return $options;
}


//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_accommodations_field');
function TravelAlbania_register_accommodations_field()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_accommodations_field',
		'title'         => esc_html__('Select Accommodations by Date', 'tta-travel-offer'),
		'object_types'  => array('tta_travel_offer'),
	));

	$group_id = $cmb_init->add_field([
		'id'          => 'TravelAlbania_accommodations_repeat',
		'type'        => 'group',
		'options'     => [
			'group_title'   => esc_html__('Accommodation {#}', 'tta-travel-offer'),
			'add_button'    => esc_html__('Add', 'tta-travel-offer'),
			'remove_button' => esc_html__('Remove', 'tta-travel-offer'),
			'closed'         => true,
			'sortable'		=> true,
		],
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Start Date', 'tta-travel-offer'),
		'id'         => 'start_date',
		'type'       => 'text_date',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('End Date', 'tta-travel-offer'),
		'id'         => 'end_date',
		'type'       => 'text_date',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Select Accommodation', 'tta-travel-offer'),
		'id'         => 'accommodation_select',
		'type'    => 'multicheck',
		'options' => fetch_taxonomy('tta_travel_accommodations'),
	]);
}


//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_excursions_field');
function TravelAlbania_register_excursions_field()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_excursions_field',
		'title'         => esc_html__('Select Excursions by Date', 'tta-travel-offer'),
		'object_types'  => array('tta_travel_offer'),
	));

	$group_id = $cmb_init->add_field([
		'id'          => 'TravelAlbania_excursions_repeat',
		'type'        => 'group',
		'options'     => [
			'group_title'   => esc_html__('Excursion {#}', 'tta-travel-offer'),
			'add_button'    => esc_html__('Add', 'tta-travel-offer'),
			'remove_button' => esc_html__('Remove', 'tta-travel-offer'),
			'closed'         => true,
			'sortable' => true,
		],
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Date', 'tta-travel-offer'),
		'id'         => 'date',
		'type'       => 'text_date',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Select Excursion', 'tta-travel-offer'),
		'id'         => 'excursion_select',
		'type'    => 'multicheck',
		'options' => fetch_taxonomy('tta_travel_excursions'),
	]);
}


//===========================
add_action('cmb2_admin_init', 'TravelAlbania_register_transports_field');
function TravelAlbania_register_transports_field()
{
	$cmb_init = new_cmb2_box(array(
		'id'            => 'TravelAlbania_transports_field',
		'title'         => esc_html__('Select Transports by Date', 'tta-travel-offer'),
		'object_types'  => array('tta_travel_offer'),
	));

	$group_id = $cmb_init->add_field([
		'id'          => 'TravelAlbania_transports_repeat',
		'type'        => 'group',
		'options'     => [
			'group_title'   => esc_html__('Transport {#}', 'tta-travel-offer'),
			'add_button'    => esc_html__('Add', 'tta-travel-offer'),
			'remove_button' => esc_html__('Remove', 'tta-travel-offer'),
			'closed'         => true,
		],
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Start Date', 'tta-travel-offer'),
		'id'         => 'start_date',
		'type'       => 'text_date',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('End Date', 'tta-travel-offer'),
		'id'         => 'end_date',
		'type'       => 'text_date',
	]);

	$cmb_init->add_group_field($group_id, [
		'name'       => esc_html__('Select Transport', 'tta-travel-offer'),
		'id'         => 'transport_select',
		'type'    => 'multicheck',
		'options' => fetch_taxonomy('tta_travel_transports'),
	]);
}

function fetch_taxonomy($taxname)
{
	$options = array();

	$taxonomies = get_terms(array(
		'taxonomy' => $taxname,
		'hide_empty' => false
	));

	if (!empty($taxonomies)) :
		foreach ($taxonomies as $item):
			$options[$item->term_id] = $item->name;
		endforeach;
	endif;

	return $options;
}
