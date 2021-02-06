<div class="ab-attribute-container">
    <label for="brand"><strong>Brand</strong></label>
    <select name="brand" id="brand" class="attribute_filter">
        <option>Select Brand</option>
        <?php foreach ($brands_arr as $brand): ?>
            <option value="<?= $brand; ?>"><?= $brand; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="model"><strong>Model</strong></label>
    <select name="model" id="model" class="attribute_filter">
        <option>Select Model</option>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="body_type"><strong>Body Type</strong></label>
    <select name="body_type" id="body_type" class="attribute_filter">
        <option>Select Body Type</option>
        <?php foreach ($body_types_arr as $body_type): ?>
            <option value="<?= $body_type ?>"><?= $body_type; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="fuel_type"><strong>Fuel Type</strong></label>
    <select name="fuel_type" id="fuel_type" class="attribute_filter">
        <option>Select Fuel Type</option>
        <?php foreach ($fuel_types_arr as $fuel_type): ?>
            <option value="<?= $fuel_type ?>"><?= $fuel_type; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="transmission"><strong>Transmission</strong></label>
    <select name="transmission" id="transmission" class="attribute_filter">
        <option>Select Transmission</option>
        <?php foreach ($transmissions_arr as $transmission): ?>
            <option value="<?= $transmission ?>"><?= $transmission; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="drive_type"><strong>Drive Type</strong></label>
    <select name="drive_type" id="drive_type" class="attribute_filter">
        <option>Select Drive Type</option>
        <?php foreach ($drive_types_arr as $drive_type): ?>
            <option value="<?= $drive_type ?>"><?= $drive_type; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="year"><strong>Year</strong></label>
    
    <?php 
        $current_year = date('Y');
        $years        = 50;
    ?>

    <select name="year" id="year" class="attribute_filter">
        <option>Select Year</option>
        <?php for ($i = 0; $i < $years; $i++): ?>
            <?php $year = $current_year - $i; ?>
            <option value="<?= $year ?>"><?= $year; ?></option>
        <?php endfor; ?>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="condition"><strong>Condition</strong></label>
    <select name="condition" id="condition" class="attribute_filter">
        <option>Select Condition</option>
        <?php foreach ($conditions_arr as $condition): ?>
            <option value="<?= $condition ?>"><?= $condition; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="ab-attribute-container">
    <label for="mileage"><strong>Mileage (in km)</strong></label>
    <div class="ab-range-container">
        <label for="mileage_minimum">Minimum</label>
        <input type="number" name="mileage_minimum" id="mileage_minimum" value="" class="attribute_filter"/>
    </div>
    <div class="ab-range-container">
        <label for="mileage_maximum">Maximum</label>
        <input type="number" name="mileage_maximum" id="mileage_maximum" value="" class="attribute_filter"/>
    </div>
</div>

<div class="ab-attribute-container">
    <label for="price"><strong>Price (B$)</strong></label>
    <div class="ab-range-container">
        <label for="price_minimum">Minimum</label>
        <input type="number" name="price_minimum" id="price_minimum" value="" class="attribute_filter"/>
    </div>
    <div class="ab-range-container">
        <label for="price_maximum">Maximum</label>
        <input type="number" name="price_maximum" id="price_maximum" value="" class="attribute_filter"/>
    </div>
</div>
