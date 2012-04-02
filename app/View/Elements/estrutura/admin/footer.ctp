<div id="footer">
    <div id="nav-footer-primario">
        <?php
            if(isset($PaginasInstitucionais)){
                $countPages = 0;
                foreach($PaginasInstitucionais as $slug=>$titulo){
                    echo html_entity_decode($this->Html->link( $this->Html->tag('em',$titulo), array('controller'=>'pages','action'=>'view',$slug), array('class'=> ($countPages==0 ? 'first' : 'item'.$countPages) ) ));
                    $countPages++;
                }
            }
            /*echo $this->Html->link('Quem somos', '#' );
            echo $this->Html->link('Economia Social', '#' );
            echo $this->Html->link('Termo de adesão', '#' );
            echo $this->Html->link('Política de privacidade', '#' );
            echo $this->Html->link('Contato', '#', array('class'=>'no-border') );*/
        ?>
        <span>MANTENEDORA:</span>
	 </div><!-- end #nav-footer-programas -->

	<div id="nav-footer-secundario">
    <?php
        
        /*echo $this->Html->link('Política de privacidade', '/pages/politica-de-privacidade' );
        echo $this->Html->link('Termos de adesão', '/pages/termos-de-adesao' );*/
        echo $this->Html->link('Sitemap', '/pages/sitemap' );
        echo $this->Html->link('Contato', '/contato' );
    ?>

    	 <p>&copy; 2012 BusinessCenter - Central de negócios, All Rights Reserved.</p>
	 </div><!-- end #nav-footer-servicos -->

	 <div id="footer-contact">
	 	Fraud &amp; Corruption Hotline<br />
        00 55 61 4141-5098
	 </div><!-- end #footer-contact -->

     <?php echo html_entity_decode($this->Html->link($this->Html->image('mantenedora.gif',array('id'=>'mantenedora')), 'http://aipdes.org', array('target'=>'_blank'))); ?>


     <div class="fix">&nbsp;</div>
</div> <!-- end #footer -->
<div class="fix">&nbsp;</div>