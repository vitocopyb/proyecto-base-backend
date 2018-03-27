<?php

namespace App\Lib;

class Utilities
{
    public static function obtenerFechaHoraActual()
    {
        date_default_timezone_set("Chile/Continental");
        $format = 'Y-m-d H:i:s.u';
        $utimestamp = microtime(true);

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

    public static function encriptarPassword($password)
    {
        $sal = '()%&9u_';
        $pimienta = '_#r%&KJ?';

        return md5($sal . $password . $pimienta);
    }

}

// TODO *** codigo para devolver una imagen con GD
// https://www.todavianose.com/como-devolver-una-imagen-al-src-de-un-img-con-php/
