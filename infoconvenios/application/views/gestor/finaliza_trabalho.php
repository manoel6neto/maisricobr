<div id="content">
    <h1 class="bg-white content-heading border-bottom">FINALIZA TRABALHO</h1>
    <div class="innerAll spacing-x2">
        <div class="widget">
            <div class="widget-body">
            Fica aqui configurado TERMO DE RESPONSABILIDADE, a partir desta decisão tomada pelo 
usuário, que será imprescindível a entrada no Siconv, com respectiva senha de acesso, para 
inserir em anexo Termo de Capacidade Técnica, Declaração de Contrapartida e outros anexos 
que se fazem necessários para que a proposta venha a ser considerada apta. Bem como, 
posteriormente enviar para analise.
<br />
<br />
				<table>
				<tbody>
				<input type="button" value="<< Voltar" onClick="history.go(-1)">
				<input type="button" value="Aceito o Termo e dou permissão para exportar para o siconv" onclick="location.href='<?= base_url();?>index.php/in/gestor/exporta_siconv?id=<?= $id;?>';">
				<br>
					<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
							Cadastro de Programa
						</td>
						<td class="field">
							<textarea onclick="this.select()" cols="50" rows="8"><?= $tela1;?></textarea>
						</td>
					</tr>
					
					<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
							Justificativa
						</td>
						<td class="field">
							<textarea onclick="this.select()" cols="50" rows="8"><?= $tela2;?></textarea>
						</td>
					</tr>
					<?php foreach ($tela3 as $key=>$meta){ ?>
					<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
							Incluir Meta <?= $key+1;?>
						</td>
						<td class="field">
							<textarea onclick="this.select()" cols="50" rows="8"><?= $meta[1];?></textarea>
						</td>
					</tr>
						<?php
						$etapas = $trabalho_model->obter_saida_tela3_etapas($meta[0]);
						foreach ($etapas as $key1=>$etapa){ ?>
						<tr class="nomePrograma" id="tr-salvarNomePrograma">
							<td class="label"></td>
						<td class="field">
								Incluir Etapa <?= $key1+1;?>
							</td>
							<td class="field">
								<textarea onclick="this.select()" cols="50" rows="8"><?= $etapa;?></textarea>
							</td>
						</tr>
						<?php } ?>
					
					<?php } 
					foreach ($tela4 as $key=>$meta){ ?>
					<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
							Cronograma <?= $key+1;?>
						</td>
						<td class="field">
							<textarea onclick="this.select()" cols="50" rows="8"><?= $meta;?></textarea>
						</td>
					</tr>
					<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
							Meta Associada <?= $key+1;?>
						</td>
						<td class="field">
							<textarea onclick="this.select()" cols="50" rows="8"><?= $tela5[$key][1];?></textarea>
						</td>
					</tr>
					
					<?php
							$tela5_etapas = $trabalho_model->obter_saida_tela5_etapas($tela5[$key][0]);
							foreach ($tela5_etapas as $key1=>$etapa){ ?>
							<tr class="nomePrograma" id="tr-salvarNomePrograma">
								<td class="label"></td>
							<td class="field">
									Etapa Associada <?= $key1+1;?>
								</td>
								<td class="field">
									<textarea onclick="this.select()" cols="50" rows="8"><?= $etapa;?></textarea>
								</td>
							</tr>
							<?php }
						} ?>
						<?php
						foreach ($tela6 as $key1=>$despesa){ ?>
						<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
								Despesa <?= $key1+1;?>
							</td>
							<td class="field">
								<textarea onclick="this.select()" cols="50" rows="8"><?= $despesa;?></textarea>
							</td>
						</tr>
						<?php } ?>
						
				</tbody>
			</table>
</div>
</div>
</div>
</div>
