<div id="content">
    <h1 class="bg-white content-heading border-bottom">Exportação</h1>
    <div class="innerAll spacing-x2">
        <div class="widget">
            <div class="widget-body">
				<table>
				<tbody>
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
					
					<?php } ?>
					<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
							Cronograma
						</td>
						<td class="field">
							<textarea onclick="this.select()" cols="50" rows="8"><?= $tela4;?></textarea>
						</td>
					</tr>
					<tr class="nomePrograma" id="tr-salvarNomePrograma">
						<td class="label">
							Meta Associada
						</td>
						<td class="field">
							<textarea onclick="this.select()" cols="50" rows="8"><?= $tela5;?></textarea>
						</td>
					</tr>
					
					<?php
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
						<?php } ?>
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