<div id="details" class="ab-sub-content">
    <h2 class="ab-h2 no-left-padding">Basic Car Information</h2>
    <div class="ab-meta-grid">
        <label for="brand"><strong>Brand</strong></label>
        <select name="brand" id="brand" >
            <?php foreach ($brands_arr as $brand): ?>
                <option value="<?= $brand; ?>"
                    <?php if ($listing->brand === $brand): ?>
                        selected
                    <?php endif;?>
                ><?= $brand; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="ab-meta-grid">
        <label for="model"><strong>Model</strong></label>
        <select name="model" id="model" >
            <?php foreach ($models_arr as $model): ?>
                <option value="<?= $model; ?>"
                    <?php if ($listing->model === $model): ?>
                        selected
                    <?php endif;?>
                ><?= $model; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="ab-meta-grid">
        <label for="body_type"><strong>Body Type</strong></label>
        <select name="body_type" id="body_type" >
            <?php foreach ($body_types_arr as $body_type): ?>
                <option value="<?= $body_type ?>"
                    <?php if ($listing->body_type === $body_type): ?>
                        selected
                    <?php endif;?>
                ><?= $body_type; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="ab-meta-grid">
        <label for="colour"><strong>Colour</strong></label>
        <input type="text" name="colour" id="colour" value="<?= $listing->colour; ?>" />
    </div>
    
    <div class="ab-meta-grid">
        <label for="fuel_type"><strong>Fuel Type</strong></label>
        <select name="fuel_type" id="fuel_type" >
            <?php foreach ($fuel_types_arr as $fuel_type): ?>
                <option value="<?= $fuel_type ?>"
                    <?php if ($listing->fuel_type === $fuel_type): ?>
                        selected
                    <?php endif;?>
                ><?= $fuel_type; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="ab-meta-grid">
        <label for="transmission"><strong>Transmission</strong></label>
        <select name="transmission" id="transmission" >
            <?php foreach ($transmissions_arr as $transmission): ?>
                <option value="<?= $transmission ?>"
                    <?php if ($listing->transmission === $transmission): ?>
                        selected
                    <?php endif;?>
                ><?= $transmission; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="ab-meta-grid">
        <label for="drive_type"><strong>Drive Type</strong></label>
        <select name="drive_type" id="drive_type" >
            <?php foreach ($drive_types_arr as $drive_type): ?>
                <option value="<?= $drive_type ?>"
                    <?php if ($listing->drive_type === $drive_type): ?>
                        selected
                    <?php endif;?>
                ><?= $drive_type; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="ab-meta-grid">
        <label for="year"><strong>Year</strong></label>
        
        <?php 
            $current_year = date('Y');
            $years        = 50;
        ?>
    
        <select name="year" id="year">
            <?php for ($i = 0; $i < $years; $i++): ?>
                <?php $year = $current_year - $i; ?>
                <option value="<?= $year ?>"
                    <?php if ($listing->year === $year): ?>
                        selected
                    <?php endif;?>
                ><?= $year; ?></option>
            <?php endfor; ?>
        </select>
    </div>
    
    <div class="ab-meta-grid">
        <label for="engine_no"><strong>Engine No.</strong></label>
        <input type="text" name="engine_no" id="engine_no" value="<?= $listing->engine_no; ?>" />
    </div>
    
    <h2 class="ab-h2 no-left-padding">Car Condition</h2>
    
    <div class="ab-meta-grid">
        <label for="condition"><strong>Condition</strong></label>
        <select name="condition" id="condition" >
            <?php foreach ($conditions_arr as $condition): ?>
                <option value="<?= $condition ?>"
                    <?php if ($listing->condition === $condition): ?>
                        selected
                    <?php endif;?>
                ><?= $condition; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="ab-meta-grid">
        <label for="mileage"><strong>Mileage (in km)</strong></label>
        <input type="number" name="mileage" id="mileage" value="<?= $listing->mileage; ?>" />
    </div>

    <h2 class="ab-h2 no-left-padding">Price</h2>
    
    <div class="ab-meta-grid">
        <label for="price"><strong>Price (B$)</strong></label>
        <input type="number" name="price" id="price" value="<?= $listing->price; ?>" required/>
    </div>
    
    <div class="ab-meta-grid">
        <label for="sale_price"><strong>Sale Price (B$)</strong></label>
        <input type="number" name="sale_price" id="sale_price" value="<?= $listing->sale_price; ?>" />
    </div>

    <label class="ab-checkbox-container">Mark as Sold
        <input type="checkbox" class="" name="sold" value="1" 
            <?php if ($listing->sold == 1): ?>
                checked
            <?php endif;?>
        />
        <span class="ab-checkmark"></span>
    </label>

    <div>
        <button class="ab-btn ab-btn-primary ab-navigate-btn" type="button" data-content="features">Next</button>
    </div>
</div>