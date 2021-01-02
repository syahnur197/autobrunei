<?php

namespace Autobrunei\Utils;

/**
 * This is where you can add some values to 
 * brands, transmission, body types, conditions, etc
 */
class Config
{

    const BRANDS = [
        'BMW',
        'Audi',
        'Toyota',
        'Hyundai',
        'Nissan',
        'Suzuki',
        'Tesla',
        'MG',
        'Mitsubishi',
        'Daihatsu',
        'Isuzu',
        'Subaru',
        'Honda',
        'Kia',
        'Mazda',
        'Peugeot',
        'Ferrarri',
        'Mini',
        'Mercedez',
        'Lexus',
    ];

    public static function get_brands(): array
    {
        return self::BRANDS;
    }

    public static function is_brand_exist(string $brand): bool
    {
        return in_array($brand, self::BRANDS);
    }


    const TRANSMISSIONS = [
        'Automatic',
        'Manual',
        'Semi-Automatic',
    ];

    public static function get_transmissions(): array
    {
        return self::TRANSMISSIONS;
    }

    public static function is_transmission_exist(string $transmission): bool
    {
        return in_array($transmission, self::TRANSMISSIONS);
    }

    const BODY_TYPES = [
        'Sedan',
        'SUV',
        'Compact',
        'Wagon',
        'Coupe',
        'Van',
        'Hatchback',
        'Pickup',
        'Sport Coupe',
        'Saloon',
        'Truck',
        'Convertible',
    ];

    public static function get_body_types(): array
    {
        return self::BODY_TYPES;
    }

    public static function is_body_type_exist(string $body_type): bool
    {
        return in_array($body_type, self::BODY_TYPES);
    }


    const CONDITIONS = [
        'New', 'Used', 'Certified Used',
    ];

    public static function get_condititons(): array
    {
        return self::CONDITIONS;
    }

    public static function is_condition_exist(string $condition): bool
    {
        return in_array($condition, self::CONDITIONS);
    }

    const FUEL_TYPES = [
        'Diesel',
        'Electric',
        'Ethanol',
        'Fuel',
        'Gasoline',
        'Hybrid',
        'LPG Autogas',
    ];

    public static function get_fuel_types(): array
    {
        return self::FUEL_TYPES;
    }

    public static function is_fuel_type_exist(string $fuel_type): bool
    {
        return in_array($fuel_type, self::FUEL_TYPES);
    }

    const DRIVE_TYPES = [
        '4WD', 
        'AWD',
        'FWD',
        'RWD',
    ];

    public static function get_drive_types(): array
    {
        return self::DRIVE_TYPES;
    }

    public static function is_drive_type_exist(string $drive_type): bool
    {
        return in_array($drive_type, self::DRIVE_TYPES);
    }
}