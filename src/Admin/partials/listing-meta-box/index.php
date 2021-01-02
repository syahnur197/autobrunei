<div class="ab-form-layout">
    <?php require_once "_sidebar.php"; ?>
    <div class="ab-content">
        <?php require_once "_content-basic-car-info.php"; ?>
        <?php require_once "_content-features.php"; ?>
        <?php require_once "_content-price.php"; ?>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        // to add new listing
        const features_listing = $("#ab-features-list");
        const new_feature_input = `<div class="ab-feature-row">
            <input type="text" class="ab-form-control" name="feature[]" placeholder="Add new feature"/>
            <button class="ab-btn ab-btn-danger ab-delete-feature-btn" type="button">Delete</button>
        </div>`;

        $(document).on('click', "#add-feature-button", function(e) {
            features_listing.append(new_feature_input);
        });


        // handling sidebar
        $(document).on('click', '.ab-list-item', function(e) {
            let content = $(this).data('content');

            $(".ab-sub-content").removeClass('ab-d-block');
            $(".ab-sub-content").addClass('ab-d-hide');

            $(`#${content}`).addClass('ab-d-block');
            $(`#${content}`).removeClass('ab-d-hide');

            $(".ab-list-item").removeClass('active');
            $(this).addClass('active');
        });

        // delete feature row
        $(document).on('click', ".ab-delete-feature-btn", function(e) {
            $(this).parent().remove();
        });
    });
</script>