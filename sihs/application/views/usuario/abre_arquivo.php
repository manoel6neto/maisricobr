<?php
$nomeArquivo = "gera_arquivos/".$arquivo->nome_arquivo;

$file = fopen($nomeArquivo,"a+");
fwrite($file, $arquivo->arquivo);
fclose($file);

chmod($nomeArquivo, 0777);

//Forçando o download...
header("Content-type: ".$arquivo->tipo);
header("Content-Disposition: attachment; filename=" . str_replace(" ", "_", $arquivo->nome_arquivo));
header("Content-Length: " . $arquivo->tamanho);
header("Content-Transfer-Encoding: binary");
readfile($nomeArquivo);

//Apagando o arquivo
unlink($nomeArquivo);
?>