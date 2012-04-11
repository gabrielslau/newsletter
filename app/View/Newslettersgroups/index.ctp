<?php
	// print_r($newslettersgroups);exit();
?>
<div class="mws-panel grid_8 newslettersgroups">
	<div class="mws-panel-header">
    	<span class="mws-i-24 i-list">Grupos de Newsletters</span>
    </div>
    <div class="mws-panel-body">
		
		<div class="dataTables_wrapper">
			<?php 
				if(empty($newslettersgroups))
					echo '<div class="mws-message info">Nenhuma newsletter foi adicionada ainda</div>';
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
					foreach ($newslettersgroups as $group):
						$class = (++$i % 2 == 0) ? ' class="odd"' : ' class="even"';
					?>
					<tr <?php echo $class;?>>
						<td>
							<?php echo '<strong>'.$this->Html->link( $group['Newslettersgroup']['nome'], array('action' => 'edit', $group['Newslettersgroup']['id']) ).'</strong>';?>
						</td>
						<td class="column-id"><?php echo $group['Newslettersgroup']['newslettersemail_count'] ?></td>
						<td class="column-actions">
						<?php 
							echo $this->Html->link(__('Ver', true), array('action' => 'view', $group['Newslettersgroup']['id']),array('class'=>'mws-button gray small'));
							echo $this->Html->link(__('Editar', true), array('action' => 'edit', $group['Newslettersgroup']['id']),array('class'=>'mws-button gray small'));

							echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $group['Newslettersgroup']['id']), array('id'=>'del-'.$group['Newslettersgroup']['id'],'class'=>'submitdelete  mws-button red small'), __('Are you sure you want to delete # %s?', $group['Newslettersgroup']['id']));
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