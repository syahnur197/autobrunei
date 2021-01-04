<div id="features" class="ab-sub-content ab-d-hide">
    <h2 class="ab-h2 no-left-padding">Additional Features</h2>
    
    <div id="ab-features-list">
        <?php foreach($features_arr as $feature): ?>
        <div class="ab-feature-row">
            <label class="ab-checkbox-container"><?= $feature; ?>
                <input type="checkbox" class="" name="feature[]" value="<?= $feature?>"/>
                <span class="ab-checkmark"></span>
            </label>
        </div>
        <?php endforeach; ?>
    </div>

    <div>
        <button class="ab-btn ab-btn-primary ab-navigate-btn" type="button" data-content="details">Previous</button>
        <button class="ab-btn ab-btn-primary ab-navigate-btn" type="button" data-content="price">Next</button>
    </div>
</div>