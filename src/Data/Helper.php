<?php

namespace Autobrunei\Data;

use Autobrunei\Data\Brands;
use Autobrunei\Data\Models;

/**
 * This is where you can add some values to 
 * brands, transmission, body types, conditions, etc
 */
class Helper
{

    public static function get_brands(): array
    {
        $brands = Brands::DATA;
        sort($brands);

        return $brands;
    }

    public static function is_brand_exist(string $brand): bool
    {
        return in_array($brand, Brands::DATA);
    }


    public static function get_brand_models(string $brand): array
    {
        $models = Models::DATA[$brand];
        sort($models);
        
        return $models;
    }

    public static function is_brand_model_exist(string $brand, string $model): bool
    {
        return in_array($model, Models::DATA[$brand]);
    }


    public static function get_transmissions(): array
    {
        return Transmissions::DATA;
    }

    public static function is_transmission_exist(string $transmission): bool
    {
        return in_array($transmission, Transmissions::DATA);
    }


    public static function get_body_types(): array
    {
        return BodyTypes::DATA;
    }

    public static function is_body_type_exist(string $body_type): bool
    {
        return in_array($body_type, BodyTypes::DATA);
    }


    public static function get_condititons(): array
    {
        return Conditions::DATA;
    }

    public static function is_condition_exist(string $condition): bool
    {
        return in_array($condition, Conditions::DATA);
    }

    public static function get_fuel_types(): array
    {
        return FuelTypes::DATA;
    }

    public static function is_fuel_type_exist(string $fuel_type): bool
    {
        return in_array($fuel_type, FuelTypes::DATA);
    }

    public static function get_drive_types(): array
    {
        return DriveTypes::DATA;
    }

    public static function is_drive_type_exist(string $drive_type): bool
    {
        return in_array($drive_type, DriveTypes::DATA);
    }
}