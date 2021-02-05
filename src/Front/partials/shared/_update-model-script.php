<?php
use Autobrunei\Utils\Request;
?>
<script>
jQuery(document).ready(function($) {
    
    function update_models_options(models) {
        let html_string = "";
    
        models.forEach(model => {
            html_string += `<option value='${model}'>${model}</option>`;
        });
    
        $("#model").html(html_string);
    }
    
    function fetch_brand_models(brand) {
        let query_object = {
            action: 'get_models_by_brand',
            brand: brand,
            nonce: '<?= Request::get_nonce(); ?>'
        };
    
        let query_string = new URLSearchParams(query_object).toString();
    
        let url = `<?= esc_url(admin_url('admin-ajax.php')); ?>?${query_string}`;
    
        let models = null;
    
        $.get(url, function(data) {
            models = data.data.models;
            update_models_options(models);
        })
        .fail(function(data) {
            console.error(data.data.message);
        });
    }
    
    
    
    <?php 
        // fetching models based on brand
    ?>
    
    $(document).on('change', "#brand", function(e) {
        let brand = $(this).val();
    
        let models = fetch_brand_models(brand);
    });
});
</script>