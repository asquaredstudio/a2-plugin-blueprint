<?php
namespace a2PluginBlueprint;

/**
 * Class StoreLocator
 *
 * @package a2PluginBlueprint
 */
class StoreLocator extends Module {
	function __construct() {
		parent::__construct( 'stores', 'store_categories' );
	}

	/**
	 * Loads some scripts
	 *
	 * @return void
	 */
	function initStylesScripts() {
		wp_enqueue_script( 'jquery' );
	}

	/**
	 * @param $facets
	 */
	function initFacet( $facets ) {
		$facets[] = [
			"name"           => "proximity",
			"label"          => "proximity",
			"type"           => "proximity",
			"source"         => "acf/field_611549dee9b7e",
			"source_other"   => "",
			"unit"           => "mi",
			"radius_ui"      => "dropdown",
			"radius_options" => "10, 25, 50, 100, 250",
			"radius_min"     => "1",
			"radius_max"     => "50",
			"radius_default" => "25"
		];
		$facets[] = [
			"name"            => "store_categories",
			"label"           => "Store Categories",
			"type"            => "checkboxes",
			"source"          => "tax/store_categories",
			"parent_term"     => "",
			"modifier_type"   => "off",
			"modifier_values" => "",
			"hierarchical"    => "no",
			"show_expanded"   => "no",
			"ghosts"          => "no",
			"preserve_ghosts" => "no",
			"operator"        => "and",
			"orderby"         => "count",
			"count"           => "10",
			"soft_limit"      => "5"
		];
		$facets[] = [
			"name"           => "map",
			"label"          => "map",
			"type"           => "map",
			"source"         => "acf/field_611549dee9b7e",
			"source_other"   => "",
			"map_design"     => "default",
			"cluster"        => "no",
			"ajax_markers"   => "no",
			"limit"          => "all",
			"map_width"      => "100%",
			"map_height"     => "450px",
			"min_zoom"       => "1",
			"max_zoom"       => "20",
			"default_lat"    => "",
			"default_lng"    => "",
			"default_zoom"   => "",
			"marker_content" => "<h3><a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a></h3>"
		];

		return $facets;
	}

	/**
	 * Adds the missing google API key
	 */
	function initACF() {
		acf_update_setting( 'google_api_key', 'AIzaSyDWXzWCbawPGoGC9vnaeGl0mNTvez3SPBU' );
	}

	/**
	 * Registers models and any additional plugin functionality
	 */
	function initPlugin() {
		register_post_type( $this->customPostName, [
			'label'                 => 'Stores',
			'description'           => 'Stores',
			'hierarchical'          => false,
			'supports'              => [
				0 => 'title',
				1 => 'thumbnail',
				2 => 'editor'
			],
			'taxonomies'            => [],
			'public'                => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'can_export'            => true,
			'delete_with_user'      => 'null',
			'labels'                => [],
			'menu_icon'             => 'dashicons-admin-post',
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'rewrite'               => true,
			'has_archive'           => true,
			'show_in_rest'          => false,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'acfe_archive_template' => '',
			'acfe_archive_ppp'      => 10,
			'acfe_archive_orderby'  => 'date',
			'acfe_archive_order'    => 'DESC',
			'acfe_single_template'  => '',
			'acfe_admin_archive'    => false,
			'acfe_admin_ppp'        => 10,
			'acfe_admin_orderby'    => 'date',
			'acfe_admin_order'      => 'DESC',
			'capability_type'       => 'post',
			'capabilities'          => [],
			'map_meta_cap'          => null,
		] );

		register_taxonomy( $this->customTaxName, [ 0 => $this->customPostName ], [
			'label'                 => 'Store Categories',
			'description'           => '',
			'hierarchical'          => true,
			'post_types'            => [
				0 => $this->customPostName,
			],
			'public'                => true,
			'publicly_queryable'    => true,
			'update_count_callback' => '',
			'sort'                  => false,
			'labels'                => [],
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => true,
			'show_in_quick_edit'    => true,
			'show_admin_column'     => true,
			'rewrite'               => true,
			'show_in_rest'          => false,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'acfe_single_template'  => '',
			'acfe_single_ppp'       => 10,
			'acfe_single_orderby'   => 'date',
			'acfe_single_order'     => 'DESC',
			'acfe_admin_ppp'        => 10,
			'acfe_admin_orderby'    => 'name',
			'acfe_admin_order'      => 'ASC',
			'capabilities'          => [],
			'meta_box_cb'           => null,
		] );

		if ( function_exists( 'acf_add_local_field_group' ) ):

			acf_add_local_field_group( [
				'key'                   => 'group_611549c1c1956',
				'title'                 => 'Store Options',
				'fields'                => [
					[
						'key'               => 'field_611549dee9b7e',
						'label'             => 'Address',
						'name'              => 'address',
						'type'              => 'google_map',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'center_lat'        => '',
						'center_lng'        => '',
						'zoom'              => '',
						'height'            => '',
					],
				],
				'location'              => [
					[
						[
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => $this->customPostName,
						],
					],
				],
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'left',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'acfe_display_title'    => '',
				'acfe_autosync'         => '',
				'acfe_form'             => 0,
				'acfe_meta'             => '',
				'acfe_note'             => '',
			] );

		endif;

		add_action( 'wp_head', [
			$this,
			'insertHelperScripts'
		] );
	}

