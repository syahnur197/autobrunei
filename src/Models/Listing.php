<?php

namespace Autobrunei\Models;

use Exception;
use Autobrunei\Main;
use Autobrunei\Utils\Request;
use Autobrunei\Utils\AdminNotice;
use Autobrunei\Data\Helper as DataHelper;

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

        $is_new_page           = $pagenow === 'post-new.php';

        $listing               = get_post($post->ID);

        // preload the models of a brand
        $selected_brand        = $listing->brand ?? DataHelper::get_brands()[0];
        $models_arr            = DataHelper::get_brand_models($selected_brand);

        // this is for to check the listing's features
        $listing_features      = json_decode($listing->features) ?? [];

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
        // copy pasted codes, don't ask me
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // I have to add this because save_meta method always run
        // even though I just click "add listing" button
        // this code checks if save_post is posted, 
        // if not then stop this code execution
        if ( ! isset($_POST['save_post']) ) return;

        if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;


        try {

            $this->_validate_save_post();

            // handle listing validations
            $listing_data = $this->_get_posted_listing_data();

            $this->_save_listing_metas($listing_data, $post_id);

        } catch (Exception $e) {

            AdminNotice::displayError($e->getMessage());

            wp_redirect( wp_get_referer() );

            exit;
        } 
    }

    private function _get_posted_listing_data(): array
    {
        // getting the value from form
        $data['brand']          = $_POST['brand'];
        $data['model']          = $_POST['model'];
        $data['body_type']      = $_POST['body_type'];
        $data['colour']         = $_POST['colour'];
        $data['fuel_type']      = $_POST['fuel_type'];
        $data['transmission']   = $_POST['transmission'];
        $data['drive_type']     = $_POST['drive_type'];
        $data['year']           = $_POST['year'];
        $data['engine_no']      = $_POST['engine_no'];
        $data['condition']      = $_POST['condition'];
        $data['mileage']        = $_POST['mileage'];
        $data['price']          = $_POST['price'];
        $data['sale_price']     = $_POST['sale_price'];
        $data['sold']           = $_POST['sold'] ?? '';
        $data['features']       = $_POST['features']; // this is an array
        $data['sellers_note']   = $_POST['sellers_note'];

        // validate data

        DataHelper::is_brand_exist($data['brand']);
        DataHelper::is_brand_model_exist($data['brand'], $data['model']);
        DataHelper::is_body_type_exist($data['body_type']);
        DataHelper::is_fuel_type_exist($data['fuel_type']);
        DataHelper::is_transmission_exist($data['transmission']);
        DataHelper::is_drive_type_exist($data['drive_type']);
        DataHelper::is_condition_exist($data['condition']);

        // sanitize the feature in features
        foreach($data['features'] as $key => $feature) {
            DataHelper::is_feature_exist($feature);
            $features[$key] = sanitize_text_field($feature);
        }
        
        return $data;
    }

    private function _save_listing_metas(iterable $listing_data, int $post_id)
    {
        foreach($listing_data as $key => $data) {

            if ($key === 'features') {
                update_post_meta($post_id, $key, json_encode($data));
            } else {
                // sanitize before storing
                $data = sanitize_text_field($data);

                update_post_meta($post_id, $key, $data);
            }

        }
    }

    private function _validate_save_post()
    {
        if ( !Request::validate_post_request()['success'] ) throw new Exception('Not a post request!');

        if ( !Request::validate_nonce()['success'] ) throw new Exception('Invalid nonce!');
    }

    public function set_custom_columns($columns)
    {
		$columns['brand']       = __('Brand');
		$columns['model']       = __('Model');
		$columns['body_type']   = __('Body Type');
		$columns['colour']      = __('Colour');
        $columns['year']        = __('Year');
        
        return $columns;
    }

    public function custom_column( $column, $post_id )
    {
        $listing = get_post($post_id);

        switch ($column) {
            case 'brand':
                echo $listing->brand;
                break;
            case 'model':
                echo $listing->model;
                break;
            case 'body_type':
                echo $listing->body_type;
            case 'colour':
                echo $listing->colour;
                break;
            case 'year':
                echo $listing->year;
    }
    }
}