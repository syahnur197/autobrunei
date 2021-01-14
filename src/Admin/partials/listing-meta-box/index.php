<?php 
    use Autobrunei\Data\Helper;
    use Autobrunei\Utils\Request;
?>

<div class="ab-form-layout">
    <?php require_once "_sidebar.php"; ?>
    <div class="ab-content">
        <?php require_once "_content-basic-car-info.php"; ?>
        <?php require_once "_content-features.php"; ?>
        <?php require_once "_content-images.php"; ?>
        <input type="hidden" name="nonce" value="<?= Request::get_nonce(); ?>"/>
        <input type="hidden" name="save_post" value="1"/>
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

        const selected_brand = $('#brand').val();

        // fetch_brand_models(selected_brand);

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

        $(document).on('click', '.non-featured-img', function(e) {
            let featured_img = $("#ab-featured-img");

            let featured_img_src = featured_img.attr('src');

            let clicked_img = $(this);
            
            let clicked_img_src = clicked_img.attr('src');

            if (clicked_img_src !== featured_img_src) {
                featured_img.attr('src', clicked_img_src);
            }
        });

        $(document).on("click", ".ab-upload-listing-img-btn", function (e) {
            e.preventDefault();
            let $button = $(this);


            // Create the media frame.
            let file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or upload image',
                library: { 
                    type: 'image' 
                },
                button: {
                    text: 'Select'
                },
                multiple: true
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                $('#ab-featured-img').attr('src', '');

                let featured_img = file_frame.state().get('selection').first().toJSON();

                $('#ab-featured-img').attr('src', featured_img.url);
                
                let selections = file_frame.state().get('selection');

                let img_container_string = "";
                let img_container = $(".ab-img-container");
                img_container.empty();
                selections.map( function( attachment ) {
                    attachment = attachment.toJSON();

                    
                    img_container_string += `<img src="${attachment.url}" class="non-featured-img" alt=""/>`;
                });

                img_container_string += `<p>Click one of the images above to change the featured image</p>`;

                $(".ab-img-container").append(img_container_string);
            });

            // Finally, open the modal
            file_frame.open();
        });
    });
</script>