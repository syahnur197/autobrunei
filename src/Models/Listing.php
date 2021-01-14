<?php

namespace Autobrunei\Models;

use Autobrunei\Data\Helper as DataHelper;
use Autobrunei\Main;
use Autobrunei\Utils\AdminNotice;
use Autobrunei\Utils\Request;
use Exception;
use InvalidArgumentException;

/**
 * Aku pakai interface just to make the models methods are consistent
 */
class Listing implements ModelInterface
{

    private $error_message = '';

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
            'title',
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
        // to allow wp.media in the front end
        wp_enqueue_media();

        add_meta_box(
            'ab-listings-id',
            'Listing',
            [$this, 'meta_box'],
            'listings',
            'normal',
            'default'
        );

        add_meta_box(
            'sellers-note-id',
            'Seller\'s Note',
            [$this, 'sellers_note_meta_box'],
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

        $listing     = get_post($post->ID);
        $models_arr  = isset($listing->brand) ? DataHelper::get_brand_models($listing->brand) 
            : DataHelper::get_brand_models(DataHelper::get_brands()[0]);

        require_once Main::get_path_from_src('Admin/partials/listing-meta-box/index.php');
    }
    
    public function sellers_note_meta_box($post)
    {
        global $pagenow;

        $is_new_page    = $pagenow === 'post-new.php';

        $listing        = get_post($post->ID);
        $sellers_note   = esc_textarea($listing->sellers_note);

        $editor_setting = [
            'media_buttons' => false,
            'drag_drop_upload' => false,
        ]; 

        wp_editor( $sellers_note, 'sellers_note', $editor_setting );
    }

    public function save_meta( $post_id, $post )
    {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        $validate_post = Request::validate_post_request( false );

        if ( ! $validate_post['success'] ) {
            throw new Exception('Not a post request!');
        }

        $validate_nonce = Request::validate_nonce( false );

        if ( ! $validate_nonce['success'] ) {
            throw new Exception('Invalid nonce!');
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        // handle listing validations
        $listing_data = $this->_get_posted_listing_data();

        foreach($listing_data as $key => $data) {
            update_post_meta($post_id, $key, $data);
        }
    }

    private function _get_posted_listing_data(): array
    {
        // getting the value from form
        $brand = esc_textarea( $_POST['brand'] );
        $model = esc_textarea( $_POST['model'] );
        $body_type = esc_textarea( $_POST['body_type'] );
        $colour = esc_textarea( $_POST['colour'] );
        $fuel_type = esc_textarea( $_POST['fuel_type'] );
        $transmission = esc_textarea( $_POST['transmission'] );
        $drive_type = esc_textarea( $_POST['drive_type'] );
        $year = esc_textarea( $_POST['year'] );
        $engine_no = esc_textarea( $_POST['engine_no'] );
        $condition = esc_textarea( $_POST['condition'] );
        $mileage = esc_textarea( $_POST['mileage'] );
        $price = esc_textarea( $_POST['price'] );
        $sale_price = esc_textarea( $_POST['sale_price'] );
        // $sold = esc_textarea( $_POST['sold'] );
        // $features = $_POST['feature[]']; // this is an array
        $sellers_note = esc_textarea( $_POST['sellers_note'] );

        // validate data

        try {
            DataHelper::is_brand_exist($brand);
            DataHelper::is_brand_model_exist($brand, $model);
            DataHelper::is_body_type_exist($body_type);
            DataHelper::is_fuel_type_exist($fuel_type);
            DataHelper::is_transmission_exist($transmission);
            DataHelper::is_drive_type_exist($drive_type);
            DataHelper::is_condition_exist($condition);

            // sanitize the feature in features
            // foreach($features as $key => $feature) {
            //     DataHelper::is_feature_exist($feature);
            //     $features[$key] = esc_textarea($feature);
            // }

        } catch (InvalidArgumentException $e) {
            $this->error_message = $e->getMessage();
            
            AdminNotice::displayError(__($this->error_message));

            $previous_page_url = wp_get_referer() ;

            wp_redirect( $previous_page_url );

            exit;
        } 

        return compact(
            'brand',
            'model',
            'body_type',
            'colour',
            'fuel_type',
            'transmission',
            'drive_type',
            'year',
            'engine_no',
            'condition',
            'mileage',
            'price',
            'sale_price',
            'sold',
            'features',
            'sellers_note',
        );
    }

    // I fucking hate this approach bro
    public function save_post_error()
    {
        ?>
            <div class="notice notice-error is-dismissible">
                <p><?= $this->error_message; ?></p>
            </div>
        <?php
    }

    public function set_custom_columns($columns)
    {
        
    }

    public function custom_column( $column, $post_id )
    {

    }
}