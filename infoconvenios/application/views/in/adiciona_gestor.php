<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url();?>configuracoes/css/style.css">
<div id="content">
    <h1 class="bg-white content-heading border-bottom">ADICIONAR USUÁRIO</h1>
    <div class="innerAll spacing-x2">
        <div class="widget">
            <div class="widget-body">
			<form name="manterProgramaPropostaValoresDoProgramaSalvarForm" method="post" action="https://www.convenios.gov.br/siconv/ManterProgramaProposta/ValoresDoProgramaSalvar.do" enctype="multipart/form-data">
 
				<input type="hidden" id="id" value="-9">
				<table>
				<tbody>
				
					
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>Nome<div class="important">*</div></nobr>
						</td>
						<td class="field">
							<input type="text" name="nome" value=" " size="50px"  id="nome">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>CPF<div class="important">*</div></nobr>
						</td>
						<td class="field">
							<input type="text" name="nome" value=" " size="15px"  id="nome">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>login<div class="important">*</div></nobr>
						</td>
						<td class="field">
							<input type="text" name="login" value=" " size="15px"  id="login">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>Senha<div class="important">*</div></nobr>
						</td>
						<td class="field">
							<input type="password" name="senha" value=" " size="30px"  id="senha">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>Email<div class="important">*</div></nobr>
						</td>
						<td class="field">
							<input type="text" name="email" value=" " size="30px"  id="email">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>Telefone<div class="important">*</div></nobr>
						</td>
						<td class="field">
							<input type="text" name="telefone" value=" " size="15px"  id="telefone">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>Escolaridade</nobr>
						</td>
						<td class="field">
							<input type="text" name="escolaridade" value=" " size="40px"  id="escolaridade">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>Nome da Instituição</nobr>
						</td>
						<td class="field">
							<input type="text" name="nomeInstituicao" value=" " size="50px"  id="nomeInstituicao">
						</td>
					</tr>
					<tr class="valorGlobal" id="tr-salvarValorGlobal">
						<td class="label">
							<nobr>Endereço</nobr>
						</td>
						<td class="field">
							<input type="text" name="endereco" value=" " size="60px"  id="endereco">
						</td>
					</tr>
					<tr>
            <td>&nbsp;</td>
            <td class="FormLinhaBotoes">
                <input type="button" id="form_submit" onclick="setaAcao('/ConsultarProposta/PreenchaOsDadosDaConsultaConsultar?tipo_consulta=CONSULTA_COMPLETA', 'validatePreenchaOsDadosDaConsultaConsultarForm', true , 'consultarPropostaPreenchaOsDadosDaConsultaConsultarForm' )" value="Cadastrar" name="cadastraGestor">
              
                <input type="button" id="form_submit" onclick="setaAcao('/ConsultarProposta/PreenchaOsDadosDaConsultaLimpar', 'validatePreenchaOsDadosDaConsultaLimparForm', true , 'consultarPropostaPreenchaOsDadosDaConsultaLimparForm' )" value="Limpar" name="consultarPropostaPreenchaOsDadosDaConsultaLimparForm">
              
            </td>
                      </tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
</div>
</div>

