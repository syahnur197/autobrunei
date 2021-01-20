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
    padding-left: 10px;
    grid-gap: 20px;
    background-color: #11323E;
    padding-top: 15px;
    padding-bottom: 15px;
}

.ab-input {
    font-size: 1.3em !important;
}

.ab-listings-container {
    display: grid;
    grid-template-columns: 25% 25% 25% 25%;
    grid-gap: 10px
}

.ab-listing .ab-listing-image {
    width: 250px;
    height: 200px;
}

.ab-listing-detail {
    display: flex;
    justify-content: space-between;
}

.ab-listing-detail .ab-listing-data{
    font-size: 0.7em;
}


</style>

<div>
    <div class="ab-nav-container">
        <div class="ab-nav-content">
            <select class="ab-input ab-dropdown">
                <option>Choose Condition</option>
                <?php foreach($conditions_arr as $key => $condition): ?>
                    <option value="<?= $condition ?>"
                        <?= $key === 0 ? "selected" : "" ?>
                    ><?= $condition; ?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="ab-nav-content">
            <select class="ab-input ab-dropdown">
                <option>Choose Brand</option>
                <?php foreach($brands_arr as $key => $brand): ?>
                    <option value="<?= $brand ?>"
                        <?= $key === 0 ? "selected" : "" ?>
                    ><?= $brand; ?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="ab-nav-content">
            <select class="ab-input ab-dropdown">
                <option>Choose Model</option>
                <?php foreach($models_arr as $key => $model): ?>
                    <option value="<?= $model ?>"
                        <?= $key === 0 ? "selected" : "" ?>
                    ><?= $model; ?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="ab-nav-content">
            <button class="ab-input ab-button">
                Search
            </button>
        </div>
    </div>
</div>

<div class="ab-listings-container">
    <?php foreach($listings as $listing): ?>
        <div class="ab-listing">
            <img class="ab-listing-image" src="<?= $listing->featured_image_url?>">

            <div class="ab-listing-detail">
                <h5 class="ab-listing-data">
                    <?= $listing->post_title; ?>
                </h5>
                <h5 class="ab-listing-data">
                    B$ <?= $listing->sale_price; ?>
                </h5>
            </div>

            <hr>

            <div class="ab-listing-detail">
                <h6 class="ab-listing-data">
                    <?= $listing->mileage; ?> km
                </h6>
                <h6 class="ab-listing-data">
                <?= $listing->transmission; ?>
                </h6>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>

</script>

