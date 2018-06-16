<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
 * Constante que informa qual o sistema que esta sendo utilizado
 */
define('TIPO_SISTEMA_USUARIO', 'M');

/*
 * Constantes contendo as ações dos logs
 */

define('ENVIO_EMAIL_SUPORTE', 'Email enviado para o suporte');
define('ENVIO_EMAIL_USUARIO_CADASTRO', 'Email enviado para o usuario confirmando cadastro');
define('FALHA_ENVIO_EMAIL_USUARIO_CADASTRO', 'Falha ao enviar email com confirmação de cadastro do usuário');
define('ENVIO_EMAIL_USUARIO_DADOS_CADASTRO', 'Email enviado para o usuario confirmando cadastro');
define('FALHA_ENVIO_EMAIL_USUARIO_DADOS_CADASTRO', 'Falha ao enviar email com confirmação de cadastro do usuário');
define('ARQUIVO_INCLUIDO', 'Arquivo de modelo incluido');
define('ARQUIVO_DELETADO', 'Arquivo de modelo deletado');
define('USUARIO_INCLUIDO', 'Usuário incluido no sistema');
define('USUARIO_EDITADO', 'Usuário do sistema alterado');
define('USUARIO_DESATIVADO', 'Usuário do sistema desativado');
define('USUARIO_ATIVADO', 'Usuário do sistema ativado');
define('VINCULA_CNPJ', 'Vinculado CNPJ ao gestor');
define('VINCULA_CNPJ_VENDEDOR', 'Vinculado CNPJ ao vendedor');
define('VINCULA_CODIGO_PARLAMENTAR_VENDEDOR', 'Vinculado código de parlamentar ao vendedor');
define('ATUALIZACAO_CNPJ', 'Dados do CNPJ atualizados');
define('ALTERACAO_SENHA_PRIMEIRO_ACESSO', 'Alteração de senha de primeiro acesso');
define('GERACAO_PROGRAMAS_PDF', 'Gerado pdf de programas');
define('UTILZADO_PROJETO_PADRAO', 'Projeto padrão utilizado');
define('DELETADO_PROJETO_PADRAO', 'Projeto padrão deletado');
define('DELETADO_PROJETO', 'Projeto deletado');
define('TORNA_PROJETO_PADRAO', 'Tornado projeto como padrão');
define('DUPLICA_PROJETO', 'Duplicado projeto');
define('ALTERA_ENDERECO', 'Alteração de endereço');
define('INC_DADOS_PROJETO', 'Inclusão dados do projeto');
define('EDT_DADOS_PROJETO', 'Edição dados do projeto');
define('INC_JUSTI_PROJETO', 'Inclusão justificativa');
define('EDT_JUSTI_PROJETO', 'Edição justificativa');
define('INC_OBJ_PROJETO', 'Inclusão objeto');
define('EDT_OBJ_PROJETO', 'Edição objeto');
define('INC_META_PROJETO', 'Inclusão meta do projeto');
define('EDT_META_PROJETO', 'Edição meta do projeto');
define('INC_ETAPA_META', 'Inclusão etapa da meta');
define('EDT_ETAPA_META', 'Edição etapa da meta');
define('INC_CRONO_PROJETO', 'Inclusão crono projeto');
define('EDT_CRONO_PROJETO', 'Edição crono projeto');
define('INC_META_CRONO', 'Inclusão meta do crono');
define('EDT_META_CRONO', 'Edição meta do crono');
define('INC_ETAPA_CRONO', 'Inclusão etapa do crono');
define('EDT_ETAPA_CRONO', 'Edição etapa do crono');
define('INC_PLANO_PROJETO', 'Inclusão plano projeto');
define('EDT_PLANO_PROJETO', 'Edição plano projeto');
define('DEL_PLANO_PROJETO', 'Deleção plano projeto');
define('PERMISSAO_USUARIO_EDITADA', 'Editada permissão de usuário no sistema');
define('INICIA_UPDATE_PROGRAMAS', 'Iniciado Processo de Atualização das Propostas dos Gestores');
define('FINALIZA_UPDATE_PROGRAMAS', 'Finalizado Processo de Atualização das Propostas dos Gestores');
define('ENCARREGADOS_ADICIONADOS', 'Adicionados/Atualizados encarregados para um gestor');
define('INICIA_EXPORTA_SICONV', 'Iniciada exportação para o SICONV');
define('FINALIZA_EXPORTA_SICONV', 'Finalizado exportação para o SICONV');
define('EXCLUI_ARQ_CAPACIDADE', 'Exclusão de arquivo de declaração de capacidade tecnica');
define('EXCLUI_ARQ_CONTRAPARTIDA', 'Exclusão de arquivo de declaração de contrapartida financeira');
define('SESSAO_FINALIZADA_VENDEDOR', 'Sessão do vendedor finalizada');

/* End of file constants.php */
/* Location: ./application/config/constants.php */