	/**
	 * Inserts ACF helper scripts into the single page
	 */
	function insertHelperScripts() {
		if ( is_singular( $this->customPostName ) ) :
			?>
			<style>
				.acf-map {
					width: 100%;
					height: 450px;
					margin: 20px 0;
				}

				.acf-map img {
					max-width: inherit !important;
				}

			</style>
			<script src="https://maps.googleapis.com/maps/api/js?key=<?php
			echo acf_get_setting( 'google_api_key' ); ?>"></script>
			<script type="text/javascript">
                (function ($) {

                    /**
                     * initMap
                     *
                     * Renders a Google Map onto the selected jQuery element
                     *
                     * @date    22/10/19
                     * @since   5.8.6
                     *
                     * @param   jQuery $el The jQuery element.
                     * @return  object The map instance.
                     */
                    function initMap($el) {

                        // Find marker elements within map.
                        var $markers = $el.find('.marker');

                        // Create gerenic map.
                        var mapArgs = {
                            zoom: $el.data('zoom') || 16,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        var map = new google.maps.Map($el[0], mapArgs);

                        // Add markers.
                        map.markers = [];
                        $markers.each(function () {
                            initMarker($(this), map);
                        });

                        // Center map based on markers.
                        centerMap(map);

                        // Return map instance.
                        return map;
                    }

                    /**
                     * initMarker
                     *
                     * Creates a marker for the given jQuery element and map.
                     *
                     * @date    22/10/19
                     * @since   5.8.6
                     *
                     * @param   jQuery $el The jQuery element.
                     * @param   object The map instance.
                     * @return  object The marker instance.
                     */
                    function initMarker($marker, map) {

                        // Get position from marker.
                        var lat = $marker.data('lat');
                        var lng = $marker.data('lng');
                        var latLng = {
                            lat: parseFloat(lat),
                            lng: parseFloat(lng)
                        };

                        // Create marker instance.
                        var marker = new google.maps.Marker({
                            position: latLng,
                            map: map
                        });

                        // Append to reference for later use.
                        map.markers.push(marker);

                        // If marker contains HTML, add it to an infoWindow.
                        if ($marker.html()) {

                            // Create info window.
                            var infowindow = new google.maps.InfoWindow({
                                content: $marker.html()
                            });

                            // Show info window when marker is clicked.
                            google.maps.event.addListener(marker, 'click', function () {
                                infowindow.open(map, marker);
                            });
                        }
                    }

                    /**
                     * centerMap
                     *
                     * Centers the map showing all markers in view.
                     *
                     * @date    22/10/19
                     * @since   5.8.6
                     *
                     * @param   object The map instance.
                     * @return  void
                     */
                    function centerMap(map) {

                        // Create map boundaries from all map markers.
                        var bounds = new google.maps.LatLngBounds();
                        map.markers.forEach(function (marker) {
                            bounds.extend({
                                lat: marker.position.lat(),
                                lng: marker.position.lng()
                            });
                        });

                        // Case: Single marker.
                        if (map.markers.length == 1) {
                            map.setCenter(bounds.getCenter());

                            // Case: Multiple markers.
                        } else {
                            map.fitBounds(bounds);
                        }
                    }

                    // Render maps on page load.
                    $(document).ready(function () {
                        $('.acf-map').each(function () {
                            var map = initMap($(this));
                        });
                    });

                })(jQuery);
			</script>
		<?php
		endif;
	}
}