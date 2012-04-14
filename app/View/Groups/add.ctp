<?php echo $this->Form->create('Group', array('class'=>'mws-form', 'id'=>'form_contentpost')) ?>
	<div class="mws-panel grid_8 pages">
		<div class="mws-panel-header">
	    	<span class="mws-i-24 i-list"><?php echo $title_for_layout?></span>
	    </div>
	    <div class="mws-panel-body">
			<div class="mws-form-inline">
			<?php
				if($this->action == 'edit') echo $this->Form->hidden('id', array('value'=>$this->Form->value('Group.id')));

				echo $this->Form->input('nome', array('type'=>'text', 'label' => array('text'=>'Nome *'), 'div'=>array('class'=>'mws-form-row'), 'between'=>'<div class="mws-form-item small">', 'after'=>'</div>', 'class'=>'mws-textinput', 'value'=>(isset($sessao_formulario['Group']['nome']) ? $sessao_formulario['Group']['nome'] : $this->Form->value('Group.nome') ) ));
			?>

			<div class="mws-form-row notice">Todos os campos marcados com <span style="color:red">*</span> são de preenchimento obrigatório.</div>

    		</div>
    		<div class="mws-button-row">
    		<?php
				echo $this->Html->link(__('Ver todos os Grupos', true), array('action' => 'index'),array('class'=>'mws-button gray small fl'));

				echo $this->action == 'add' ? '<input type="submit" value="Cadastrar" class="mws-button blue" />' : '<input type="submit" value="Atualizar" class="mws-button blue" />';
    		?>
    		</div>
    </div>    	
</div>
<?php echo $this->Form->end();?>