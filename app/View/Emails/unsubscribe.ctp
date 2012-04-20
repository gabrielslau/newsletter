<?php
	if(isset($mensagem)):
		echo '<h1>'.$mensagem.'</h1>'; 
		echo '<div class="mws-login-lock">'.$this->Html->image('/css/admin/icons/24/alert-2.png').'</div>';
	else:
		echo '<h1>Cancelar recebimento de newsletter</h1>';
		echo '<div class="mws-login-lock">'.$this->Html->image('/css/admin/icons/24/mail-2.png').'</div>';

		echo '<div id="mws-login-form">';
			echo $this->Form->create('Email' , array('url' => array('controller' => 'emails','action' =>'unsubscribe'), 'id'=>'form-login', 'class'=>'mws-form'));

			echo $this->Form->input('email', array( 'label' => false, 'div'=>array('class'=>'mws-form-row'), 'placeholder'=>'Email', 'class'=>'mws-login-username mws-textinput required','before'=>'<div class="mws-form-item large">','after'=>'</div>' ));

			$options = array(
				'label' => 'Cancelar Inscrição',
				'class'=>'mws-button blue mws-login-button',
				'div' => array(
					'class' => 'mws-form-row',
				)
			);
			echo $this->Form->end($options);
		echo '</div>';
		echo '<div class="fix"></div>';

	endif;
?>