<?php

namespace App\Helpers;

class NumeroALetras
{
    public static function convertir($numero)
    {
        $entero = (int) floor($numero);
        $decimales = (int) round(($numero - $entero) * 100);
        
        $letras = self::traducir($entero);
        $decimalesStr = str_pad($decimales, 2, '0', STR_PAD_LEFT);
        
        return strtoupper(trim($letras)) . " CON " . $decimalesStr . "/100 SOLES";
    }

    private static function traducir($numero)
    {
        $numero = (int) $numero; // Aseguramos que sea entero
        $unidades = ['', 'un ', 'dos ', 'tres ', 'cuatro ', 'cinco ', 'seis ', 'siete ', 'ocho ', 'nueve ', 'diez ', 'once ', 'doce ', 'trece ', 'catorce ', 'quince ', 'dieciséis ', 'diecisiete ', 'dieciocho ', 'diecinueve ', 'veinte '];
        $decenas = ['', '', '', 'treinta ', 'cuarenta ', 'cincuenta ', 'sesenta ', 'setenta ', 'ochenta ', 'noventa '];
        $centenas = ['', 'ciento ', 'doscientos ', 'trescientos ', 'cuatrocientos ', 'quinientos ', 'seiscientos ', 'setecientos ', 'ochocientos ', 'novecientos '];

        if ($numero == 0) {
            return 'cero';
        }
        if ($numero < 21) {
            return $unidades[$numero];
        }
        if ($numero < 100) {
            $dec = (int) floor($numero / 10);
            $uni = $numero % 10;
            if ($numero > 20 && $numero < 30) {
                return 'veinti' . trim($unidades[$uni]) . ' ';
            }
            return $decenas[$dec] . ($uni > 0 ? 'y ' . $unidades[$uni] : '');
        }
        if ($numero == 100) {
            return 'cien ';
        }
        if ($numero < 1000) {
            $cen = (int) floor($numero / 100);
            $resto = $numero % 100;
            return $centenas[$cen] . ($resto > 0 ? self::traducir($resto) : '');
        }
        if ($numero == 1000) {
            return 'mil ';
        }
        if ($numero < 1000000) {
            $miles = (int) floor($numero / 1000);
            $resto = $numero % 1000;
            $strMiles = ($miles == 1) ? 'mil ' : self::traducir($miles) . 'mil ';
            return $strMiles . ($resto > 0 ? self::traducir($resto) : '');
        }
        if ($numero == 1000000) {
            return 'un millón ';
        }
        if ($numero < 1000000000) {
            $millones = (int) floor($numero / 1000000);
            $resto = $numero % 1000000;
            $strMillones = ($millones == 1) ? 'un millón ' : self::traducir($millones) . 'millones ';
            return $strMillones . ($resto > 0 ? self::traducir($resto) : '');
        }
        
        return 'Número muy grande';
    }
}