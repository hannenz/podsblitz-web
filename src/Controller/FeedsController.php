<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Feeds Controller
 *
 * @property \App\Model\Table\FeedsTable $Feeds
 *
 * @method \App\Model\Entity\Feed[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FeedsController extends AppController {


	public function isAuthorized($user) {
		$action = $this->request->getParam('action');
		if (in_array($action, ['index', 'subscribe', 'import'])) {
			return true;
		}

		$id = $this->request->getParam('pass.0');
		if (!$id) {
			return false;
		}

		$feed = $this->Feeds->findById($id)->first();
		return $feed->user_id === $user['id'];
	}


	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|void
	 */
	public function index() {
		// $this->paginate = [
		//     'contain' => ['Users']
		// ];
		// $feeds = $this->paginate($this->Feeds);
		$userId = $this->Auth->user('id');
		$feeds = $this->Feeds->findByUserId($userId);

		$this->set(compact('feeds'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Feed id.
	 * @return \Cake\Http\Response|void
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null) {
		$feed = $this->Feeds->get($id, [
			'contain' => ['Episodes', 'Users']
		]);

		// $feed->fetch($feed->url);

		$this->set('feed', $feed);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
	 */
	public function subscribe () {

		$feed = $this->Feeds->newEntity();
		$feed->user_id = $this->Auth->user('id');

		if ($this->request->is('post')) {


			$feed = $this->Feeds->patchEntity($feed, $this->request->getData());
			if ($this->Feeds->save($feed)) {
				$this->Flash->success(__('The feed has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The feed could not be saved. Please, try again.'));
		}
		$this->set(compact('feed'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Feed id.
	 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$feed = $this->Feeds->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$feed = $this->Feeds->patchEntity($feed, $this->request->getData());
			if ($this->Feeds->save($feed)) {
				$this->Flash->success(__('The feed has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The feed could not be saved. Please, try again.'));
		}
		$users = $this->Feeds->Users->find('list', ['limit' => 200]);
		$this->set(compact('feed', 'users'));
	}



	public function syncEpisodes($id = null) {
		$feed = $this->Feeds->get($id);
		$feed->syncEpisodes();
		$this->redirect(['action' => 'view', $id]);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Feed id.
	 * @return \Cake\Http\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$feed = $this->Feeds->get($id);
		if ($this->Feeds->delete($feed)) {
			$this->Flash->success(__('The feed has been deleted.'));
		} else {
			$this->Flash->error(__('The feed could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}



	/**
	 * Import from OPML (and maybe other sources?!?)
	 *
	 */
	public function import() {

		if ($this->request->is('post')) {
			$feedsTable = TableRegistry::get('Feeds');
			if (is_uploaded_file($this->request->data['opmlfile']['tmp_name'])) {
				$feedsTable->import($this->request->data['opmlfile']['tmp_name'], $this->Auth->user('id'));
			}
		}
	}
}
