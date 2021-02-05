<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Data\Helper;
use Autobrunei\Entities\Listing;
use Autobrunei\Main;
use WP_Query;

class ListingShopViewController
{

    private $posts_per_page = 60;

    // [listing_show_view]
    public function listing_show_view()
    {
        $brands_arr            = Helper::get_brands();
        $selected_brand        = Helper::get_brands()[0];
        $models_arr            = Helper::get_brand_models($selected_brand);

        
        $transmissions_arr     = Helper::get_transmissions();
        $body_types_arr        = Helper::get_body_types();
        $conditions_arr        = Helper::get_condititons();
        $fuel_types_arr        = Helper::get_fuel_types();
        $drive_types_arr       = Helper::get_drive_types();
        $features_arr          = Helper::get_features();

        $loop                  = $this->_get_all_listings();

        require_once Main::get_path_from_src('Front/partials/shop-listing-view/index.php');
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

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = [
            'posts_per_page' => $this->posts_per_page, 
            'post_status'    => 'publish',
            'paged'          => $paged,
            'post_type'      => Listing::POST_TYPE,
            'meta_query'     => $this->_filter_listings($meta_query),
        ];

        return new WP_Query($args);
    }

    private function _filter_listings($meta_query)
    {

        if (isset($_GET['condition'])) {
            $meta_query[]  = [
                'key'       => 'condition',
                'value'     => sanitize_text_field($_GET['condition']),
                'compare'   => '=',

            ];
        }

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


        return $meta_query;
    }
}