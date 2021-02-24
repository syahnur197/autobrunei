<?php

use Autobrunei\Utils\Str;

?>

<style>

td .ab-featured-image {
    width: 300px;
    height: 300px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
</style>

<div class="ab-comparison-container">
    <table>
        <thead>
            <th>Label</th>
            <?= Str::repeater($listings_nos, "<th>[placeholder]</th>"); ?>
        </thead>
        <tr>
            <td>Featured Image</td>
            <?= Str::repeater($listings_featured_images, "<td class='ab-img-container'><img src='[placeholder]' class='ab-featured-image'/></td>"); ?>
        </tr>
        <tr>
            <td>Title</td>
            <?= Str::repeater($listings_titles, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Brand</td>
            <?= Str::repeater($listings_brands, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Model</td>
            <?= Str::repeater($listings_models, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Year</td>
            <?= Str::repeater($listings_years, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Transmission</td>
            <?= Str::repeater($listings_transmissions, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Body Type</td>
            <?= Str::repeater($listings_body_types, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Fuel Type</td>
            <?= Str::repeater($listings_fuel_types, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Colours</td>
            <?= Str::repeater($listings_colours, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Engine No.</td>
            <?= Str::repeater($listings_engine_nos, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Mileages</td>
            <?= Str::repeater($listings_mileages, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Condition</td>
            <?= Str::repeater($listings_conditions, "<td>[placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Sale Price</td>
            <?= Str::repeater($listings_prices, "<td>BND [placeholder]</td>"); ?>
        </tr>
        <tr>
            <td>Features</td>
            <?= Str::repeater($listings_features, "<td>[placeholder]</td>"); ?>
        </tr>
    </table>
</div>