<div id="images" class="ab-sub-content ab-d-hide">
    <h2 class="ab-h2 no-left-padding">Image Gallery</h2>

    <div class="ab-featured-img-container">

        <?php if (isset($listing->featured_image_id)): ?>
            <img id="ab-featured-img" slt="" src="<?= $listing->featured_image_url; ?>"/>
        <?php else: ?>
            <img id="ab-featured-img" alt=""/>
        <?php endif;?>
    </div>

    <div class="ab-img-container">
        <?php if (isset($listing->images_ids)): ?>
            <?php foreach(json_decode($listing->images_urls) as $url): ?>
                <img src="<?= $url; ?>" class="non-featured-img" alt=""/>
            <?php endforeach; ?>
        <?php endif; ?>
    
    </div>

    <input type="file" id="listing_images" name="listing_images[]" multiple class="ab-upload-listing-img-input ab-btn ab-btn-primary" accept="image/*"/>
</div>