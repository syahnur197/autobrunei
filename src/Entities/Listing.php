<?php

namespace Autobrunei\Entities;

use Autobrunei\Data\Helper;
use Autobrunei\Utils\FileUploader;
use Exception;
use WP_Post;
use Carbon\Carbon;

class Listing
{

    const POST_TYPE = 'ab-listings';

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
    private $featured_image_index;
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
            'post_title' => $this->title,
            'post_status' => 'publish',
            'post_type' => self::POST_TYPE,
            'comment_status' => 'closed',
        ];

        if (!is_numeric($this->id)) {
            $this->id = wp_insert_post($post_arr);
        } else {

            // check if current user owns the listing
            $author_id = (int) $this->get_wp_post()->post_author;
            $current_user_id = get_current_user_id();

            if ($author_id !== $current_user_id) {
                throw new Exception('You don\'t own this listing!');
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

    public function set_attachments($files)
    {

    }

    public static function get_listing_by_id($post_id)
    {
        $post = get_post($post_id);

        if ($post->post_type !== self::POST_TYPE) {
            throw new Exception('Post is not a Listing!');
        }

        return new self($post);
    }

    // execute when initialising an empty listing
    private function _initialise_empty_listing()
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
        $this->images_urls = '';
    }

    private function _initialise_listing($data)
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

        // if the object initialised has ID
        // which means data is already in DB
        if (isset($data->ID)) {
            // existing listing
            $this->id = $data->ID;
            $this->_parse_features();
            $this->get_wp_post();
        }
    }

    private function _parse_features()
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

    private function _validate_listing_data()
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

        $this->end_date = strtotime('+7 day', $timestamp);
    }

    private function _save_listing_metas()
    {
        $listing_data = get_object_vars($this);

        foreach($listing_data as $key => $data) {
            
            $ignore_keys = ['id', 'title', 'wp_post', 'featured_image_url', 'images_urls', 'featured_image_index'];
            
            if (in_array($key, $ignore_keys)) {
                continue;
            }
            
            if ($key === 'features') {
                update_post_meta($this->id, $key, json_encode($data));
            } else {
                // sanitize before storing
                $data = sanitize_text_field($data);

                update_post_meta($this->id, $key, $data);
            }

        }
    }

    public function save_attachments($files)
    {
        $post_id = $this->id;
        
        $attachment_ids = FileUploader::handleUpload($post_id, $files);

        $attachment_urls = [];

        foreach ($attachment_ids as $key => $attachment_id) {
            $url = wp_get_attachment_url($attachment_id);

            if ($key === (int) $this->featured_image_index) {
                update_post_meta($this->id, 'featured_image_id', $attachment_id);
                update_post_meta($this->id, 'featured_image_url', $url);
            }

            $attachment_urls[] = $url;
        }

        update_post_meta($this->id, 'images_ids', json_encode($attachment_ids));
            
        update_post_meta($this->id, 'images_urls', json_encode($attachment_urls));
    }

    // overcomplicating the getter method, because why not
    // I might regret this in the future but oh wells
    public function __call($name, $argument)
    {
        if (substr($name, 0, 3) === 'get') {
            // return the string after get
            $property = substr($name, 3);

            // add underscore before capital letter
            $property = preg_replace('/\B([A-Z])/', '_$1', $property);

            // get the actual property name
            $property = strtolower($property);

            // return the property
            return $this->{$property};
        }

        throw new Exception("Method not found!");
    }
}