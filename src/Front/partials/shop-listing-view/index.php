<style>
/* Layouts */
.ab-shop-container {
    display: grid;
    grid-template-columns: 25% 75%;
}

.ab-filter-container {
    background-color: red;
}

.ab-shop-listings-container {
    background-color: green;
}

.ab-compare-container {
    background-color: lime;
    padding: 1rem;
}

/* Components Style */
.ab-attribute-container {
    display: flex;
    flex-direction: column;
    padding: 1rem;
}

.ab-listing-title {
    font-size: 1.5em;
    font-weight: bolder;
}

.ab-attribute-title {
    font-size: 1em;
    font-weight: bold;
}

/* Min and Max */
.ab-range-container {
    display: grid;
    grid-template-columns: 30% 70%;
    align-items: center;
    margin: 0.5rem 0;
}

/* listing component styling */
.ab-listings-container {
    padding: 1rem;
}

.ab-listing {
    background-color: lightblue;
    padding: 1rem;
    display: grid;
    grid-template-columns: 30% 50% 20%;
    margin-bottom: 1rem;
}

.ab-listing-image {
    width: 250px;
    height: 200px;
    border: 1px solid black;
}

.ab-meta-description {
    display: grid;
    grid-template-columns: auto auto auto;

}

.ab-listing-pricing {
    font-size: 1.3em;
    font-weight: bold;
}

.ab-compare-button {
    background-color: green;
    color: white;
    padding: 1rem;
    cursor: pointer;
}

.ab-compare-button:hover {
    background-color: darkgreen;
}

.ab-filter-buttons-container {
    padding: 1rem;
}

.ab-filter-button {
    padding: 0.5rem;
    background-color: white;
    margin: 1rem;
}

.ab-filter-dismiss {
    margin-left: 1.5rem;
    margin-right: 0.5rem;
    font-size: 0.7em;
    cursor: pointer;
}

</style>

<?php 
    use Autobrunei\Main;
?>

<div class="ab-compare-container">
    <p>
        Compare x with y
    </p>
    <span class="ab-compare-button">Compare</span>
</div>

<div class="ab-shop-container">
    <div class="ab-filter-container">
        <?php require_once "_filter-component.php"; ?>
    </div>
    <div class="ab-shop-listings-container">
        <div class="ab-filter-buttons-container">
            <span class="ab-filter-button">Brand: Toyota <span class="ab-filter-dismiss">X</span></span>
            <span class="ab-filter-button">Make: 86 <span class="ab-filter-dismiss">X</span></span>
        </div>
        <?php require_once "_listings-component.php"; ?>
    </div>
</div>

<?php require_once Main::get_path_from_src('Front/partials/shared/_update-model-script.php'); ?>

<script>
    jQuery(document).ready(function($) {
        const filter_attribute_class = '.filter_attribute';
        const filter_attribute_doms = $(filter_attribute_class);


        $(document).on('change', filter_attribute_class, function() {

        });
    });
</script>