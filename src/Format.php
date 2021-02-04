<?php
/**
 * Created by PhpStorm.
 * User: Marcos
 * Date: 25/10/2017
 * Time: 09:33 AM
 */

namespace src;

class Format
{
    public static function dateYyyymmddToMmddyyyy(
        string $yyyymmddDate,
        string $inputSeparator = '-',
        string $outputSeparator = '/'
    ): string {
        $vl_date_array = explode($inputSeparator, $yyyymmddDate);

        if (isset($vl_date_array['0'])) {
            $vl_event_year = $vl_date_array['0'];
        } else {
            $vl_event_year = '';
        }

        if (isset($vl_date_array['1'])) {
            $vl_event_month = $vl_date_array['1'];
        } else {
            $vl_event_month = '';
        }

        if (isset($vl_date_array['2'])) {
            $vl_event_day = $vl_date_array['2'];
        } else {
            $vl_event_day = '';
        }

        // use two digits months for output
        if (preg_match('~\d+~', $vl_event_month) &&
            $vl_event_month < 12) {
            $vl_event_month = (int) $vl_event_month;
            $vl_event_month = "0$vl_event_month";
        }


        // use two digits day for output
        if (preg_match('~\d+~', $vl_event_day) &&
            $vl_event_day < 10) {
            $vl_event_day = (int)$vl_event_day;
            $vl_event_day = "0$vl_event_day";
        }

        return "$vl_event_day$outputSeparator$vl_event_month$outputSeparator$vl_event_year";
    }
    public static function dateMmddyyyyToYyyymmdd(
        string $mmddyyyyDate,
        string $inputSeparator = '/',
        string $outputSeparator = '-'
    ): string {
        $vl_date_array = explode($inputSeparator, $mmddyyyyDate);


        if (isset($vl_date_array['0'])) {
            $vl_event_month = $vl_date_array['0'];
        } else {
            $vl_event_month = '';
        }

        if (isset($vl_date_array['1'])) {
            $vl_event_day = $vl_date_array['1'];
        } else {
            $vl_event_day = '';
        }

        if (isset($vl_date_array['2'])) {
            $vl_event_year = $vl_date_array['2'];
        } else {
            $vl_event_year = '';
        }

        // use two digits months for output
        if (preg_match('~\d+~', $vl_event_month) &&
            $vl_event_month < 12) {
            $vl_event_month = (int) $vl_event_month;
            $vl_event_month = "0$vl_event_month";
        }


        // use two digits day for output
        if (preg_match('~\d+~', $vl_event_day) &&
            $vl_event_day < 10) {
            $vl_event_day = (int)$vl_event_day;
            $vl_event_day = "0$vl_event_day";
        }

        return "$vl_event_year$outputSeparator$vl_event_month$outputSeparator$vl_event_day";
    }


    public static function friendlyString(string $string): string
    {
        $string = trim($string);

        $string = html_entity_decode($string);

        $string = strip_tags($string);

        $string = self::removeAccent($string);

        $string = strtolower($string);

        $string = preg_replace('~[^ a-z0-9_\.]~', ' ', $string);

        $string = preg_replace('~ ~', '-', $string);

        $string = preg_replace('~-+~', '-', $string);


        return $string;
    }

    public static function removeAccent($str)
    {

        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'à', 'á', 'â', 'ã', 'ä', 'å',
             'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë',
             'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï',
             'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø',  'ò', 'ó', 'ô', 'õ', 'ö', 'ø',
             'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü',
             'Ñ', 'ñ',
             'Ý', 'ý', 'ÿ');

        $b = array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
             'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
             'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i',
             'o', 'o', 'o', 'o', 'o', 'o',  'o', 'o', 'o', 'o', 'o', 'o',
             'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
             'n', 'n',
             'y', 'y', 'y');

        return str_replace($a, $b, $str);
    }


    public static function dateDashSymbol(string $dateString): string
    {
        return $dateString == '0000-00-00 00:00:00' ||
               $dateString == '0000-00-00' ?
                    '-' : Format::date($dateString);
    }


    public static function dateTimeDashSymbol(string $dateString): string
    {
        return $dateString == '0000-00-00 00:00:00' ||
               $dateString == '0000-00-00' ?
                    '-' : Format::dateTime($dateString);
    }

    public static function number(float $number, $decimals = 2): string
    {
        return number_format(
            $number,
            $decimals,
            Config::get('dec_point'),
            Config::get('thousands_sep')
        );
    }


    public static function date(string $date = ''): string
    {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if (!$dt) {
            $dt = \DateTime::createFromFormat('Y-m-d', $date);
        }
        return $dt->format(Config::get('date_format'));
    }


    public static function dateTime(string $date = ''): string
    {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if (!$dt) {
            $dt = \DateTime::createFromFormat('Y-m-d', $date);
        }
        return $dt->format(Config::get('date_time_format'));
    }

    /**
     * receives path string
     * return this path clean (removes things lik repeated /)
     *
     * by Marcos A. Botta <marcos.botta@gmail.com>
     * April 2012
     */
    public static function cleanPath($vp_path)
    {
        $vl_path_array = parse_url($vp_path);
        if (isset($vl_path_array['scheme']) &&
            $vl_path_array['scheme'] != '' &&
            strlen($vl_path_array['scheme']) > 1) {
            $vp_path = preg_replace("~{$vl_path_array['scheme']}://~", '', $vp_path);
            $vl_path_array['scheme'] = "{$vl_path_array['scheme']}://";
        } else {
            $vl_path_array['scheme'] = '';
        }

        // http://www.sitioWeb.com/../fotos/1001.jpg -> http://www.sitioWeb.com/fotos/1001.jpg
        if (isset($vl_path_array['host']) &&
            $vl_path_array['host'] != '') {
            $vp_path = preg_replace(
                "~{$vl_path_array['host']}\/\.\.\/~",
                "{$vl_path_array['host']}/",
                $vp_path
            ); // /./ -> /
        }
        $vp_path = preg_replace('~\/\.\/~', '/', $vp_path); // /./ -> /

        $vp_path = preg_replace('~\.\./~', 'DOUBLEDOT', $vp_path); // this is something we must keep on path
        $vp_path = preg_replace('~/(/)*~', '/', $vp_path);
        $vp_path = preg_replace('~^\./~', '', $vp_path);
        $vp_path = preg_replace('~\./~', '/', $vp_path);
        $vp_path = preg_replace('~DOUBLEDOT~', '../', $vp_path);

        // corrijo /../ en el inicio del path
        if (isset($vl_path_array['path']) &&
            $vl_path_array['path'] != '') {
            //   echo $vl_path_array['path']; die();
            // echo "~\/\.\.\/{$vl_path_array['path']}~"; echo '<br>';
            $vp_path = preg_replace(
                "~\/\.\.\/{$vl_path_array['path']}~",
                "/{$vl_path_array['path']}",
                $vp_path
            );
        }
        return "{$vl_path_array['scheme']}{$vp_path}";
    } // cleanPath()
}
