<?php

namespace Autobrunei\Cpt;

interface CptInterface
{
    public function create_post_type();

    public function add_meta_boxes();

    public function meta_box( $post );

    public function save_meta( $post_id, $post );

    public function set_custom_columns($columns);

    public function custom_column( $column, $post_id );
}