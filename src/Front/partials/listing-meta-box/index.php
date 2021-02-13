<?php

use Autobrunei\Main;
use Autobrunei\Utils\Request;
use Autobrunei\Utils\Session;

?>

<?php if(Session::exist('error')): 

    if (Session::exist('listing')) $listing = unserialize(Session::get('listing'));
    
?>
    <p style="font-size: 1.5em; color: red; font-weight: bold;"><?= Session::get('error', false); ?></p>

<?php endif;?>
<div class="ab-form-layout">
    <?php require_once "_sidebar.php"; ?>
    <div class="ab-content">
        <form method="POST" enctype="multipart/form-data" action="<?= esc_url(admin_url('admin-ajax.php')); ?>">
            <?php require_once "_content-basic-car-info.php"; ?>
            <?php require_once "_content-features.php"; ?>
            <?php require_once "_content-images.php"; ?>
            <input type="hidden" name="nonce" value="<?= Request::get_nonce(); ?>"/>
            <input type="hidden" name="action" value="save_ab_listing"/>
            <input type="hidden" name="listing_id" value="<?= $_GET['listing_id'] ?? ''; ?>"/>
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<?php require_once Main::get_path_from_src('Front/partials/_shared/_update-model-script.php'); ?>


<script>
    <?php 
        // if you are wondering why the comments are put in php block
        // is so that it won't get rendered in the html page
    ?>
    jQuery(document).ready(function($) {
        <?php 
            // if $listing has images ids, put into selected_image_sources_array js variable
            $selected_image_sources_array = "";
            if ($listing->getImagesIds() !== null && !Session::exist('error')) {
                foreach(json_decode($listing->getImagesUrls()) as $url) {
                    $selected_image_sources_array .= "'" . $url . "',";
                }
            }
        ?>

        let selected_image_sources_array = [<?= $selected_image_sources_array; ?>];
        let featured_image_index = 0;

        function navigate(content) {
            $(".ab-sub-content").removeClass('ab-d-block');
            $(".ab-sub-content").addClass('ab-d-hide');

            $(`#${content}`).addClass('ab-d-block');
            $(`#${content}`).removeClass('ab-d-hide');

            $(".ab-list-item").removeClass('active');

            $(".ab-list-item").find(`[data-content='${content}']`)
            
            $(`.ab-list-item[data-content='${content}']`).addClass('active');
        }

        <?php
            // handling sidebar
        ?>

        $(document).on('click', '.ab-list-item', function(e) {
            let content = $(this).data('content');

            navigate(content);
        });

        $(document).on('click', '.ab-navigate-btn', function(e) {
            let content = $(this).data('content');

            navigate(content);
        });

        $(document).on('click', '.non-featured-img', function(e) {
            let featured_img = $("#ab-featured-img");

            let featured_img_src = featured_img.attr('src');

            let clicked_img = $(this);
            
            let clicked_img_src = clicked_img.attr('src');

            if (clicked_img_src !== featured_img_src) {
                featured_img.attr('src', clicked_img_src);

                <?php 
                    // getting the featured image index
                ?>

                featured_image_index = selected_image_sources_array.indexOf(clicked_img_src);

                <?php 
                    // setting the featured image index based on the image clicked by the user
                ?>

                $("#featured_image_index").val(featured_image_index);
            }
        });

        $(document).on("change", ".ab-upload-listing-img-input", function(e) {
            const element = e.target;
            const files = element.files;

            if (!FileReader || !files || !files.length) {
                console.error('operation not supported!')
                return;
            }

            const images_src_array = [];
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                const file_reader = new FileReader();

                file_reader.onload = async function(e) {
                    images_src_array.push(e.target.result);

                    <?php
                        // return early if index is not the last file
                    ?>

                    if (i !== files.length - 1) {
                        return;
                    }

                    <?php 
                        // I'm doing this for the sake of selected featured image
                    ?>

                    selected_image_sources_array = images_src_array;

                    <?php 
                        // run these functions at the end of the loop
                        // the reason why I put it here instead at below file_reader.readAsDataURL(file)
                        // because of the asynchronous nature of FileReader
                    ?>

                    update_features_image(images_src_array[0]);
                    update_images(images_src_array);
                }
                
                file_reader.readAsDataURL(file);
            }

            <?php
                // to check whether file is uploaded through the file input form
            ?>

            $("#files_uploaded").val("1");

        });

        function update_features_image(image_source_str) {
            $('#ab-featured-img').attr('src', image_source_str);
        }

        function update_images(images_src_array) {
            if (!Array.isArray(images_src_array)) {
                console.error('Argument is not an array!');
                return;
            }

            let img_container_string = "";
            let img_container = $(".ab-img-container");
            img_container.empty();

            let images_urls_array = [];

            images_src_array.map(function(image_src) {
                img_container_string += `<img src="${image_src}" class="non-featured-img" alt=""/>`;

                images_urls_array.push(image_src);
            });

            img_container_string += `<p>Click one of the images above to change the featured image</p>`;

            $(".ab-img-container").append(img_container_string);
        }
    });
</script>