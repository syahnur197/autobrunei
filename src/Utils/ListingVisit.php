<?php

namespace Autobrunei\Utils;

use Autobrunei\Entities\Listing;

class ListingVisit
{

    const VISIT_KEY = 'visit_count';

    public static function increment_count(Listing $listing)
    {

        $listing_id = $listing->getId();

        $has_visited_key = 'has_visited_listing_id_' . $listing_id;

        // check if user has visited a particular listing
        if (Session::exist($has_visited_key)) {
            return;
        };

        $count = 0;

        // get the visit count if post meta visit_count exist
        if (metadata_exists('post', $listing_id, self::VISIT_KEY)) {
            $count = (int) get_post_meta($listing_id, self::VISIT_KEY, true);
        }

        // increment visit_count by 1
        update_post_meta($listing_id, self::VISIT_KEY, (++$count));

        // set the user has visited the listing
        Session::set($has_visited_key, true);
    }

    public static function get_visit_count(Listing $listing): int
    {
        $listing_id = $listing->getId();

        if (metadata_exists('post', $listing_id, self::VISIT_KEY)) {
            return (int) get_post_meta($listing_id, self::VISIT_KEY, true);
        }

        return 0;
    }
}