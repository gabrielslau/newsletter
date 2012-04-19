<div class="mws-panel grid_8 newslettersssss newslettersgroups">
	<div class="mws-panel-header">
    	<span class="mws-i-24 i-list">Newsletters</span>
    </div>
    <div class="mws-panel-body">

    	<div class="mws-panel-toolbar top clearfix">
        	<ul>
            	<li><?php echo $this->Html->link(__('Adicionar nova Newsletter', true), array('action' => 'add'),array('class'=>'mws-button gray'));?></li>
            </ul>
        </div>
		
		<div class="dataTables_wrapper">
			<?php 
				if(empty($newsletters))
					echo '<div class="mws-message info">Nenhuma newsletter foi adicionada ainda. '.$this->Html->link(__('Que tal adicionar uma agora?', true), array('action' => 'add'),array('class'=>'mws-button blue')).'	</div>';
				else{
			?>
				<table class="mws-datatable mws-table">
					<thead>
						<tr class="thead">
							<th class="sorting"><?php echo $this->Paginator->sort('subject','Assunto');?></th>
							<th >Grupos de email</th>
							<th class="sorting"><?php echo $this->Paginator->sort('date_send','Data de envio');?></th>
							<!-- <th>&nbsp;</th> -->
						</tr>
					</thead><!-- cabeçalho -->
					<tbody>
					<?php
					$i = 0;
					foreach ($newsletters as $newsletter):
						$class = (++$i % 2 == 0) ? ' class="odd"' : ' class="even"';
					?>
					<tr <?php echo $class;?>>
						<td>
							<?php 
								echo '<strong>'.( ( $newsletter['Newsletter']['status'] == 0 ) ? $newsletter['Newsletter']['subject'] : $this->Html->link( $newsletter['Newsletter']['subject'], array('action' => 'edit', $newsletter['Newsletter']['id']) ) ).'</strong> ';

								if( $newsletter['Newsletter']['status'] == 0 ) echo ' <small><span class="hg-gray">Somente visualização</span></small>';
							?>
						</td>
						<td class="column-actions"  style="text-align:center;width:130px">
							<?php 
								if(empty($newsletter['Group'])){
								echo '<span class="hg-gray">-</span>';
								}else{
									foreach ($newsletter['Group'] as $emailgroup) {
										echo  ' <span class="hg-yellow">'. 

										$this->Html->link( utf8_decode($emailgroup['nome']) , array('controller'=>'groups','action' => 'view', $emailgroup['id']))

										.'</span> ' ;
										// echo  ' <span class="hg-yellow">'. utf8_decode($group['nome']).'</span> ' ;
									}
								}
							?>
						</td>
						<td class="column-actions" style="text-align:center">
							<div class="date" style="padding:10px">
							<?php 
								echo getDay($newsletter['Newsletter']['date_send']).'-';
								echo getMesAbr( getMonth($newsletter['Newsletter']['date_send']) ).'-';
								echo getYear($newsletter['Newsletter']['date_send']);
							?>
							</div>
							<div class="row-actionss">
								<?php 
									echo $this->Html->link(__('Ver', true), array('action' => 'view', $newsletter['Newsletter']['id']),array('class'=>'mws-button gray small'));

									// Mostra opção de Desabilitar e Editar, caso esteja ativado. Senão, Apenas opção de Reativar
									if( $newsletter['Newsletter']['status'] == 0 ) {
										// echo $this->Form->postLink(__('Reativar'), array('action' => 'enable', $newsletter['Newsletter']['id']), array('class'=>'submitdelete  mws-button red small'), __('Tem certeza que deseja reativar a Newsletter # %s?', $newsletter['Newsletter']['id']));
									}else{
										echo $this->Html->link(__('Editar', true), array('action' => 'edit', $newsletter['Newsletter']['id']),array('class'=>'mws-button gray small'));

										// echo $this->Form->postLink(__('Desativar'), array('action' => 'disable', $newsletter['Newsletter']['id']), array('id'=>'del-'.$newsletter['Newsletter']['id'],'class'=>'submitdelete  mws-button red small'), __('Tem certeza que deseja desativar a Newsletter # %s? \n Ela só ficará  acessível para visualização', $newsletter['Newsletter']['id']));
									}

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