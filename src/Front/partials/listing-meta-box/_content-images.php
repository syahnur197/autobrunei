<?php
    use Autobrunei\Utils\Session;
?>

<div id="images" class="ab-sub-content ab-d-hide">
    <h2 class="ab-h2 no-left-padding">Image Gallery</h2>

    <div class="ab-featured-img-container">

        <?php if ($listing->getFeaturedImageId() !== null && !Session::exist('error')): ?>
            <img id="ab-featured-img" slt="" src="<?= $listing->getFeaturedImageUrl(); ?>"/>
            <input type="hidden" id="featured_image_index" name="featured_image_index" value="<?= $listing->getFeaturedImageIndex(); ?>" />
        <?php else: ?>
            <img id="ab-featured-img" alt=""/>
            <input type="hidden" id="featured_image_index" name="featured_image_index" value="0" />
        <?php endif;?>
    </div>

    <div class="ab-img-container">
        <?php if ($listing->getImagesIds() !== null && !Session::exist('error')): ?>
            <?php foreach(json_decode($listing->getImagesUrls()) as $url): ?>
                <img src="<?= $url; ?>" class="non-featured-img" alt=""/>
            <?php endforeach; ?>
            <input type="hidden" id="images_ids" name="images_ids" value="<?= $listing->getImagesIds(); ?>" />
        <?php endif; ?>
    </div>

    <input type="hidden" id="files_uploaded" name="files_uploaded" value="0" />
    <input type="file" id="listing_images" name="listing_images[]" multiple class="ab-upload-listing-img-input ab-btn ab-btn-primary" accept="image/*"/>
</div>