<?php

namespace Autobrunei\Controllers\Admin;

use Autobrunei\Data\Helper as DataHelper;
use Autobrunei\Utils\Request;
use Autobrunei\Utils\Response;

class ListingController
{
    // GET wp-admin/admin-ajax.php?action=get_models_by_brand
    public function get_models_by_brand()
    {
        Request::validate_get_request(true);

        $brand = esc_textarea( $_GET['brand'] );

        $models = DataHelper::get_brand_models($brand);

        Response::success([
            'models' => $models,
        ]);
    }
}