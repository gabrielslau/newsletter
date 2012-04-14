<?php echo $this->Form->input('session_id', array('type'=>'hidden','id'=>'SessionId', 'value'=> isset($SessionID) ? $SessionID : '' )); ?>
<div class="mws-panel grid_8 newslettersgroups">
	<div class="mws-panel-header">
    	<span class="mws-i-24 i-list">Contas de Emails</span>
    </div>
    <div class="mws-panel-body">

    	<div class="mws-panel-toolbar top clearfix">
        	<ul>
            	<li><?php echo $this->Html->link(__('Adicionar novo Email', true), array('action' => 'add'),array('class'=>'mws-button gray'));?></li>
            	<li><?php echo $this->Html->link(__('Importar lista de Emails', true), '#',array('class'=>'mws-button gray', 'id'=>'ImportEmailPopUp'));?></li>
            </ul>
        </div>

        <div class="PopUpImport formulario">
        	<h1>Importar Emails</h1>
        	<p class="notice">
        		A lista de emails deve estar em um arquivo em formato <b>.CSV</b> (separados por <b>,</b> ) e fornecer as informações na seguinte ordem:
        	</p> 
        		
        		<blockquote>Email , Nome (opcional)</blockquote> 

        	<p class="notice">Se nenhuma categoria for selecionada, serão adicionados ao grupo <b>TODOS</b>.</p> 

        	<?php echo $this->Form->input('categorias_grupos',array('type'=>'select','options'=>$groups, 'label'=>'Grupo de emails', 'div'=>array('class'=>'categorias_grupos_wrapper'), 'class'=>'chzn-select', 'multiple'=>true, 'default'=>'1','data-placeholder'=>'Clique para selecionar um grupo' )); ?>

        	<div class="boxupload">
        		<div class="upload-menu"><div class="buttom_choice"><input type="file" name="data[Email][Filedata]" id="uploadify-galeria" /></div></div><br clear="all" /><div id="fileQueue-galeria"></div><div id="uploader_errors-galeria" class="ui-state-highlight mws-message hidden"></div><br clear="all" />
        	</div>
        </div>

		
		<div class="dataTables_wrapper">
			<?php 
				if(empty($emails))
					echo '<div class="mws-message info">Nenhum email foi adicionado ainda. '.$this->Html->link(__('Que tal adicionar uma agora?', true), array('action' => 'add'),array('class'=>'mws-button blue')).'</div>';
				else{
			?>
				<table class="mws-datatable mws-table">
					<thead>
						<tr class="thead">
							<th class="sorting"><?php echo $this->Paginator->sort('nome');?></th>
							<th class="sorting"><?php echo $this->Paginator->sort('email');?></th>
							<th>Grupo(s)</th>
							<th>&nbsp;</th>
						</tr>
					</thead><!-- cabeçalho -->
					<tbody>
					<?php
					$i = 0;
					foreach ($emails as $email):
						$class = (++$i % 2 == 0) ? ' class="odd"' : ' class="even"';
					?>
					<tr <?php echo $class;?>>
						<td class="column-actions">
							<?php echo '<strong>'.$this->Html->link( $email['Email']['nome'], array('action' => 'edit', $email['Email']['id']) ).'</strong>';?>
						</td>
						<td class="column-actions"><?php echo $email['Email']['email'] ?></td>
						<td>
						<?php 
							if(empty($email['Group'])){
								echo '<span class="hg-gray">-</span>';
							}else{
								foreach ($email['Group'] as $group) {
									echo  ' <span class="hg-yellow">'. $group['nome'].'</span> ' ;
								}
							}
						?>
						</td>
						<td class="column-actions">
						<?php 
							echo $this->Html->link(__('Editar', true), array('action' => 'edit', $email['Email']['id']),array('class'=>'mws-button gray small'));

							echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $email['Email']['id']), array('id'=>'del-'.$email['Email']['id'],'class'=>'submitdelete  mws-button red small'), __('Are you sure you want to delete # %s?', $email['Email']['id']));
						?>
						</td>
						<!-- <td>
							
						</td> -->
					</tr>
					<?php endforeach; ?>
					</tbody><!-- conteudo -->
				</table>
				<div class="dataTables_info">
					<?php 
						echo $this->Paginator->counter(array('format' => __('Exibindo {:start} - {:end} de aproximadamente {:count}', true)))
					?>
				</div>
				<?php 
					echo $this->element('pagination/navigate_mwsadmin');
				?>
			<?php
				}
			?>
		</div>
    </div>
</div>