<?php
class QueuesController extends AppController {

	var $name = 'Queues';

	function index() {
		$this->Queue->recursive = 0;
		$this->set('queues', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('%s inválido.', true), 'Queue'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('queue', $this->Queue->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Queue->create();
			if ($this->Queue->save($this->data)) {
				$this->Session->setFlash(sprintf(__('O %s foi salvo.', true), 'queue'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('O %s não pode ser salvo. Por favor, tente novamente.', true), 'queue'));
			}
		}
		$newsletters = $this->Queue->Newsletter->find('list');
		$emails = $this->Queue->Email->find('list');
		$this->set(compact('newsletters', 'emails'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('%s inválido.', true), 'Queue'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Queue->save($this->data)) {
				$this->Session->setFlash(sprintf(__('O %s foi salvo.', true), 'queue'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('O %s não pode ser salvo. Por favor, tente novamente.', true), 'queue'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Queue->read(null, $id);
		}
		$newsletters = $this->Queue->Newsletter->find('list');
		$emails = $this->Queue->Email->find('list');
		$this->set(compact('newsletters', 'emails'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('ID inválido para %s.', true), 'queue'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Queue->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s excluído.', true), 'Queue'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s não pode ser excluído.', true), 'Queue'));
		$this->redirect(array('action' => 'index'));
	}
}
?>