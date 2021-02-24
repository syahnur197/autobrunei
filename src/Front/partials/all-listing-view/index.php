<style>

.ab-nav {
    display: flex;
}

.ab-nav li.ab-nav-item {
    display: inline;
    background-color: #11323E;
    background-size: 300px 100px;
    color: white;
    padding: 10px 25px;
    margin: 0px;
    cursor: pointer;
}

.ab-nav-container {
    display: grid;
    grid-template-columns: 25% 25% 25% 25%;
    padding: 10px;
    grid-gap: 1px;
    background-color: #11323E;
    padding-top: 15px;
    padding-bottom: 15px;
}

.ab-input {
    font-size: 1.3em !important;
	padding: 20px 30px;
	width: 98%;
}

.ab-listings-container {
    display: grid;
    grid-template-columns: 25% 25% 25% 25%;
    grid-gap: 15px
}

.ab-listing .ab-listing-image {
    /* width: 250px;
    height: 200px; */
    height: 75%; width: 100%; object-fit: contain;

}

.ab-listing-detail {
    display: flex;
    justify-content: space-between;
}

.ab-listing-detail .ab-listing-data{
    font-size: 1em;
}

.ab-pricing {
    font-size: 1.5em !important;
    font-weight:500;
    color: #0c6913;
}

#search_button {
	background: #cac8c8;
    color: #000;
    border: 0px;
}

#search_button:hover {
	background: #fff;
    color: #000;
    border: 2px solid #000;
	font-weight: 500;
}

@media only screen and (max-width: 768px) {
    .ab-nav-container {
        display: block;
        grid-template-columns: 100%;
    }
    .ab-input {
        width: 100%;
    }
    .ab-listings-container {
        display: block;
    }
}

</style>

<?php 
use Autobrunei\Entities\Listing;
use Autobrunei\Main;
?>

<br>
<div style="padding:2px;">
    <div class="ab-nav-container">
        <div class="ab-nav-content">
            <select class="ab-input ab-dropdown" name="brand" id="brand">
                <option <?= !isset($_GET['brand']) ? 'brand' : ''; ?>>Choose Brand</option>
                <?php foreach($brands_arr as $key => $brand): ?>
                    <option value="<?= $brand ?>"
                        <?= isset($_GET['brand']) && $_GET['brand'] == $brand ? 'selected' : ''; ?>
                    ><?= $brand; ?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="ab-nav-content">
            <select class="ab-input ab-dropdown" name="model" id="model">
                <option <?= !isset($_GET['model']) ? 'model' : ''; ?>>Choose Model</option>
                <?php foreach($models_arr as $key => $model): ?>
                    <option value="<?= $model ?>"
                        <?= isset($_GET['model']) && $_GET['model'] == $model ? 'selected' : ''; ?>
                    ><?= $model; ?></option>
                <?php endforeach;?>
            </select>
        </div>
		<?php 
            $current_year = date('Y');
            $years        = 50;
        ?>
		<div class="ab-nav-content">
            <select class="ab-input ab-dropdown" name="year" id="year">
                <option <?= !isset($_GET['year']) ? 'year' : ''; ?>>Choose Year</option>
				<?php for ($i = 0; $i < $years; $i++): ?>
					<?php $year = $current_year - $i; ?>
				    <option value="<?= $year ?>"
                        <?= isset($_GET['year']) && $_GET['year'] == $model ? 'selected' : ''; ?>
                    ><?= $year; ?></option>
				<?php endfor; ?>
            </select>
        </div>
        <div class="ab-nav-content">
            <button class="ab-input ab-button" id="search_button" type="button">
                Search
            </button>
        </div>
    </div>
</div>

<br>

<div class="ab-listings-container">
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

                <div class="ab-listing-detail">
                    <h5 class="ab-listing-data">
                        <a href="<?= $listing->get_view_listing_url(); ?>"><?= $listing->getTitle(); ?></a>
                    </h5>
                    <h5 class="ab-listing-data ab-pricing">
                        B$ <?= $listing->getPrice(); ?>
                    </h5>
                </div>

                <hr>

                <div class="ab-listing-detail">
                    <h6 class="ab-listing-data">
                        <?= $listing->getMileage(); ?> km
                    </h6>
                    <h6 class="ab-listing-data">
                    <?= $listing->getTransmission(); ?>
                    </h6>
                </div>
                <br>
            </div>

        <?php endwhile; ?>
    <?php endif; ?>
    <?php 
        wp_reset_postdata();
        wp_reset_query();
    ?>
</div>

<?php require_once Main::get_path_from_src('Front/partials/_shared/_update-model-script.php'); ?>

<script>
jQuery(document).ready(function($) {
    let search_object = {};

    let ignore_strings = ['Choose Condition', 'Choose Brand', 'Choose Model'];

    let url = "";

    $('.ab-dropdown').on('change', function(e) {
        const element = e.target;

        const name = $(element).attr('name');

        const value = $(element).val();

        if (ignore_strings.includes(value)) {
            delete search_object[name]; 
            return;
        }

        search_object = {
            ...search_object,
            [name] : $(element).val(),
        }

        const query_string = new URLSearchParams(search_object).toString();

        const current_url = window.location.href.split('?')[0];

        url = `${current_url}?${query_string}`;

    });

    $("#search_button").on("click", function(e) {
        window.location = url;
    });
});

</script>

