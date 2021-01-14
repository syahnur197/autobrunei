<?php

namespace Autobrunei\Data;

use Autobrunei\Data\Brands;
use Autobrunei\Data\Models;
use Exception;
use InvalidArgumentException;

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
        if ( ! in_array($brand, Brands::DATA) ) {
            throw new InvalidArgumentException('Brand "'. $brand .'" does not exist!');
        }

        return true;
    }


    public static function get_brand_models(string $brand): array
    {
        $models = Models::DATA[$brand];
        sort($models);
        
        return $models;
    }

    public static function is_brand_model_exist(string $brand, string $model): bool
    {
        if (!in_array($model, Models::DATA[$brand])) {
            throw new InvalidArgumentException('Model "'. $model .'" does not exist!');
        }

        return true;
    }


    public static function get_transmissions(): array
    {
        return Transmissions::DATA;
    }

    public static function is_transmission_exist(string $transmission): bool
    {
        if (!in_array($transmission, Transmissions::DATA)) {
            throw new InvalidArgumentException('Transmission "'. $transmission .'" does not exist!');
        }

        return true;
    }


    public static function get_body_types(): array
    {
        $body_types = BodyTypes::DATA;
        sort($body_types);

        return $body_types;
    }

    public static function is_body_type_exist(string $body_type): bool
    {
        if (!in_array($body_type, BodyTypes::DATA)) {
            throw new InvalidArgumentException('Body type "'. $body_type .'" does not exist!');
        }

        return true;
    }


    public static function get_condititons(): array
    {
        return Conditions::DATA;
    }

    public static function is_condition_exist(string $condition): bool
    {
        if (!in_array($condition, Conditions::DATA)) {
            throw new InvalidArgumentException('Condition "'. $condition .'" does not exist!');
        }

        return true;
    }

    public static function get_fuel_types(): array
    {
        $fule_types = FuelTypes::DATA;
        sort($fule_types);

        return $fule_types;
    }

    public static function is_fuel_type_exist(string $fuel_type): bool
    {
        if (!in_array($fuel_type, FuelTypes::DATA)) {
            throw new InvalidArgumentException('Fuel type "'. $fuel_type .'" does not exist!');
        }

        return true;
    }

    public static function get_drive_types(): array
    {
        return DriveTypes::DATA;
    }

    public static function is_drive_type_exist(string $drive_type): bool
    {
        if (!in_array($drive_type, DriveTypes::DATA)) {
            throw new InvalidArgumentException('Drive type "'. $drive_type .'" does not exist!');
        }

        return true;
    }

    public static function get_features(): array
    {
        $features = Features::DATA;
        sort($features);

        return $features;
    }

    public static function is_feature_exist(string $feature): bool
    {
        if (!in_array($feature, Features::DATA)) {
            throw new InvalidArgumentException('Feature "'. $feature .'" does not exist!');
        }

        return true;
    }
}