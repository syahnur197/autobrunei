<?php 
    use Autobrunei\Entities\Listing;
?>

<?php if($loop->have_posts()): ?>
    <?php while ($loop->have_posts()): ?>
        <?php 
            $loop->the_post();
            $listing = new Listing($loop->post);
        ?>
        <div class="ab-listing">
            <a href="<?= $listing->get_view_listing_url(); ?>">
                <img class="ab-listing-image" src="<?= $listing->getFeaturedImageUrl(); ?>">
            </a>

            <div class="ab-listing-description">
                <span class="ab-listing-title"><?= $listing->getBrand() . ' ' . $listing->getModel() . " ({$listing->getYear()})"; ?></span>
                <br>

                <div class="ab-meta-description">
                    <div>
                        <span class="ab-attribute-title">Mileage</span> <br>
                        <?= $listing->getMileage(); ?> km
                    </div>
                    <div>
                        <span class="ab-attribute-title">Transmission</span> <br>
                        <?= $listing->getTransmission(); ?>
                    </div>
                    <div>
                        <span class="ab-attribute-title">Fuel Type</span> <br>
                        <?= $listing->getFuelType(); ?>
                    </div>
                </div>
            </div>

            <div class="ab-listing-description">
                <span class="ab-listing-pricing">
                    B$ <?= $listing->getPrice(); ?>
                </span>

                <br>

                <button type="button">
                    Add to compare
                </button>

            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
<?php 
    wp_reset_postdata();
    wp_reset_query();
?>