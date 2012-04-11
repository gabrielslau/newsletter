<?php
	// print_r($newslettersemails);exit();
	
	

?>
<?php echo $this->Form->input('session_id', array('type'=>'hidden','id'=>'SessionId', 'value'=> isset($SessionID) ? $SessionID : '' )); ?>
<div class="mws-panel grid_8 newslettersgroups">
	<div class="mws-panel-header">
    	<span class="mws-i-24 i-list">Contas de Emails</span>
    </div>
    <div class="mws-panel-body">

    	<div class="mws-panel-toolbar top clearfix">
        	<ul>
            	<!-- <li><?php echo $this->Html->link(__('Adicionar novo Email', true), array('action' => 'add'),array('class'=>'mws-button gray'));?></li> -->
            	<li><?php echo $this->Html->link(__('Importar lista de Emails', true), array('action' => 'import'),array('class'=>'mws-button gray', 'id'=>'ImportEmailPopUp'));?></li>
            </ul>
        </div>

        <div class="PopUpImport formulario">
        	<h1>Importar Emails</h1>
        	<p class="notice">
        		A lista de emails deve estar em um arquivo em formato <b>.CSV</b> (separados por <b>,</b> ) e fornecer as informações na seguinte ordem:
        	</p> 
        		
        		<blockquote>Email , Nome (opcional)</blockquote> 

        	<p class="notice">Se nenhuma categoria for selecionada, serão adicionados ao grupo <b>TODOS</b>.</p> 

        	<?php echo $this->Form->input('categorias_grupos',array('type'=>'select','options'=>$newslettersgroups, 'label'=>'Grupo de emails', 'div'=>array('class'=>'categorias_grupos_wrapper'), 'class'=>'chzn-select', 'multiple'=>true, 'default'=>'1','data-placeholder'=>'Clique para selecionar um grupo' )); ?>

        	<div class="boxupload">
        		<div class="upload-menu"><div class="buttom_choice"><input type="file" name="data[Newslettersemail][Filedata]" id="uploadify-galeria" /></div></div><br clear="all" /><div id="fileQueue-galeria"></div><div id="uploader_errors-galeria" class="ui-state-highlight mws-message hidden"></div><br clear="all" />
        	</div>
        </div>

		
		<div class="dataTables_wrapper">
			<?php 
				if(empty($newslettersemails))
					echo '<div class="mws-message info">Nenhum email foi adicionado ainda</div>';
				else{
			?>

				<?php 
					/*echo $this->element("uploadify", array(
						    "dom_id" => "file_upload",
						    "session_id" => $SessionID,
						    // "include_scripts" => array('jquery','uploadify','swfobject','uploadify_css'),
						    "options" => array(
								"script"         => $this->webroot."newslettersemails/temp_upload_csv",
								'folder'         => '/'.$this->webroot.APP_DIR.'/'.WEBROOT_DIR.'/files/uploads',
								'queueSizeLimit' => 1,
								'auto'           => true,
								'multi'          => false,
								'fileDesc'       => 'Arquivos .CSV',
								'fileExt'        =>  '*.csv;',
								'buttonText'     => 'Importar',
								'onError'		=> "function(event,queueID,fileObj,errorObj){
													console.log(event);
													console.log(queueID);
													console.log(fileObj);
													console.log(errorObj);
												}",
								'onComplete'	=> "function(event,queueID,fileObj,response,data){console.log(response);}"
						    )
					    ),
						array(
							"plugin" => "Cuploadify"
						)
					);

					echo $this->Form->create('Newslettersemail', array('type' => 'file', 'controller'=>'newslettersemails','action'=>'upload_profile_pic',$SessionID));
						echo $this->Form->file('teste',array('name'=>'Filedata'));
						echo $this->Form->input('folder',array('type'=>'hidden','name'=>'folder','value'=>'/'.$this->webroot.'app/webroot/files/uploads'));
					echo $this->Form->end('Enviar');*/
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
					foreach ($newslettersemails as $email):
						$class = (++$i % 2 == 0) ? ' class="odd"' : ' class="even"';
					?>
					<tr <?php echo $class;?>>
						<td>
							<?php echo '<strong>'.$this->Html->link( $email['Newslettersemail']['nome'], array('action' => 'edit', $email['Newslettersemail']['id']) ).'</strong>';?>
						</td>
						<td><?php echo $email['Newslettersemail']['email'] ?></td>
						<td class="column-actions">
						<?php 
							if(empty($email['Newslettersgroup'])){
								echo '<span class="hg-gray">-</span>';
							}else{
								foreach ($email['Newslettersgroup'] as $group) {
									echo  ' <span class="hg-yellow">'. $group['nome'].'</span> ' ;
								}
							}
						?>
						</td>
						<td class="column-actions">
						<?php 
							// echo $this->Html->link(__('Editar', true), array('action' => 'edit', $email['Newslettersemail']['id']),array('class'=>'mws-button gray small'));

							echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $email['Newslettersemail']['id']), array('id'=>'del-'.$email['Newslettersemail']['id'],'class'=>'submitdelete  mws-button red small'), __('Are you sure you want to delete # %s?', $email['Newslettersemail']['id']));
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