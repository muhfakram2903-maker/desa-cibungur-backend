<?php

namespace App\Enums;

enum StatusPengaduan: string
{
    case MENUNGGU  = 'menunggu';
    case DIPROSES  = 'diproses';
    case SELESAI   = 'selesai';
    case DITOLAK   = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'Menunggu',
            self::DIPROSES => 'Diproses',
            self::SELESAI  => 'Selesai',
            self::DITOLAK  => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MENUNGGU => 'warning',
            self::DIPROSES => 'info',
            self::SELESAI  => 'success',
            self::DITOLAK  => 'danger',
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function selectOptions(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn ($case) => [$case->value => $case->label()]
        )->toArray();
    }
}
