<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Entities\Listing;
use Autobrunei\Main;
use Autobrunei\Utils\ListingVisit;
use Autobrunei\Utils\Response;

class ViewListingPageController
{
    // [view_listing]
    public function view_listing()
    {

        // if other users try to edit the listing, show 404
        if (!isset($_GET['listing_id'])) {
            Response::not_found();
        }

        // initialise the listing object
        $listing = Listing::get_listing_by_id($_GET['listing_id']);

        ListingVisit::increment_count($listing);

        require_once Main::get_path_from_src('Front/partials/view-listing/index.php');
    }
}