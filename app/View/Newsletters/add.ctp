<?php echo $this->Form->create('Newslettersqueue', array('class'=>'mws-form', 'id'=>'form_contentpost','type' => 'file')) ?>
	<div class="mws-panel grid_8 pages">
		<div class="mws-panel-header">
	    	<span class="mws-i-24 i-list">Adicionar nova página</span>
	    </div>
	    <div class="mws-panel-body">
			<div class="mws-form-inline">
			<?php
				if($this->action == 'edit') echo $this->Form->hidden('id', array('value'=>$this->Form->value('Newslettersqueue.id')));

				echo $this->Form->input('subject', array('type'=>'text', 'label' => array('text'=>'Assunto *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Newslettersqueue']['subject']) ? $sessao_formulario['Newslettersqueue']['subject'] : $this->Form->value('Newslettersqueue.subject') ) ));

				echo $this->Form->input('emailbody', array('type'=>'textarea', 'label' => array('text'=>'Conteúdo *'), 'div'=>array('class'=>'mws-form-row ckeditor'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'value'=>(isset($sessao_formulario['Newslettersqueue']['emailbody']) ? $sessao_formulario['Newslettersqueue']['emailbody'] : $this->Form->value('Newslettersqueue.emailbody') ) ));


				/*if( isset($newslettersgroups) && !empty($newslettersgroups) ){
					echo '<div class="mws-form-row"><label>Selecione os grupos que receberão os emails</label><div class="mws-form-item clearfix"><ul class="mws-form-list inline">';
						// echo $this->Form->input('to_groups_id',array('type'=>'checkbox','options'=>$newslettersgroups));
						foreach ($newslettersgroups as $id=>$group) {
							// echo '<li>'.$this->Form->checkbox('to_groups_id',array('value'=>$id,'label'=>$group)).'</li>';
							echo '<li>'.$this->Form->input('to',array('type'=>'checkbox','options'=>$id,'label'=>array('text'=>$group), 'id'=>'NewslettersqueueToGroupsId'.$id )).'</li>';
						}

					echo '</ul></div></div>';
				}*/

				echo $this->Form->input('to', array('type'=>'select', 'label' => array('text'=>'Selecione os grupos que receberão os emails *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-col-5w-8 alpha"><div class="mws-form-item small">', 'after'=>'</div></div>', 'class'=>'chzn-select', 'multiple'=>true, 'size'=>'10', 'options'=>$newslettersgroups, 'default'=>(isset($sessao_formulario['Newslettersqueue']['to']) ? $sessao_formulario['Newslettersqueue']['to'] : explode(',',$this->Form->value('Newslettersqueue.to')) ) ));
				
				// echo $this->Form->value('Newslettersqueue.to');

				echo $this->Form->input('data_envio', array('type'=>'text', 'label' => array('text'=>'Data de envio *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-col-2-8 alpha"><div class="mws-form-item small">', 'after'=>'</div></div>', 'class'=>'mws-textinput mws-dtpicker ', 'value'=>(isset($sessao_formulario['Newslettersqueue']['data_envio']) ? $sessao_formulario['Newslettersqueue']['data_envio'] : $this->Form->value('Newslettersqueue.data_envio') ) ));







			?>

			<div class="mws-form-row notice">Todos os campos marcados com <span style="color:red">*</span> são de preenchimento obrigatório.</div>
			<?php 
				// if($this->action == 'add') echo '<div class="mws-form-row notice">Para incluir um documento anexo à página, é necessário cadastrá-la primeiramente.</div>';
			?>

    		</div>
    		<div class="mws-button-row">
    		<?php
    			// echo $this->action == 'edit' ? $this->Form->postLink(__('Deletar esta newsletter'), array('action' => 'delete', $this->Form->value('Newslettersqueue.id')), array('id'=>'del-'.$this->Form->value('Newslettersqueue.id'),'class'=>'mws-button red small fl'), __('Tem certeza que deseja excluir a Página # %s?', $this->Form->value('Newslettersqueue.slug'))) : '';

				echo $this->Html->link(__('Ver todas as Newsletters', true), array('action' => 'index'),array('class'=>'mws-button gray small fl'));

				echo $this->action == 'edit' ? '<span class="notice mr20">Publicado <strong><time datetime="'. $this->Form->value('Newslettersqueue.created') .'">'. getTimeAgo($this->Form->value('Newslettersqueue.created')) .'</time></strong></span>' : '';

				echo $this->action == 'add' ? '<input type="submit" value="Cadastrar" class="mws-button blue" />' : '<input type="submit" value="Atualizar" class="mws-button blue" />';
    		?>
    		</div>
    </div>    	
</div>
<?php echo $this->Form->end();?>





