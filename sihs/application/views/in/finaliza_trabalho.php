<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>configuracoes/css/style.css">
<div id="content">
<div id="salvar" class="action">
<div class="trigger">
Fica aqui configurado TERMO DE RESPONSABILIDADE, a partir desta decisão tomada pelo 
usuário, que será imprescindível a entrada no Siconv, com respectiva senha de acesso, para 
inserir em anexo Termo de Capacidade Técnica, Declaração de Contrapartida e outros anexos 
que se fazem necessários para que a proposta venha a ser considerada apta. Bem como, 
posteriormente enviar para analise.
<br />
<br />
<input type="button" value="Aceito o Termo e dou permissão para exportar para o siconv" onclick="location.href='<?php echo base_url(); ?>index.php/in/gestor/exporta_siconv?id=<?php echo $id; ?>';">
</div>
<br class="clr">
