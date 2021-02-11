<script>

    <?php // should have used vue js for this 🤦‍♂️?>

    // to hold the filtered attributes
    let filtered_attributes = {};

    function push_filtered_attributes(attribute, value) {
        filtered_attributes[attribute] = value;
    }

    function create_filter_attribute_button(attribute, value) {
        return `<span class="ab-filter-button" data-attribute="${attribute}">${to_nice_name(attribute)}: ${value} <span class="ab-filter-dismiss" data-attribute="${attribute}">X</span></span>`;
    }

    function create_filter_attribute_buttons() {
        let render_string = ``;
        for (const [attribute, value] of Object.entries(filtered_attributes)) {
            render_string += create_filter_attribute_button(attribute, value)
        }

        return render_string;
    }

    <?php // edit here if you want to change the structure of the listing ?>

    function generate_listing(listing_object) {
        const listing_title = `${listing_object.brand} ${listing_object.model} (${listing_object.year})`
        const render_string = `
            <div class="ab-listing">
                <img class="ab-listing-image" src="${listing_object.featured_image_url}">

                <div class="ab-listing-description">
                    <span class="ab-listing-title"><a target="_blank" href="${listing_object.url}">${listing_title}</a></span>
                    <br>

                    <div class="ab-meta-description">
                        <div>
                            <span class="ab-attribute-title">Mileage</span> <br>
                            ${listing_object.mileage} km
                        </div>
                        <div>
                            <span class="ab-attribute-title">Transmission</span> <br>
                            ${listing_object.transmission}
                        </div>
                        <div>
                            <span class="ab-attribute-title">Fuel Type</span> <br>
                            ${listing_object.fuel_type}
                        </div>
                    </div>
                </div>

                <div class="ab-listing-description">
                    <span class="ab-listing-pricing">
                        B$ ${listing_object.sale_price}
                    </span>

                    <br>

                    <button type="button" class="add-to-compare-btn" data-title="${listing_title}" data-listing-id="${listing_object.id}">
                        Add to compare
                    </button>
                </div>
            </div>
        `

        return render_string;
    }


    /** utils */
    function title_case(str) {
        let splitStr = str.toLowerCase().split(' ');
        for (let i = 0; i < splitStr.length; i++) {
            splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
        }
        return splitStr.join(' '); 
    }

    function to_nice_name(name) {
        name = name.replace(/_/g, ' ');

        return title_case(name);
    }

    
    jQuery(document).ready(function($) {
        const filter_attribute_class = '.attribute_filter';
        
        const dismiss_filter_button_class = '.ab-filter-dismiss';
        
        const filter_buttons_container = $("#ab-filter-buttons-container");

        const listings_container_class = '.ab-listings-container';

        const listings_container = $(listings_container_class);

        function render_filter_attribute_buttons() {
            let filter_attribute_button = create_filter_attribute_buttons();
    
            filter_buttons_container.html(filter_attribute_button);
        }

        function render_filtered_listings(paged) {
            const ajaxurl = '<?= esc_url(admin_url('admin-ajax.php')); ?>';

            const payload = { action: 'get_filtered_listings', paged: paged, ...filtered_attributes };

            const query_string = new URLSearchParams(payload).toString();

            const url = `${ajaxurl}?${query_string}`;

            const jqxhr = $.get(url, function(data) {

                if (!data.success) {
                    return;
                }

                const listings = data.data.listings;

                let theString = '';

                for (let i = 0; i < listings.length; i++) {
                    theString += generate_listing(listings[i]);
                }

                <?php // updated the paged variable ?>

                paged = data.data.paged;

                if (data.data.has_prev_page) {
                    theString += `<span class='ab-pagination-btn' data-paged="${paged - 1}">Previous</span>`;
                }

                if (data.data.has_next_page) {
                    theString += `<span class='ab-pagination-btn' data-paged="${paged + 1}">next</span>`;
                }


                listings_container.html(theString);
            });

            jqxhr.fail(function(data) {
                listings_container.html(`
                    <p>${data.responseJSON.message}</p>
                    <span class='ab-pagination-btn' data-paged="${paged - 1}">Previous</span>
                `);
            })

        }

        <?php // render the listing on document load ?>

        render_filtered_listings(1);


        $(document).on('change', filter_attribute_class, function(event) {
            const attribute = event.target.name;
            const value = event.target.value;

            push_filtered_attributes(attribute, value) 

            pop_model_if_brand(attribute);

            render_filter_attribute_buttons();

            <?php // reset the paged ?>

            render_filtered_listings(1);
            
        });

        <?php // pagination ?>

        const navigation_button_class = '.ab-pagination-btn';
        $(document).on('click', navigation_button_class, function(event) {
            const the_paged = event.target.getAttribute("data-paged");

            render_filtered_listings(the_paged);
        });


        function pop_attribute_from_filtered_attributes(attribute) {
            delete filtered_attributes[attribute];

            $(`#${attribute}`).val($(`#${attribute} option:first`).val());

            pop_model_if_brand(attribute);
        }

        <?php // if user 'x' the brand, we should also 'x' the model ?>

        function pop_model_if_brand(attribute) {
            if (attribute === 'brand') {
                pop_attribute_from_filtered_attributes('model');
            }
        }

        $(document).on('click', dismiss_filter_button_class, function(event) {
            const attribute = event.target.getAttribute("data-attribute");

            pop_attribute_from_filtered_attributes(attribute);

            render_filter_attribute_buttons();

            paged = 1;

            render_filtered_listings(1);
        });

        <?php // Listing comparison ?>

        let compared_listings = []; 


        const comparison_container_class = '.ab-compare-container';
        const add_to_compare_btn_class = '.add-to-compare-btn';
        const compared_listings_container = '.ab-compared-listings-container';
        const dismiss_compared_listing_class = '.ab-dismiss-compared-listing';

        function create_compared_listing_button(title, id) {
            return `<span class="ab-compared-listing">${title} <span class="ab-dismiss-compared-listing" data-listing-id="${id}">x</span></span>`;
        }

        function listing_is_compared(listing_id) {
            return compared_listings.some(listing => listing.listing_id === listing_id)
        }

        function create_compared_listing_buttons() {
            let render_string = ``;

            for (let i = 0; i < compared_listings.length; i++) {
                const listing_title = compared_listings[i].listing_title;
                const listing_id = compared_listings[i].listing_id;
                render_string += create_compared_listing_button(listing_title, listing_id);
            }

            return render_string;
        }


        $(document).on('click', add_to_compare_btn_class, function(event) {
            const listing_title = event.target.getAttribute("data-title");
            const listing_id = event.target.getAttribute("data-listing-id");

            if (listing_is_compared(listing_id)) {
                return;
            }

            if (compared_listings.length >= 2) {
                alert('You cannot add more than 2 vehicles to compare!');
                return;
            }

            compared_listings.push({
                listing_id, listing_title
            });

            if (compared_listings.length > 0) {
                $(comparison_container_class).css('display', 'block');
            }

            $(compared_listings_container).html(create_compared_listing_buttons());
        });

        $(document).on('click', dismiss_compared_listing_class, function(event) {
            const listing_id = event.target.getAttribute("data-listing-id");

            if (!listing_is_compared(listing_id)) {
                return;
            }

            compared_listings = compared_listings.filter(listing => listing.listing_id !== listing_id);

            if (compared_listings.length <= 0) {
                $(comparison_container_class).css('display', 'none');
            }

            $(compared_listings_container).html(create_compared_listing_buttons());
        })
    });
</script>