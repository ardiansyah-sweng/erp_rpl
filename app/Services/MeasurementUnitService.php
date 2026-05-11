<?php

namespace App\Services;

class MeasurementUnitService
{
    public static function getAll()
    {
        $units = config('measurement_units');
        $result = [];
        
        foreach ($units as $category => $categoryUnits) {
            foreach ($categoryUnits as $key => $unit) {
                $result[$key] = $unit;
            }
        }
        
        return $result;
    }
    
    public static function getByCategory($category)
    {
        return config("measurement_units.{$category}", []);
    }
    
    public static function getOptions()
    {
        $units = self::getAll();
        $options = [];
        
        foreach ($units as $key => $unit) {
            $options[$key] = "{$unit['name']} ({$unit['abbr']})";
        }
        
        return $options;
    }
}