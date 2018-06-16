<?php

class data_model extends CI_Model {

    public function retornaDiffDatas($dataIni, $dataFim, $diffEmDias = true) {
        $timeStampIni = $this->geraTimestamp($dataIni);
        $timeStampFim = $this->geraTimestamp($dataFim);

        $diff = $timeStampFim - $timeStampIni;
        if ($diffEmDias)
            return (int) floor($diff / (60 * 60 * 24));
        else
            return $diff;
    }

    public function geraTimestamp($data) {
        $partes = explode('-', $data);

        return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
    }

    public function retornaNovaData($data, $qtdTempo, $incrementaMes = false) {
        $d = explode("-", $data);
        if (!$incrementaMes) {
            return date("Y-m-d", mktime(0, 0, 0, $d[1], $d[2] + $qtdTempo, $d[0]));
        } else {
            return date("Y-m-d", mktime(0, 0, 0, $d[1] + $qtdTempo, $d[2], $d[0]));
        }
    }

    public function calcula_datas_inicio($dataInicioProjeto_old, $dataInicioProjeto_new, $dataInicio_old) {
        $diffData = $this->retornaDiffDatas($dataInicioProjeto_old, $dataInicio_old);

        if ($diffData <= 0)
            return $dataInicioProjeto_new;
        else
            return $this->retornaNovaData($dataInicioProjeto_new, $diffData);
    }

    public function retornaMesesDiff($dataIni, $dataFim) {
        $diff = $this->retornaDiffDatas($dataIni, $dataFim, false);
        return $this->time2text($diff);
    }

    public function get_meses_to_time($time) {
        $months = floor($time / (86400 * 30));
        return $months;
    }

    function time2text($time) {
        $response = array();
//     	$years = floor($time/(86400*365));
//     	$time=$time%(86400*365);
        $months = floor($time / (86400 * 30));
//     	$time=$time%(86400*30);
//     	$days = floor($time/86400);
//     	$time=$time%86400;
//     	$hours = floor($time/(3600));
//     	$time=$time%3600;
//     	$minutes = floor($time/60);
//     	$seconds=$time%60;
//     	if($years>0) $response[]=$years.' ano'. ($years>1?'s':' ');
        if ($months > 0)
            $response[] = $months . ' mes' . ($months > 1 ? 'es' : ' ');
//     	if($days>0) $response[]=$days.' dia' .($days>1?'s':' ');
//     	if($hours>0) $response[]=$hours.' hora'.($hours>1?'s':' ');
//     	if($minutes>0) $response[]=$minutes.' minuto' . ($minutes>1?'s':' ');
//     	if($seconds>0) 		$response[]=$seconds.' segundo' . ($seconds>1?'s':' ');
        return implode(', ', $response);
    }

}
