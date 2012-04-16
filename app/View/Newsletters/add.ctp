<?php echo $this->Form->create('Newsletter', array('class'=>'mws-form', 'id'=>'form_contentpost','type' => 'file')) ?>
	<div class="mws-panel grid_8 pages">
		<div class="mws-panel-header">
	    	<span class="mws-i-24 i-list"><?php echo $title_for_layout?></span>
	    </div>
	    <div class="mws-panel-body">
			<div class="mws-form-inline">
			<?php
				if($this->action == 'edit') echo $this->Form->hidden('id', array('value'=>$this->Form->value('Newsletter.id')));

				echo $this->Form->input('subject', array('type'=>'text', 'label' => array('text'=>'Assunto *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Newsletter']['subject']) ? $sessao_formulario['Newsletter']['subject'] : $this->Form->value('Newsletter.subject') ), 'maxlength'=>100 ));

				echo $this->Form->input('emailbody', array('type'=>'textarea', 'label' => array('text'=>'Conteúdo *'), 'div'=>array('class'=>'mws-form-row ckeditor'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'value'=>(isset($sessao_formulario['Newsletter']['emailbody']) ? $sessao_formulario['Newsletter']['emailbody'] : $this->Form->value('Newsletter.emailbody') ) ));


				$options_groups = array();
				$form_groups = $this->Form->value('Group');
				if($this->action == 'edit' && !empty($form_groups)){
					// print_r($this->Form->value('Group'));exit();
					foreach ($form_groups as $group) {
						$options_groups[] = $group['id'];
					}
				}
				$options_emails = array();
				$form_emails = $this->Form->value('Email');
				if($this->action == 'edit' && !empty($form_emails)){
					// print_r($form_emails);exit();
					foreach ($form_emails as $email) {
						$options_emails[] = $email['id'];
					}
				}
				echo $this->Form->input('Group.id', array('type'=>'select', 'label' => array('text'=>'Grupos de email *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-col-5w-8 alpha"><div class="mws-form-item small">', 'after'=>'</div></div>', 'class'=>'chzn-select', 'multiple'=>true, 'size'=>'10', 'options'=>$groups, 'default'=>$options_groups ));

				echo $this->Form->input('Email.id', array('type'=>'select', 'label' => array('text'=>'Emails individuais *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-col-5w-8 alpha"><div class="mws-form-item small">', 'after'=>'</div></div>', 'class'=>'chzn-select', 'multiple'=>true, 'size'=>'10', 'options'=>$emails, 'default'=>$options_emails ));
				
				echo $this->Form->input('template_id', array('type'=>'select', 'label' => array('text'=>'Template *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-col-5w-8 alpha"><div class="mws-form-item small">', 'after'=>'</div></div>', 'class'=>'chzn-select', 'options'=>$templates, 'default'=>$this->Form->value('Newsletter.template_id') ));

				$defaul_date = new DateTime(trim($this->Form->value('Newsletter.date_send')));
				echo $this->Form->input('date_send', array('type'=>'text', 'label' => array('text'=>'Data de envio *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-col-2-8 alpha"><div class="mws-form-item small">', 'after'=>'</div></div>', 'class'=>'mws-textinput mws-dtpicker ', 'value'=>(isset($sessao_formulario['Newsletter']['date_send']) ? $sessao_formulario['Newsletter']['date_send'] : $defaul_date->format('d/m/Y H:i') ) ));

			?>

				<div class="mws-form-row notice">Todos os campos marcados com <span style="color:red">*</span> são de preenchimento obrigatório.</div>

    		</div>
    		<div class="mws-button-row">
    		<?php
    			// echo $this->action == 'edit' ? $this->Form->postLink(__('Desativar esta newsletter'), array('action' => 'disable', $this->Form->value('Newsletter.id')), array('id'=>'del-'.$this->Form->value('Newsletter.id'),'class'=>'mws-button red small fl '), __('Tem certeza que deseja desativar a Newsletter # %s?', $this->Form->value('Newsletter.id'))) : '';

				echo $this->Html->link(__('Ver todas as Newsletters', true), array('action' => 'index'),array('class'=>'mws-button gray small fl'));

				echo $this->action == 'edit' ? $this->Html->link(__('Visualizar', true), array('action' => 'view', $this->Form->value('Newsletter.id')),array('class'=>'mws-button gray small fl')) : '';

				echo $this->action == 'edit' ? '<span class="notice mr20">Criada <strong><time datetime="'. $this->Form->value('Newsletter.created') .'">'. getTimeAgo($this->Form->value('Newsletter.created')) .'</time></strong></span>' : '';

				echo $this->action == 'add' ? '<input type="submit" value="Cadastrar" class="mws-button blue" />' : '<input type="submit" value="Atualizar" class="mws-button blue" />';
    		?>
    		</div>
    </div>    	
</div>
<?php echo $this->Form->end();?>