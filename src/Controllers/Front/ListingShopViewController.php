<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Data\Helper;
use Autobrunei\Entities\Listing;
use Autobrunei\Main;
use Autobrunei\Utils\Response;
use WP_Query;

class ListingShopViewController
{

    private $posts_per_page = 30;

    // [listing_shop_view]
    public function listing_shop_view()
    {
        $brands_arr            = Helper::get_brands();
        $selected_brand        = Helper::get_brands()[0];
        // $models_arr            = Helper::get_brand_models($selected_brand);

        
        $transmissions_arr     = Helper::get_transmissions();
        $body_types_arr        = Helper::get_body_types();
        $conditions_arr        = Helper::get_condititons();
        $fuel_types_arr        = Helper::get_fuel_types();
        $drive_types_arr       = Helper::get_drive_types();
        $features_arr          = Helper::get_features();

        require_once Main::get_path_from_src('Front/partials/shop-listing-view/index.php');
    }

    // GET wp-admin/admin-ajax.php?action=get_filtered_listings
    public function get_filtered_listings()
    {
        $loop = $this->_get_all_listings();

        if (!$loop->have_posts()) {
            Response::error('No listings found!');
        }

        $listings = [];

        $posts_count = $loop->post_count;

        while ($loop->have_posts()) {
            $loop->the_post();
            $listing = new Listing($loop->post);
            $listings[] = $listing->to_array();
        }

        $paged = ($_GET['paged']) ? (int) $_GET['paged'] : 1;

        // if the paged is greater than 1, we can assume it has previous page 
        $has_prev_page = $paged > 1;

        // if max per page is less than current post count,
        // we can assume it has no next page
        // however there will be a situation where the post count
        // is equal to the post_per_page and actually has no next page
        // but the below expression will return true
        // this will happend if the actual posts count is divisible by
        // post per page
        $has_next_page = $this->posts_per_page <= $posts_count; 

        Response::success([
            'listings' => $listings,
            'has_next_page' => $has_next_page,
            'has_prev_page' => $has_prev_page,
            'post_counts' => $posts_count,
            'paged' => $paged,
        ]);
    }

    private function _get_all_listings()
    {
        $meta_query = [
            [
                'key'       => 'end_date',
                'value'     => time(),
                'compare'   => '>=',
            ],
        ];

        $paged = ($_GET['paged']) ? $_GET['paged'] : 1;

        $args = [
            'posts_per_page' => $this->posts_per_page, 
            'post_status'    => 'publish',
            'paged'          => $paged,
            'post_type'      => Listing::POST_TYPE,
            'meta_query'     => $this->_get_meta_query($meta_query),
        ];

        return new WP_Query($args);
    }

    private function _get_meta_query($meta_query)
    {

        if (isset($_GET['brand'])) {

            $brand = sanitize_text_field($_GET['brand']);
            Helper::is_brand_exist($brand);

            $meta_query[]  = [
                'key'       => 'brand',
                'value'     => $brand,
                'compare'   => '=',

            ];
        }

        if (isset($_GET['model']) && isset($_GET['brand'])) {

            $brand = sanitize_text_field($_GET['brand']);
            $model = sanitize_text_field($_GET['model']);


            Helper::is_brand_model_exist($brand, $model);

            $meta_query[]  = [
                'key'       => 'model',
                'value'     => $model,
                'compare'   => '=',

            ];
        }

        if (isset($_GET['body_type'])) {
            $meta_query[]  = [
                'key'       => 'body_type',
                'value'     => sanitize_text_field($_GET['body_type']),
                'compare'   => '=',

            ];
        }

        if (isset($_GET['fuel_type'])) {
            $meta_query[]  = [
                'key'       => 'fuel_type',
                'value'     => sanitize_text_field($_GET['fuel_type']),
                'compare'   => '=',

            ];
        }

        if (isset($_GET['transmission'])) {
            $meta_query[]  = [
                'key'       => 'transmission',
                'value'     => sanitize_text_field($_GET['transmission']),
                'compare'   => '=',

            ];
        }

        if (isset($_GET['drive_type'])) {
            $meta_query[]  = [
                'key'       => 'drive_type',
                'value'     => sanitize_text_field($_GET['drive_type']),
                'compare'   => '=',

            ];
        }

        if (isset($_GET['year'])) {
            $meta_query[]  = [
                'key'       => 'year',
                'value'     => sanitize_text_field($_GET['year']),
                'compare'   => '=',

            ];
        }

        if (isset($_GET['condition'])) {
            $meta_query[]  = [
                'key'       => 'condition',
                'value'     => sanitize_text_field($_GET['condition']),
                'compare'   => '=',

            ];
        }

        if (isset($_GET['mileage_minimum']) && $_GET['mileage_minimum'] !== '') {
            $meta_query[]  = [
                'key'       => 'mileage',
                'value'     => (int) sanitize_text_field($_GET['mileage_minimum']),
                'compare'   => '>=',
            ];
        }

        if (isset($_GET['mileage_maximum']) && $_GET['mileage_maximum'] !== '') {
            $meta_query[]  = [
                'key'       => 'mileage',
                'value'     => (int) sanitize_text_field($_GET['mileage_maximum']),
                'compare'   => '<=',
            ];
        }

        if (isset($_GET['price_minimum']) && $_GET['price_minimum'] !== '') {
            $meta_query[]  = [
                'key'       => 'sale_price',
                'value'     => (int) sanitize_text_field($_GET['price_minimum']),
                'compare'   => '>=',
            ];
        }

        if (isset($_GET['price_maximum']) && $_GET['price_maximum'] !== '') {
            $meta_query[]  = [
                'key'       => 'sale_price',
                'value'     => (int) sanitize_text_field($_GET['price_maximum']),
                'compare'   => '<=',
            ];
        }


        return $meta_query;
    }
}