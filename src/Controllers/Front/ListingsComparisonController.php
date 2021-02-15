<?php

namespace Autobrunei\Controllers\Front;

use Autobrunei\Entities\Listing;
use Autobrunei\Main;
use Autobrunei\Utils\Response;

class ListingsComparisonController
{
    // [listings_comparison]
    public function listings_comparison()
    {
        if (!isset($_GET['listing_ids'])) {
            Response::not_found();
        }
        
        $listing_ids = $_GET['listing_ids'];

        $listings = [];

        
        foreach ($listing_ids as $key => $listing_id) {
            $listings[]                 = $listing= Listing::get_listing_by_id($listing_id);

            $listings_nos[]             = "Listing No. " . ($key+1);


            $listings_featured_images[] = $listing->getFeaturedImageUrl();
            $listings_titles[]          = "<a href='" . $listing->get_view_listing_url() . "'>" . $listing->getTitle() . "</a>";
            $listings_brands[]          = $listing->getBrand();
            $listings_models[]          = $listing->getModel();
            $listings_years[]           = $listing->getYear();
            $listings_transmissions[]   = $listing->getTransmission();
            $listings_body_types[]      = $listing->getBodyType();
            $listings_fuel_types[]      = $listing->getFuelType();
            $listings_colours[]         = $listing->getColour();
            $listings_engine_nos[]      = $listing->getEngineNo();
            $listings_mileages[]        = $listing->getMileage();
            $listings_conditions[]      = $listing->getCondition();
            $listings_prices[]          = $listing->getPrice();
            $listings_sale_prices[]     = $listing->getSalePrice();

            $features                   =  $listing->getFeatures();

            $string = "<ul>";
            foreach($features as $feature) {
                $string .= "<li>$feature</li>";
            }
            $string .= "<ul>";

            $listings_features[] = $string;
        }
        

        require_once Main::get_path_from_src('Front/partials/listings-comparison/index.php');
    }

}