<?php echo $this->Form->create('Newslettersemail', array('class'=>'mws-form', 'id'=>'form_contentpost')) ?>
	<div class="mws-panel grid_8 pages">
		<div class="mws-panel-header">
	    	<span class="mws-i-24 i-list">Adicionar novo email</span>
	    </div>
	    <div class="mws-panel-body">
			<div class="mws-form-inline">
			<?php
				echo $this->Form->input('nome', array('type'=>'text', 'label' => array('text'=>'Nome *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Newslettersemail']['nome']) ? $sessao_formulario['Newslettersemail']['nome'] : $this->Form->value('Newslettersemail.nome') ) ));
				
				echo $this->Form->input('email', array('type'=>'text', 'label' => array('text'=>'Email *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Newslettersemail']['email']) ? $sessao_formulario['Newslettersemail']['email'] : $this->Form->value('Newslettersemail.email') ) ));

				echo $this->Form->input('', array('type'=>'select', 'label' => array('text'=>'Associe este email a um vários grupos *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-col-5w-8 alpha"><div class="mws-form-item small">', 'after'=>'</div></div>', 'class'=>'chzn-select', 'multiple'=>true, 'size'=>'10', 'options'=>$newslettersgroups, 'default'=>(isset($sessao_formulario['Newslettersgroup']['id']) ? $sessao_formulario['Newslettersgroup']['id'] : explode(',',$this->Form->value('Newslettersgroup.id')) ) ));
			?>

			<div class="mws-form-row notice">Todos os campos marcados com <span style="color:red">*</span> são de preenchimento obrigatório.</div>

    		</div>
    		<div class="mws-button-row">
    		<?php
				echo $this->Html->link(__('Ver todos os Emails', true), array('action' => 'index'),array('class'=>'mws-button gray small fl'));

				echo '<input type="submit" value="Cadastrar" class="mws-button blue" />';
    		?>
    		</div>
    </div>    	
</div>
<?php echo $this->Form->end();?>