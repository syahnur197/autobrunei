<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Data\Helper;
use Autobrunei\Main;

class ListingPageController
{
    // [all_listings_view]
    public function all_listings_view()
    {
        $brands_arr = Helper::get_brands();
        $models_arr = Helper::get_brand_models($brands_arr[0]);
        $conditions_arr = Helper::get_condititons();

        $meta_query = array(
            array(
                'key'       => 'end_date',
                'value'     => time(),
                'compare'   => '>=',
            ),
        );


        $listings = get_posts([
            'post_type'     => 'ab-listings',
            'post_status'   => 'publish',
            'numberposts'   => -1,
            'meta_query'    => $meta_query,
        ]);
        
        require_once Main::get_path_from_src('Front/partials/all-listing-view/index.php');
    }

    public function filter_listings()
    {

        if (isset($_GET['brand'])) {
            $meta_query = array(
                array(
                    'key'       => 'brand',
                    'value'     => sanitize_text_field($_GET['brand']),
                    'compare'   => '==',
                )
            );
        }

        if (isset($_GET['brand']) && isset($_GET['model'])) {
            $meta_query = array(
                array(
                    'key'       => 'brand',
                    'value'     => sanitize_text_field($_GET['model']),
                    'compare'   => '==',
                )
            );
        }


        return get_posts([
            'post_type'     => 'ab-listings',
            'post_status'   => 'publish',
            'numberposts'   => -1,
            'meta_query'    => $meta_query,
        ]);
    }
}