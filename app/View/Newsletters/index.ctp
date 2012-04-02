<div class="mws-panel grid_8 newsletters">
	<div class="mws-panel-header">
    	<span class="mws-i-24 i-list">Newsletters</span>
    </div>
    <div class="mws-panel-body">
		
		<div class="dataTables_wrapper">
			<?php 
				if(empty($newsletters))
					echo '<div class="mws-message info">Nenhuma newsletter foi adicionada ainda</div>';
				else{
			?>
				<table class="mws-datatable mws-table">
					<thead>
						<tr class="thead">
							<th class="sorting"><?php echo $this->Paginator->sort('subject','Assunto');?></th>
							<th class="sorting"><?php echo $this->Paginator->sort('emailbody','Conteúdo');?></th>
							<th class="sorting"><?php echo $this->Paginator->sort('created','Data');?></th>
							<!-- <th>&nbsp;</th> -->
						</tr>
					</thead><!-- cabeçalho -->
					<tbody>
					<?php
					$i = 0;
					foreach ($newsletters as $newsletter):
						$class = (++$i % 2 == 0) ? ' class="gradeA odd"' : ' class="gradeA even"';
					?>
					<tr <?php echo $class;?>>
						<td class="column-titulo options">
							<?php echo '<strong>'.$this->Html->link( $newsletter['Newslettersqueue']['subject'], array('action' => 'edit', $newsletter['Newslettersqueue']['id']) ).'</strong><br />';?>
						</td>
						<td><?php echo limit_words(strip_tags($newsletter['Newslettersqueue']['emailbody']),27); ?>&nbsp;</td>
						<td>
							<div class="date" style="padding:10px;text-align:center">
							<?php 
								echo getDay($newsletter['Newslettersqueue']['created']).'-';
								echo getMesAbr( getMonth($newsletter['Newslettersqueue']['created']) ).'-';
								echo getYear($newsletter['Newslettersqueue']['created']);
							?>
							</div>
							<div class="row-actionss">
								<?php 
									echo $this->Html->link(__('Ver', true), array('action' => 'view', $newsletter['Newslettersqueue']['id']),array('class'=>'mws-button gray small'));
									echo $this->Html->link(__('Editar', true), array('action' => 'edit', $newsletter['Newslettersqueue']['id']),array('class'=>'mws-button gray small'));

									echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $newsletter['Newslettersqueue']['id']), array('id'=>'del-'.$newsletter['Newslettersqueue']['id'],'class'=>'submitdelete  mws-button red small'), __('Are you sure you want to delete # %s?', $newsletter['Newslettersqueue']['id']));
								?>
							</div>
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