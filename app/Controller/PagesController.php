<?php
App::uses('AppController', 'Controller');
/**
 * Pages Controller
 *
 * @property Page $Page
 */
class PagesController extends AppController {


/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}


		// Enable CacheView
		$this->helpers[] = 'Cache';
		$this->cacheAction = '10 minutes';

		$this->loadModel('Email');
		$this->loadModel('Group');
		$this->loadModel('Newsletter');
		$this->loadModel('Log');

		$countItens = array();
		$countItens['Email'] = $this->Email->find('count',array('cache' => 'EmailCount', 'cacheConfig' => 'long'));
		$countItens['Group'] = $this->Group->find('count',array('cache' => 'GroupCount'));
		$countItens['Newsletter'] = $this->Newsletter->find('count',array('cache' => 'NewsletterCount'));
		$countItens['LogInProgress'] = $this->Log->find('count',array('conditions'=>array("Log.end_sending"=>null),'cache' => 'LogInProgressCount'));
		$countItens['LogFinished'] = $this->Log->find('count',array('conditions'=>array( 'NOT'=>array("Log.end_sending"=>null) ),'cache' => 'LogFinishedCount'));



		$this->set(compact('page', 'subpage', 'title_for_layout','countItens'));
		$this->render(implode('/', $path));
	}

}//end controller