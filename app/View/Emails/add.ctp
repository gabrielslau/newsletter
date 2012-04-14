<?php echo $this->Form->create('Email', array('class'=>'mws-form', 'id'=>'form_contentpost')) ?>
	<div class="mws-panel grid_8 pages">
		<div class="mws-panel-header">
	    	<span class="mws-i-24 i-list"><?php echo $title_for_layout?></span>
	    </div>
	    <div class="mws-panel-body">
			<div class="mws-form-inline">
			<?php
				if($this->action == 'edit') echo $this->Form->hidden('id', array('value'=>$this->Form->value('Email.id')));

				echo $this->Form->input('nome', array('type'=>'text', 'label' => array('text'=>'Nome *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Email']['nome']) ? $sessao_formulario['Email']['nome'] : $this->Form->value('Email.nome') ) ));
				
				echo $this->Form->input('email', array('type'=>'text', 'label' => array('text'=>'Email *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Email']['email']) ? $sessao_formulario['Email']['email'] : $this->Form->value('Email.email') ) ));

				$options_groups = array();
				if($this->action == 'edit'){
					foreach ($this->Form->value('Group') as $group) {
						$options_groups[] = $group['id'];
					}
				}

				echo $this->Form->input('Group.id', array('type'=>'select', 'label' => array('text'=>'Associe este email a um vários grupos *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'chzn-select', 'multiple'=>true, 'size'=>'10', 'options'=>$groups, 'default'=>$options_groups  ));

				/*if( isset($groups) && !empty($groups) ){
					// Monta Select de grupos do email
					echo '<div class="mws-form-row"><label>Grupos de email *</label><div class="mws-form-item small clearfix">';
						echo '<input type="hidden" id="GroupId_" value="" name="data[Group][id]">';
						echo '<select id="GroupId" size="10" multiple="multiple" class="chzn-select" name="data[Group][id][]">';
						foreach ($groups as $id=>$group) {
							$selected = in_array($id, $options_groups) ? ' selected="selected" ' : '';
							echo '<option value="'.$id.'" '.$selected.' >'.$group.'</option>';
						}
						echo '</select> <span class="mws-tooltip-s" title="Associe este email a um ou vários grupos"><span class="ui-icon ui-icon-info"></span></span>';
					echo '</div></div>';
				}*/
			?>

			<div class="mws-form-row notice">Todos os campos marcados com <span style="color:red">*</span> são de preenchimento obrigatório.</div>

    		</div>
    		<div class="mws-button-row">
    		<?php
				echo $this->Html->link(__('Ver todos os Emails', true), array('action' => 'index'),array('class'=>'mws-button gray small fl'));

				echo $this->action == 'add' ? '<input type="submit" value="Cadastrar" class="mws-button blue" />' : '<input type="submit" value="Atualizar" class="mws-button blue" />';
    		?>
    		</div>
    </div>    	
</div>
<?php echo $this->Form->end();?>