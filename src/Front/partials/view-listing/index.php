<style>

.featured-image {
    height: auto;
    width: 100%;
    object-fit: cover;
}

.ab-listing-images {
    margin-top: 10px;
    display: flex;
    flex-direction: row;
    flex-flow: wrap;
}

.ab-image-figure {
	width: 200px;
	height: 200px;
	margin: 0;
	padding: 0;
	background: white;
	overflow: hidden;
    margin-right: 5px;
}

.ab-image-figure:last-child {
    margin-right: 0px;
}

.ab-image {
    height: 75%; 
    width: 100%; 
    object-fit: contain;
    opacity: 1;
	-webkit-transform: scale(1);
	transform: scale(1);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}

.ab-image:hover {
    opacity: .8;
	-webkit-transform: scale(1.3);
	transform: scale(1.3);
}

@media only screen and (max-width: 768px) {

    .ab-image {
        height: 40%;
        width: 40%;
    }
    .featured-image {
        height: auto;
        width: 100%;
        object-fit: cover;
    }

}

</style>
<?php
    use Autobrunei\Entities\Listing;
    use Autobrunei\Utils\ListingVisit;

    /** @var Listing $listing */
    $listing;
?>
<div class="ab-listing-container">

    <h1><?= $listing->getBrand(); ?> <?= $listing->getModel(); ?> <?= $listing->getYear(); ?></h1>
    <br>
    <img src="<?= $listing->getFeaturedImageUrl()?>" class="featured-image"/>

    <div class="ab-listing-images">
        <?php foreach(json_decode($listing->getImagesUrls()) as $image_url): ?>
            <figure class="ab-image-figure"><img class="ab-image" src="<?= $image_url; ?>"/></figure>
        <?php endforeach; ?>
    </div>

    <div class="vc_row wpb_row section vc_row-fluid  vc_custom_1613292112866 grid_section" >
    <div class="section_inner clearfix" >
    <div class="section_inner_margin clearfix" >

        <div class="wpb_column vc_column_container vc_col-sm-6">
        <div class="vc_column-inner" style="border-left: 1px solid #000;">
        <div class="wpb_wrapper">
        <div class="vc_empty_space" style="height: 32px"></div>
            <h4><strong>DETAILS</strong></h4>
            <div class="vc_empty_space" style="height: 32px"></div>

            <lable>Brand: </lable><strong><?= $listing->getBrand(); ?> </strong><br>
            <lable>Model: </lable> <strong><?= $listing->getModel(); ?> </strong><br>
            <lable>Year: </lable><strong><?= $listing->getYear(); ?> </strong><br>
            <lable>Transmission: </lablel><strong><?= $listing->getTransmission(); ?> </strong><br>
            <lable>Fuel Type: </lable><strong><?= $listing->getFuelType(); ?> </strong><br>
            <lable>Body Type: </lable><strong><?= $listing->getBodyType(); ?></strong> <br>
            <lable>Colour: </lable><strong><?= $listing->getColour(); ?> </strong><br>
            <lable>Engine No: </lable><strong><?= $listing->getEngineNo(); ?> </strong><br>
            <lable>Mileage: </lable><strong><?= $listing->getMileage(); ?> </strong><br>
            <lable>Condition: </lable><strong><?= $listing->getCondition(); ?> </strong><br>
            <lable>Price: B$ </lable><strong><?= $listing->getPrice(); ?> </strong><br>
            <div class="vc_empty_space" style="height: 32px"></div>
        </div>
        </div>
        </div>

        <div class="wpb_column vc_column_container vc_col-sm-6" style="background:#dcdcdd; padding:20px;">
        <div class="vc_column-inner">
        <div class="wpb_wrapper">
        <div class="vc_empty_space" style="height: 32px"></div>
            <h4><strong>FEATURES</strong></h4>
            <div class="vc_empty_space" style="height: 32px"></div>
            <ul>
                <?php foreach($listing->getFeatures() as $feature): ?>
                    <li><?=$feature; ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="vc_empty_space" style="height: 32px"></div>
        </div>
        </div>
        </div>

    </div>
    </div>
    </div>

    <div class="vc_empty_space" style="height: 32px"></div>
    <hr>

    <div>
        <div class="vc_empty_space" style="height: 32px"></div>
        <h4>Seller's Note:</h4>
        <div class="vc_empty_space" style="height: 32px"></div>
        <div>
            <?= $listing->getSellersNote(); ?>
        </div>
    </div>

    <hr>

    <div>
        <div class="vc_empty_space" style="height: 32px"></div>
        <h4>Contact the seller</h4>
        <div class="vc_empty_space" style="height: 32px"></div>
        <div>
            <h5>Mobile no: <?= $listing->getPhoneNo(); ?></h5>
            <a href="<?= $listing->get_whatsapp_link(); ?>">
                <img src="/wp-content/uploads/2021/02/WhatsApp-icon-PNG.png" width="80px"/>
            </a>
        </div>
    </div>

    <div class="vc_empty_space" style="height: 32px"></div>

    <hr>

    <h5>Total Unique Visitors: <strong><?= ListingVisit::get_visit_count($listing); ?></strong></h5>
    <div class="vc_empty_space" style="height: 52px"></div>
</div>


<script>

    jQuery(document).ready(function($) {
        
        $(document).on('click', '.ab-image', function(e) {
            const source = e.target.src;
            
            $('.featured-image').attr('src', source);

        })

    })

</script>