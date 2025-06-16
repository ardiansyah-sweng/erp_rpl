<?php

namespace App\Enums;

//menampilkan product type
enum ProductType: string
{
    case FG = 'FG';
    case RM = 'RM';
    case HFG = 'HFG';

    public function label(): string
    {
        return match($this) {
            self::RM => 'Raw Material',
            self::FG => 'Finished Good',
            self::HFG => 'Half Finished Goods',
        };
    }
}

