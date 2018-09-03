<?php

class Util_Model extends CI_Model {

    public function formata_data_padrao_br($mysql_data) {
        return date('d/m/Y', strtotime($mysql_data));
    }

    public function substituir_simbolo_por_texto($simbolo) {
        $resultado = '';
        switch ($simbolo) {
            case '>':
                $resultado = "maior que";
                break;
            case '<':
                $resultado = "menor que";
                break;
            case '=':
                $resultado = "igual a";
                break;
            case '>=':
                $resultado = "maior igual a";
                break;
            case '<=':
                $resultado = "menor igual a";
                break;
            case '!=':
                $resultado = "diferente de";
                break;
        }

        return $resultado;
    }

}
