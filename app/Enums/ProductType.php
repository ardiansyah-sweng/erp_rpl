<?php

namespace App\Enums;

enum ProductType: string
{
    case FG = 'Finished Goods';
    case RM = 'Raw Material';
    case HFG = 'Half Finished Goods';
}
