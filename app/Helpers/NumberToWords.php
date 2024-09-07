<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convert($number)
    {
        $units = ['',' SATU',' DUA',' TIGA',' EMPAT',' LIMA',' ENAM',' TUJUH',' DELAPAN',' SEMBILAN'];
        $teens = [' SEPULUH',' SEBELAS',' DUA BELAS',' TIGA BELAS',' EMPAT BELAS',' LIMA BELAS',' ENAM BELAS',' TUJUH BELAS',' DELAPAN BELAS',' SEMBILAN BELAS'];
        $tens = ['',' SEPULUH',' DUA PULUH',' TIGA PULUH',' EMPAT PULUH',' LIMA PULUH',' ENAM PULUH',' TUJUH PULUH',' DELAPAN PULUH',' SEMBILAN PULUH'];
        $thousands = ['',' RIBU',' JUTA',' MILIAR',' TRILIUN'];

        if ($number == 0) return 'NOL';

        $number = intval($number);
        $words = '';

        foreach ($thousands as $i => $thousand) {
            $divider = pow(1000, $i + 1);
            $number_part = intval($number % $divider / pow(1000, $i));

            if ($number_part) {
                $prefix = $i > 0 ? ($number_part < 100 ? ' ' : '') . $thousands[$i] : '';
                $words = self::convertLessThanThousand($number_part) . $prefix . $words;
            }
        }

        return trim($words) . ' PERAK';
    }

    private static function convertLessThanThousand($number)
    {
        $units = ['',' SATU',' DUA',' TIGA',' EMPAT',' LIMA',' ENAM',' TUJUH',' DELAPAN',' SEMBILAN'];
        $teens = [' SEPULUH',' SEBELAS',' DUA BELAS',' TIGA BELAS',' EMPAT BELAS',' LIMA BELAS',' ENAM BELAS',' TUJUH BELAS',' DELAPAN BELAS',' SEMBILAN BELAS'];
        $tens = ['',' SEPULUH',' DUA PULUH',' TIGA PULUH',' EMPAT PULUH',' LIMA PULUH',' ENAM PULUH',' TUJUH PULUH',' DELAPAN PULUH',' SEMBILAN PULUH'];

        if ($number < 10) return $units[$number];
        if ($number < 20) return $teens[$number - 10];
        if ($number < 100) return $tens[intval($number / 10)] . ($number % 10 ? $units[$number % 10] : '');
        if ($number < 1000) return $units[intval($number / 100)] . ' RATUS' . ($number % 100 ? ' ' . self::convertLessThanThousand($number % 100) : '');

        return '';
    }
}
