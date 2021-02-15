<style>
<?php // all the best maintaining 💗?>

/* Layouts */
.ab-shop-container {
    display: grid;
    grid-template-columns: 25% 75%;
}

.ab-filter-container {
    background-color: #d0c3bd;
}

.ab-shop-listings-container {
    background-color: #fff;
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

.ab-listings-container p{
    padding-bottom: 2em;
}

.ab-listing {
    /* background-color: lightblue; */
    padding: 1rem;
    display: grid;
    grid-template-columns: 30% 50% 20%;
    margin-bottom: 1rem;
}

.ab-pagination-btn {
    cursor: pointer;
    background-color: #fff;
    color: #000;
    padding: 1rem;
    margin-top: 5rem;
    margin-right: 1rem;
}

.ab-pagination-btn:hover {
    background-color: orange;
}

.ab-listing-image {
    height: 75%; 
    width: 100%; 
    object-fit: contain;
    border: 1px solid black;
}

.ab-meta-description {
    display: grid;
    grid-template-columns: auto auto auto;

}

.ab-listing-pricing {
    font-size: 2em;
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

.ab-compared-listings-container {
    margin-top: 1rem;
}

.ab-compared-listing {
    background-color: black;
    color: white;
    padding: 1rem;
    margin-right: 1rem;
}

.ab-compared-listing:hover {
    background-color: #282828;
    color: white;
}

.ab-dismiss-compared-listing {
    margin-left: 1.5rem;
    margin-right: 0.5rem;
    font-size: 0.7em;
    cursor: pointer;
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

.ab-listing-description {
    padding-left: 20px;
}

.ab-listing-title a {
    color: #000;
}


.ab-meta-description {
    color: #717171;
}

.woocommerce-ordering, .products {
    display: none;
}

.attribute_filter {
    padding: 10px !important;
}

.add-to-compare-btn {
    padding:15px;
}
</style>