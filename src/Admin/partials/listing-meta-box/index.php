<?php 
    use Autobrunei\Data\Helper;
    use Autobrunei\Utils\Request;
?>

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

        // models formatter
        function update_models_options(models) {
            let html_string = "";

            models.forEach(model => {
                html_string += `<option value='${model}'>${model}</option>`;
            });

            $("#model").html(html_string);
        }

        // fetch models based on brand
        function fetch_brand_models(brand) {
            let query_object = {
                action: 'get_models_by_brand',
                brand: brand,
                nonce: '<?= Request::get_nonce(); ?>'
            };

            let query_string = new URLSearchParams(query_object).toString();

            let url = `${ajaxurl}?${query_string}`;

            let models = null;

            $.get(url, function(data) {
                models = data.data.models;
                update_models_options(models);
            })
            .fail(function(data) {
                console.error(data.data.message);
            });
        }

        function navigate(content) {
            $(".ab-sub-content").removeClass('ab-d-block');
            $(".ab-sub-content").addClass('ab-d-hide');

            $(`#${content}`).addClass('ab-d-block');
            $(`#${content}`).removeClass('ab-d-hide');

            $(".ab-list-item").removeClass('active');

            $(".ab-list-item").find(`[data-content='${content}']`)
            
            $(`.ab-list-item[data-content='${content}']`).addClass('active');
        }

        fetch_brand_models("<?= Helper::get_brands()[0]; ?>");

        // handling sidebar
        $(document).on('click', '.ab-list-item', function(e) {
            let content = $(this).data('content');

            navigate(content);
        });

        $(document).on('click', '.ab-navigate-btn', function(e) {
            let content = $(this).data('content');

            navigate(content);
        });

        // fetching models based on brand
        $(document).on('change', "#brand", function(e) {
            let brand = $(this).val();

            let models = fetch_brand_models(brand);
        });

        // to add new listing
        // $(document).on('click', "#add-feature-button", function(e) {
        //     const features_listing = $("#ab-features-list");
        //     const new_feature_input = `<div class="ab-feature-row">
        //         <input type="text" class="ab-form-control" name="feature[]" placeholder="Add new feature"/>
        //         <button class="ab-btn ab-btn-danger ab-delete-feature-btn" type="button">Delete</button>
        //     </div>`;
        //     features_listing.append(new_feature_input);
        // });

        // delete feature row
        // $(document).on('click', ".ab-delete-feature-btn", function(e) {
        //     $(this).parent().remove();
        // });
    });
</script>