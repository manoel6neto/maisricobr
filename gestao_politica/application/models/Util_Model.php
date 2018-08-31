<?php

class Util_Model extends CI_Model {

    public function formata_data_padrao_br($mysql_data) {
        return date('d/m/Y', strtotime($mysql_data));
    }

}
