<?php

namespace Autobrunei\Models;

use Autobrunei\Data\Helper as DataHelper;
use Autobrunei\Main;

/**
 * Aku pakai interface just to make the models methods are consistent
 */
class Listing implements ModelInterface
{
    public function create_post_type()
    {
        $labels = array(
            'name'               => __( 'Listings' ),
            'singular_name'      => __( 'Listing' ),
            'add_new'            => __( 'Add New Listing' ),
            'add_new_item'       => __( 'Add New Listing' ),
            'edit_item'          => __( 'Edit Listing' ),
            'new_item'           => __( 'Add New Listing' ),
            'view_item'          => __( 'View Listing' ),
            'search_items'       => __( 'Search Listing' ),
            'not_found'          => __( 'No listings found' ),
            'not_found_in_trash' => __( 'No listings found in trash' )
        );
    
        $supports = array(
            'title', 'editor',
        );
        
        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'has_archive'       => true,
            'rewrite'           => array('slug' => 'listings'),
            'show_in_rest'      => true,
            'supports'          => $supports,
            'capability_type'   => 'post',
            'capabilities'      => array(
                // 'create_posts' => 'do_not_allow', 
            ),
            'register_meta_box_cb' => array( $this, 'add_meta_boxes' ),
        );
    
        register_post_type( 'listings', $args);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'ab-listings-id',
            'Listing',
            [$this, 'meta_box'],
            'listings',
            'normal',
            'default'
        );
    }

    public function meta_box($post)
    {
        global $pagenow;

        // arrays of values for dropdowns
        $brands_arr            = DataHelper::get_brands();
        $transmissions_arr     = DataHelper::get_transmissions();
        $body_types_arr        = DataHelper::get_body_types();
        $conditions_arr        = DataHelper::get_condititons();
        $fuel_types_arr        = DataHelper::get_fuel_types();
        $drive_types_arr       = DataHelper::get_drive_types();
        $features_arr          = DataHelper::get_features();

        $is_new_page    = $pagenow === 'post-new.php';

        $listing            = get_post($post->ID);
        $transmission_type  = esc_textarea($listing->transmission_type);
        $fuel_type          = esc_textarea($listing->fuel_type);
        $color              = esc_textarea($listing->color);
        $mileage            = esc_textarea($listing->mileage);
        $body_type          = esc_textarea($listing->body_type);
        $additional_info    = esc_textarea($listing->additional_info);

        require_once Main::get_path_from_src('Admin/partials/listing-meta-box/index.php');
    }

    public function save_meta( $post_id, $post )
    {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        // handle listing validations

        // save (or update) listings
    }

    public function set_custom_columns($columns)
    {
        
    }

    public function custom_column( $column, $post_id )
    {

    }
}