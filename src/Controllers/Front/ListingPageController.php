<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Data\Helper;
use Autobrunei\Entities\Listing;
use Autobrunei\Main;
use WP_Query;

class ListingPageController
{
    // [all_listings_view]
    public function all_listings_view()
    {
        $brands_arr = Helper::get_brands();
        $models_arr = Helper::get_brand_models($brands_arr[0]);
        $conditions_arr = Helper::get_condititons();

        $meta_query = [
            [
                'key'       => 'end_date',
                'value'     => time(),
                'compare'   => '>=',
            ],
        ];

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = [
            'posts_per_page' => -1, 
            'post_status'    => 'publish',
            'paged'          => $paged,
            'post_type'      => Listing::POST_TYPE,
            'meta_query'     => $this->_filter_listings($meta_query),
        
            // order by subscription end date, the one that expires 
            // the latest will be first in the list
            'order'          => 'DESC',
            'orderby'        => 'meta_value_num',
            'meta_key'       => Listing::SUB_END_DATE,
        ];

        $loop = new WP_Query($args);
        
        require_once Main::get_path_from_src('Front/partials/all-listing-view/index.php');
    }

    private function _filter_listings($meta_query)
    {

        if (isset($_GET['year'])) {
            $meta_query[]  = [
                'key'       => 'year',
                'value'     => sanitize_text_field($_GET['year']),
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