<style>

.featured-image {
    height: 700px;
    width: 1100px;
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
    height: 250px;
    width: 250px;
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


</style>
<?php
    use Autobrunei\Entities\Listing;
use Autobrunei\Utils\ListingVisit;

/** @var Listing $listing */
    $listing;
?>
<div class="ab-listing-container">
    <img src="<?= $listing->getFeaturedImageUrl()?>" class="featured-image"/>

    <div class="ab-listing-images">
        <?php foreach(json_decode($listing->getImagesUrls()) as $image_url): ?>
            <figure class="ab-image-figure"><img class="ab-image" src="<?= $image_url; ?>"/></figure>
        <?php endforeach; ?>
    </div>

    <div>
        <lable><strong>Brand: </strong></lable><?= $listing->getBrand(); ?> <br>
        <lable><strong>Model: </strong></lable><?= $listing->getModel(); ?> <br>
        <lable><strong>Year: </strong></lable><?= $listing->getYear(); ?> <br>
        <lable><strong>Transmission: </strong></lable><?= $listing->getTransmission(); ?> <br>
        <lable><strong>Fuel Type: </strong></lable><?= $listing->getFuelType(); ?> <br>
        <lable><strong>Body Type: </strong></lable><?= $listing->getBodyType(); ?> <br>
        <lable><strong>Colour: </strong></lable><?= $listing->getColour(); ?> <br>
        <lable><strong>Engine No: </strong></lable><?= $listing->getEngineNo(); ?> <br>
        <lable><strong>Mileage: </strong></lable><?= $listing->getMileage(); ?> <br>
        <lable><strong>Condition: </strong></lable><?= $listing->getCondition(); ?> <br>
        <lable><strong>Price: B$ </strong></lable><?= $listing->getPrice(); ?> <br>
        <lable><strong>Sale Price: B$ </strong></lable><?= $listing->getSalePrice(); ?> <br>
    </div>

    <hr>

    <div>
        <h4>Seller's Note</h4>
        <div>
            <?= $listing->getSellersNote(); ?>
        </div>
    </div>

    <hr>

    <div>
        <h4>Features</h4>
        <ul>
            <?php foreach($listing->getFeatures() as $feature): ?>
                <li><?=$feature; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <hr>

    <h5>Total Unique Visitors: <strong><?= ListingVisit::get_visit_count($listing); ?></strong></h5>
</div>

<script>

    jQuery(document).ready(function($) {
        
        $(document).on('click', '.ab-image', function(e) {
            const source = e.target.src;
            
            $('.featured-image').attr('src', source);

        })

    })

</script>