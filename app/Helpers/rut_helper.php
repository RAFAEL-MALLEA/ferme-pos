<?php

/**
 * Helper de validacion de RUT chileno.
 * No existia en el proyecto original (no hay evidencia de validacion de RUT
 * en los logs), pero dado que datos_personales.rut es clave primaria y se
 * usa como FK en usuario, venta, etc., validar el digito verificador ANTES
 * de tocar la base de datos evita gran parte de los errores de integridad
 * ("Column cannot be null", FK violations) que se veian en los logs.
 */
if (! function_exists('limpiar_rut')) {
    function limpiar_rut(string $rut): string
    {
        return strtoupper(preg_replace('/[^0-9kK]/', '', $rut));
    }
}

if (! function_exists('dv_rut')) {
    function dv_rut(string $rutSinDv): string
    {
        $suma  = 0;
        $multi = 2;

        for ($i = strlen($rutSinDv) - 1; $i >= 0; $i--) {
            $suma += ((int) $rutSinDv[$i]) * $multi;
            $multi = ($multi === 7) ? 2 : $multi + 1;
        }

        $resto = 11 - ($suma % 11);

        if ($resto === 11) {
            return '0';
        }
        if ($resto === 10) {
            return 'K';
        }

        return (string) $resto;
    }
}

if (! function_exists('validar_rut')) {
    function validar_rut(string $rutCompleto): bool
    {
        $limpio = limpiar_rut($rutCompleto);

        if (strlen($limpio) < 2) {
            return false;
        }

        $cuerpo = substr($limpio, 0, -1);
        $dv     = strtoupper(substr($limpio, -1));

        if (! ctype_digit($cuerpo)) {
            return false;
        }

        return dv_rut($cuerpo) === $dv;
    }
}

if (! function_exists('formatear_rut')) {
    function formatear_rut(string $rutCompleto): string
    {
        $limpio = limpiar_rut($rutCompleto);
        $cuerpo = substr($limpio, 0, -1);
        $dv     = substr($limpio, -1);

        return number_format((int) $cuerpo, 0, '', '.') . '-' . $dv;
    }
}
