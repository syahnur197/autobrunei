<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Entities\Listing;
use Autobrunei\Main;

class ViewListingPageController
{
    // [view_listing]
    public function view_listing()
    {
        // initialise the listing object
        $listing = Listing::get_listing_by_id($_GET['listing_id']);

        require_once Main::get_path_from_src('Front/partials/view-listing/index.php');
    }
}