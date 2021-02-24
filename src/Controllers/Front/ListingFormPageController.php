<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Data\Helper;
use Autobrunei\Entities\Listing;
use Autobrunei\Main;
use Autobrunei\Utils\Request;
use Autobrunei\Utils\Response;
use Autobrunei\Utils\Session;
use Exception;
use InvalidArgumentException;

class ListingFormPageController
{
    // [listing_form_view]
    public function listing_form_view()
    {
        if (!is_user_logged_in()) {
            Response::not_found();
        }

        // arrays of values for dropdowns
        $brands_arr            = Helper::get_brands();
        $transmissions_arr     = Helper::get_transmissions();
        $body_types_arr        = Helper::get_body_types();
        $conditions_arr        = Helper::get_condititons();
        $fuel_types_arr        = Helper::get_fuel_types();
        $drive_types_arr       = Helper::get_drive_types();
        $features_arr          = Helper::get_features();

        // initialise the listing object
        $listing = isset($_GET['listing_id']) 
            ? Listing::get_listing_by_id($_GET['listing_id']) 
            : new Listing();

        $models_arr = isset($_GET['listing_id']) 
            ? Helper::get_brand_models($listing->getBrand())
            : Helper::get_brand_models($selected_brand = Helper::get_brands()[0]);

        // if other users try to edit the listing, show 404
        $user_id = get_current_user_id();
        if (isset($_GET['listing_id']) && !$listing->is_author($user_id)) {
            Response::not_found();
        }

        require_once Main::get_path_from_src('Front/partials/listing-meta-box/index.php');

        Session::unset('error');
    }

    // POST wp-admin/admin-ajax.php
    // Payload: action=save_ab_listing
    public function save_ab_listing()
    {
        global $wpdb;

        $wpdb->query( "START TRANSACTION" );
        try {
            $this->_validate_save_post();
    
            $listing = $this->_get_listing_object();

            $this->_validate_listing($listing);

            $files = $_FILES["listing_images"];

            $success = $listing->save();

            // in the frontend, I changed the value of files_uploaded to "1"
            // whenever the user uploaded new files from the input
            // with a class of .ab-upload-listing-img-input
            $files_uploaded = $_POST['files_uploaded'] === "1";

            $listing->save_attachments($files, $files_uploaded);

            $wpdb->query( "COMMIT" );
    
            // wp_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ).'/vehicle-listing' );
            wp_redirect( wp_get_referer() );
            exit;
        } catch (Exception $e) {

            $listing  = $listing instanceof Listing ? $listing : new Listing();

            Session::set('listing', serialize($listing));
            Session::set('error', $e->getMessage());
            $wpdb->query( "ROLLBACK" );
            wp_redirect( wp_get_referer() );
            exit;
        }
    }

    private function _validate_save_post()
    {
        if ( !Request::validate_post_request()['success'] ) throw new Exception('Not a post request!');

        if ( !Request::validate_nonce()['success'] ) throw new Exception('Invalid nonce!');

    }

    private function _get_listing_object(): Listing
    {
        $data['ID']                   = $_POST['listing_id'];

        // getting the value from form
        $data['brand']                = $_POST['brand'];
        $data['model']                = $_POST['model'];
        $data['body_type']            = $_POST['body_type'];
        $data['colour']               = $_POST['colour'];
        $data['fuel_type']            = $_POST['fuel_type'];
        $data['transmission']         = $_POST['transmission'];
        $data['drive_type']           = $_POST['drive_type'];
        $data['year']                 = $_POST['year'];
        $data['engine_no']            = $_POST['engine_no'];
        $data['condition']            = $_POST['condition'];
        $data['mileage']              = $_POST['mileage'];
        $data['price']                = $_POST['price'];
        $data['sold']                 = $_POST['sold'] ?? '';
        $data['features']             = $_POST['features'] ?? []; // this is an array
        $data['sellers_note']         = $_POST['sellers_note'];
        $data['phone_no']             = $_POST['phone_no'];
        $data['featured_image_index'] = $_POST['featured_image_index'];

        return new Listing((object) $data);
    }

    private function _validate_listing(Listing $listing)
    {
        if (strlen((string) $listing->getPhoneNo()) !== Listing::PHONE_NO_LENGTH) {
            throw new InvalidArgumentException('Phone number length must be ' . Listing::PHONE_NO_LENGTH . ' digits!');
        }

        if (empty($listing->getPrice()) || $listing->getPrice() === '') {
            throw new InvalidArgumentException('Sale price must not be empty!');
        }

        if (empty($listing->getMileage()) || $listing->getMileage() === '') {
            throw new InvalidArgumentException('Mileage must not be empty!');
        }
    }

}
