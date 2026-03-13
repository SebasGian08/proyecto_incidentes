<?php


namespace BolsaTrabajo;


use Carbon\Carbon;

class App
{
    public static $PERFIL_EMPRESA = 1;
    public static $PERFIL_ALUMNO = 2;

    public static $TIPO_ALUMNO = 0;
    public static $TIPO_EGRESADO = 1;
    public static $TIPO_TITULADO = 2;

    public static $HABILIDAD_PERSONAL = 1;
    public static $HABILIDAD_PROFESIONAL = 2;

    public static $ESTADO_POSTULANTES = 1;
    public static $ESTADO_EVALUANDO = 2;
    public static $ESTADO_SELECCIONADOS = 3;
    public static $ESTADO_ACEPTADOS = 4;
    public static $ESTADO_DESCARTADOS = 5;

    public static $PERFIL_DESARROLLADOR= 1;
    public static $PERFIL_ADMINISTRADOR = 2;
    public static $PERFIL_JEFE = 3;
    public static $PERFIL_ANALISTA_VENDEDOR= 4;
    public static $PERFIL_VENDEDOR = 5;
    public static $PERFIL_CLIENTE_ASOCIADO = 6;
    public static $PERFIL_MOTORIZADO = 7;
   

    public static function ObtenerNombreMes($mes)
    {
        $nombre = "";

        switch ($mes){
            case 1 : $nombre = "Enero"; break;
            case 2 : $nombre = "Febrero"; break;
            case 3 : $nombre = "Marzo"; break;
            case 4 : $nombre = "Abril"; break;
            case 5 : $nombre = "Mayo"; break;
            case 6 : $nombre = "Junio"; break;
            case 7 : $nombre = "Julio"; break;
            case 8 : $nombre = "Agosto"; break;
            case 9 : $nombre = "Setiembre"; break;
            case 10 : $nombre = "Octubre"; break;
            case 11 : $nombre = "Noviembre"; break;
            case 12 : $nombre = "Diciembre"; break;
        }

        return $nombre;
    }

    public static function formatDateStringSpanish($date)
    {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($date);
        $mes = $meses[($fecha->format('n')) - 1];
        $date = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

        return $date;
    }


}
