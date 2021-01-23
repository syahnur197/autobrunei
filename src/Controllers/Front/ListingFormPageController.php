<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Data\Helper;
use Autobrunei\Entities\Listing;
use Autobrunei\Main;
use Autobrunei\Utils\Request;
use Autobrunei\Utils\Session;
use Exception;

class ListingFormPageController
{
    // [listing_form_view]
    public function listing_form_view()
    {
        // arrays of values for dropdowns
        $brands_arr            = Helper::get_brands();
        $transmissions_arr     = Helper::get_transmissions();
        $body_types_arr        = Helper::get_body_types();
        $conditions_arr        = Helper::get_condititons();
        $fuel_types_arr        = Helper::get_fuel_types();
        $drive_types_arr       = Helper::get_drive_types();
        $features_arr          = Helper::get_features();

        // preload the models of a brand
        $selected_brand        = Helper::get_brands()[0];
        $models_arr            = Helper::get_brand_models($selected_brand);

        $listing               = isset($_GET['listing_id']) 
                                    ? Listing::get_listing_by_id($_GET['listing_id']) 
                                    : new Listing();

        require_once Main::get_path_from_src('Front/partials/listing-meta-box/index.php');
    }

    // POST wp-admin/admin-ajax.php
    // Payload: action=save_ab_listing
    public function save_ab_listing()
    {
        try {
            $this->_validate_save_post();
    
            $listing = $this->_get_listing_object();
    
            $success = $listing->save();
    
            wp_redirect( wp_get_referer() );
            exit;
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
            wp_redirect( wp_get_referer() );
            exit;
        }
    }

    private function _validate_save_post()
    {
        if ( !Request::validate_post_request()['success'] ) throw new Exception('Not a post request!');

        if ( !Request::validate_nonce()['success'] ) throw new Exception('Invalid nonce!');
    }

    private function _get_listing_object()
    {
        $data['ID']                 = $_POST['listing_id'];

        // getting the value from form
        $data['brand']              = $_POST['brand'];
        $data['model']              = $_POST['model'];
        $data['body_type']          = $_POST['body_type'];
        $data['colour']             = $_POST['colour'];
        $data['fuel_type']          = $_POST['fuel_type'];
        $data['transmission']       = $_POST['transmission'];
        $data['drive_type']         = $_POST['drive_type'];
        $data['year']               = $_POST['year'];
        $data['engine_no']          = $_POST['engine_no'];
        $data['condition']          = $_POST['condition'];
        $data['mileage']            = $_POST['mileage'];
        $data['price']              = $_POST['price'];
        $data['sale_price']         = $_POST['sale_price'];
        $data['sold']               = $_POST['sold'] ?? '';
        $data['features']           = $_POST['features'] ?? []; // this is an array
        $data['sellers_note']       = $_POST['sellers_note'];
        // $data['featured_image_url'] = $_POST['featured_image_url'];
        // $data['images_urls']        = $_POST['images_urls'];

        return new Listing((object) $data);
    }

}