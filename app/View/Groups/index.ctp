<div class="mws-panel grid_8 newslettersgroups groups">
	<div class="mws-panel-header">
    	<span class="mws-i-24 i-list">Grupos de Emails</span>
    </div>
    <div class="mws-panel-body">
		
    	<div class="mws-panel-toolbar top clearfix">
        	<ul>
            	<li><?php echo $this->Html->link(__('Adicionar novo Grupo', true), array('action' => 'add'),array('class'=>'mws-button gray'));?></li>
            </ul>
        </div>

		<div class="dataTables_wrapper">
			<?php 
				if(empty($groups))
					echo '<div class="mws-message info">Nenhum Grupo de email foi adicionado ainda. '.$this->Html->link(__('Que tal adicionar um agora?', true), array('action' => 'add'),array('class'=>'mws-button blue')).'</div>';
				else{
			?>
				<table class="mws-datatable mws-table">
					<thead>
						<tr class="thead">
							<th class="sorting"><?php echo $this->Paginator->sort('nome','Nome do grupo');?></th>
							<th>Emails cadastrados</th>
							<th>&nbsp;</th>
						</tr>
					</thead><!-- cabeçalho -->
					<tbody>
					<?php
					$i = 0;
					foreach ($groups as $group):
						$class = (++$i % 2 == 0) ? ' class="odd"' : ' class="even"';
					?>
					<tr <?php echo $class;?>>
						<td>
							<?php echo '<strong>'.$this->Html->link( $group['Group']['nome'], array('action' => 'edit', $group['Group']['id']) ).'</strong>';?>
						</td>
						<td class="column-id"><?php echo $group['Group']['email_count'] ?></td>
						<td class="column-actions">
						<?php 
							// echo $this->Html->link(__('Ver', true), array('action' => 'view', $group['Group']['id']),array('class'=>'mws-button gray small'));
							echo $this->Html->link(__('Editar', true), array('action' => 'edit', $group['Group']['id']),array('class'=>'mws-button gray small'));

							echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $group['Group']['id']), array('id'=>'del-'.$group['Group']['id'],'class'=>'submitdelete  mws-button red small'), __('Are you sure you want to delete # %s?', $group['Group']['id']));
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