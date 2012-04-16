<?php echo $this->Form->create('Template', array('class'=>'mws-form')) ?>
	<div class="mws-panel grid_8 pages">
		<div class="mws-panel-header">
	    	<span class="mws-i-24 i-list"><?php echo $title_for_layout?></span>
	    </div>
	    <div class="mws-panel-body">
			<div class="mws-form-inline">
			<?php
				if($this->action == 'edit') echo $this->Form->hidden('id', array('value'=>$this->Form->value('Template.id')));

				echo $this->Form->input('titulo', array('type'=>'text', 'label' => array('text'=>'Título *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Template']['titulo']) ? $sessao_formulario['Template']['titulo'] : $this->Form->value('Template.titulo') ) ));
				
				echo $this->Form->input('file', array('type'=>'text', 'label' => array('text'=>'Nome do arquivo do template *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Template']['file']) ? $sessao_formulario['Template']['file'] : $this->Form->value('Template.file') ) ));
			?>

			<div class="mws-form-row notice">Todos os campos marcados com <span style="color:red">*</span> são de preenchimento obrigatório.</div>

    		</div>
    		<div class="mws-button-row">
    		<?php
				echo $this->Html->link(__('Ver todos os Templates', true), array('action' => 'index'),array('class'=>'mws-button gray small fl'));

				echo $this->action == 'add' ? '<input type="submit" value="Cadastrar" class="mws-button blue" />' : '<input type="submit" value="Atualizar" class="mws-button blue" />';
    		?>
    		</div>
    </div>    	
</div>
<?php echo $this->Form->end();?>