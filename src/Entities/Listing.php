<?php

namespace Autobrunei\Entities;

use Autobrunei\Data\Helper;
use Autobrunei\Utils\FileUploader;
use Autobrunei\Utils\Response;
use Autobrunei\Utils\Str;
use Exception;
use WP_Post;

/**
 * Autobrunei\Entities\Listing
 *
 * @method int getId()
 * @method string getTitle()
 * @method string getBrand()
 * @method string getModel()
 * @method string getBodyType()
 * @method string getFuelType()
 * @method string getColour()
 * @method string getTransmission()
 * @method string getDriveType()
 * @method string getYear()
 * @method string getEngineNo()
 * @method string getCondition()
 * @method string getMileage() Get mileage in kilometre
 * @method string getPrice() Get "original" price
 * @method string getSalePrice() Get Sale Price
 * @method bool getSold()
 * @method array getFeatures() Get json encoded features. Decode to get features as array
 * @method string getSellersNote()
 * @method string getFeaturedImageId()
 * @method string getFeaturedImageUrl()
 * @method string getImagesIds()
 * @method string getImagesUrls()
 * @method string getStartDate()
 * @method string getEndDate()
 * @method WP_Post getWpPost()
 */
class Listing
{

    const POST_TYPE = 'ab-listings';

    const VIEW_LISTING_URL = 'view-listing';

    const TIME_LIMIT = '+7 day';

    private $id;

    private string $title;
    private string $brand;
    private string $model;
    private string $body_type;
    private string $fuel_type;
    private string $colour;
    private string $transmission;
    private string $drive_type;
    private string $year;
    private string $engine_no;
    private string $condition;
    private string $mileage;
    private string $price;
    private string $sale_price;
    private string $sold;
    private $features;
    private string $sellers_note;
    private string $featured_image_url;
    private string $featured_image_id;
    private $featured_image_index;
    private $images_ids;
    private $images_urls;
    private $start_date;
    private $end_date;

    private $wp_post;

    public function __construct($data = null)
    {
        if (!isset($data)) {
            $this->_initialise_empty_listing();
            return;
        }

        $this->_initialise_listing($data);

        $this->title = $this->brand . ' ' . $this->model . ' ' . $this->engine_no . ' ' . $this->year;
    }

    public function save()
    {
        $post_arr = [
            'post_title'     => $this->title,
            'post_status'    => 'publish',
            'post_type'      => self::POST_TYPE,
            'comment_status' => 'closed',
        ];

        if (!is_numeric($this->id)) {
            // create new listing
            $this->id = wp_insert_post($post_arr);
        } else {
            // update the listing
            $current_user_id = get_current_user_id();

            if (!$this->is_author($current_user_id)) {
                throw new Exception("You are not allowed to update someone else's listing!");
            }

            $post_arr['ID'] = $this->id;
            wp_update_post($post_arr);
        }
        

        $this->_validate_listing_data();

        $this->_save_listing_metas();

        $this->wp_post = get_post($this->id);

        return true;

    }

    public function get_wp_post()
    {
        if (isset($this->wp_post) && $this->wp_post instanceof WP_Post) {
            return $this->wp_post;
        }

        $this->wp_post = get_post($this->id);

        return $this->wp_post;
    }

    public static function get_listing_by_id($post_id): Listing
    {
        $post = get_post($post_id);

        if ($post === null) {
            Response::not_found();
        }

        if ($post->post_type !== self::POST_TYPE) {
            Response::not_found();
        }

        return new self($post);
    }

    // execute when initialising an empty listing
    private function _initialise_empty_listing(): void
    {
        $this->title = '';
        $this->brand = '';
        $this->model = '';
        $this->body_type = '';
        $this->fuel_type = '';
        $this->colour = '';
        $this->transmission = '';
        $this->drive_type = '';
        $this->year = '';
        $this->engine_no = '';
        $this->condition = '';
        $this->mileage = '';
        $this->price = '';
        $this->sale_price = '';
        $this->sold = '';
        $this->features = [];
        $this->sellers_note = '';
        $this->featured_image_url = '';
        $this->featured_image_id = '';
        $this->images_urls = '';
    }

    private function _initialise_listing($data): void
    {
        // if values are not provided, default to empty string or empty array
        $this->brand = $data->brand ?? '';
        $this->model = $data->model ?? '';
        $this->body_type = $data->body_type ?? '';
        $this->fuel_type = $data->fuel_type ?? '';
        $this->colour = $data->colour ?? '';
        $this->transmission = $data->transmission ?? '';
        $this->drive_type = $data->drive_type ?? '';
        $this->year = $data->year ?? '';
        $this->engine_no = $data->engine_no ?? '';
        $this->condition = $data->condition ?? '';
        $this->mileage = $data->mileage ?? '';
        $this->price = $data->price ?? '';
        $this->sale_price = $data->sale_price ?? '';
        $this->sold = $data->sold ?? '';
        $this->features = $data->features ?? [];
        $this->sellers_note = $data->sellers_note ?? '';
        $this->featured_image_index = $data->featured_image_index ?? '';
        $this->featured_image_url = $data->featured_image_url ?? '';

        // if the object initialised has ID
        // which means data is already in DB
        if (isset($data->ID)) {
            // existing listing
            $this->id = $data->ID;

            // I've added the below properties on 4 february for viewing and edit existing listing purpose
            $this->featured_image_id = (string) $data->featured_image_id ?? '';
            $this->images_ids = $data->images_ids ?? '';
            $this->images_urls = $data->images_urls ?? '';
            // edit 4 february

            $this->_parse_features();
            $this->get_wp_post();
        }
    }

