<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum JilidEnum: string implements HasLabel
{
    case jilid1 = 'jilid 1';
    case jilid2 = 'jilid 2';
    case jilid3 = 'jilid 3';
    case jilid4 = 'jilid 4';
    case jilid5 = 'jilid 5';
    case jilid6 = 'jilid 6';
    case quran = 'al quran';
    
    public function getLabel(): ?string
    {
        return $this->name;        
    }
}