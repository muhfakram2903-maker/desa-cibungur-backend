<?php

namespace App\Enums;

enum StatusBerita: string
{
    case DRAFT     = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED  = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT     => 'Draft',
            self::PUBLISHED => 'Diterbitkan',
            self::ARCHIVED  => 'Diarsipkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT     => 'secondary',
            self::PUBLISHED => 'success',
            self::ARCHIVED  => 'dark',
        };
    }
}