    private function _parse_features(): void
    {
        // if the features are not an array
        // that means it might be a json
        // which means data came from db
        // which means need to decode it

        if (!is_array($this->features)) {
            $this->features = json_decode($this->features);
        }

        // else the features is an array
        // just let it be
        // the point of this function is to make 
        // features as an array
    }

    private function _validate_listing_data(): void
    {
        // validate data
        Helper::is_brand_exist($this->brand);
        Helper::is_brand_model_exist($this->brand, $this->model);
        Helper::is_body_type_exist($this->body_type);
        Helper::is_fuel_type_exist($this->fuel_type);
        Helper::is_transmission_exist($this->transmission);
        Helper::is_drive_type_exist($this->drive_type);
        Helper::is_condition_exist($this->condition);

        foreach($this->features as $key => $feature) {
            Helper::is_feature_exist($feature);
            $features[$key] = sanitize_text_field($feature);
        }

        $timestamp = time();

        $this->start_date = $timestamp;

        $this->end_date = strtotime(self::TIME_LIMIT, $timestamp);
    }

    private function _save_listing_metas(): void
    {
        $listing_data = get_object_vars($this);

        foreach($listing_data as $key => $data) {
            
            // ignore keys that we dont want to update in the database in this operation
            // some of the keys i.e., featured_image_url and images_urls are handled
            // somewhere else
            $ignore_keys = ['id', 'title', 'wp_post', 'featured_image_url', 'images_urls'];
            
            // skip ignored keys
            if (in_array($key, $ignore_keys)) {
                continue;
            }
            
            if ($key === 'features') {
                // json encode the features
                update_post_meta($this->id, $key, json_encode($data));
            } else {
                // sanitize before storing
                $data = sanitize_text_field($data);

                update_post_meta($this->id, $key, $data);
            }
        }
    }

    public function save_attachments($files, $files_uploaded): void
    {
        $post_id = $this->id;
        
        $attachment_ids = $files_uploaded
            // if files are uploaded handle file upload
            ? FileUploader::handleUpload($post_id, $files)

            // otherwise just get the images ids in the front end
            : json_decode($_POST['images_ids']);

        $attachment_urls = [];

        if (count($attachment_ids) < 1) {
            throw new Exception('Please select an attachment!');
        }

        foreach ($attachment_ids as $key => $attachment_id) {
            $url = wp_get_attachment_url($attachment_id);

            // select the feature image url based on the featured image index selected
            if ($key === (int) $this->featured_image_index) {
                update_post_meta($this->id, 'featured_image_id', $attachment_id);
                update_post_meta($this->id, 'featured_image_url', $url);
            }

            $attachment_urls[] = $url;
        }

        update_post_meta($this->id, 'images_ids', json_encode($attachment_ids));
            
        update_post_meta($this->id, 'images_urls', json_encode($attachment_urls));
    }

    public function to_array(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'brand' => $this->brand,
            'model' => $this->model,
            'body_type' => $this->body_type,
            'fuel_type' => $this->fuel_type,
            'transmission' => $this->transmission,
            'drive_type' => $this->drive_type,
            'year' => $this->year,
            'condition' => $this->condition,
            'mileage' => $this->mileage,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'featured_image_url' => $this->featured_image_url,
            'url' => $this->get_view_listing_url(),
        ];
    }

    public function get_view_listing_url(): string
    {
        return site_url(self::VIEW_LISTING_URL . '?listing_id=' . $this->getId());
    }

    public function is_author(int $user_id): bool
    {
        return $user_id === (int) $this->get_wp_post()->post_author;
    }

    // overcomplicating the getter method, because why not
    // I might regret this in the future but oh wells
    // edit 4 february 2021: I regret it
    public function __call($name, $argument)
    {
        if (substr($name, 0, 3) === 'get') {
            // return the string after get
            $property = substr($name, 3);

            // add underscore before capital letter
            $property = preg_replace('/\B([A-Z])/', '_$1', $property);

            // get the actual property name
            $property = strtolower($property);

            if (empty($this->{$property}) && $property === 'featured_image_url') {
                $seed = Str::random(10);
                return "https://picsum.photos/seed/$seed/1200/1200";
            } else if (empty($this->{$property}) && $property === 'images_urls') {
                return json_encode([
                    "https://picsum.photos/seed/" . Str::random(10) . "/1200/1200",
                    "https://picsum.photos/seed/" . Str::random(10) . "/1200/1200",
                    "https://picsum.photos/seed/" . Str::random(10) . "/1200/1200",
                    "https://picsum.photos/seed/" . Str::random(10) . "/1200/1200",
                    "https://picsum.photos/seed/" . Str::random(10) . "/1200/1200",
                ]);
            }

            // return the property
            return $this->{$property};
        }

        throw new Exception("Method not found!");
    }
}