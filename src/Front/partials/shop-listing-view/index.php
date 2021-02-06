
<?php 
    use Autobrunei\Main;
?>

<?php require_once "_style-component.php"; ?>

<div class="ab-compare-container" style="display: none;">
    <span>Comparing: </span>

    <div class="ab-compared-listings-container">
        
    </div>
    
    <br>
    <span class="ab-compare-button">Compare</span>
</div>

<div class="ab-shop-container">
    <div class="ab-filter-container">
        <?php require_once "_filter-component.php"; ?>
    </div>
    <div class="ab-shop-listings-container">
        <div class="ab-filter-buttons-container" id="ab-filter-buttons-container">
        </div>
        <div class="ab-listings-container">
        </div>
    </div>
</div>

<?php require_once Main::get_path_from_src('Front/partials/shared/_update-model-script.php'); ?>

<?php require_once "_script-component.php"; ?>