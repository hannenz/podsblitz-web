<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Episodes Controller
 *
 * @property \App\Model\Table\EpisodesTable $Episodes
 *
 * @method \App\Model\Entity\Episode[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EpisodesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Feeds']
        ];
        $episodes = $this->paginate($this->Episodes);

        $this->set(compact('episodes'));
    }

    /**
     * View method
     *
     * @param string|null $id Episode id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $episode = $this->Episodes->get($id, [
            'contain' => ['Feeds']
        ]);

        $this->set('episode', $episode);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $episode = $this->Episodes->newEntity();
        if ($this->request->is('post')) {
            $episode = $this->Episodes->patchEntity($episode, $this->request->getData());
            if ($this->Episodes->save($episode)) {
                $this->Flash->success(__('The episode has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The episode could not be saved. Please, try again.'));
        }
        $feeds = $this->Episodes->Feeds->find('list', ['limit' => 200]);
        $this->set(compact('episode', 'feeds'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Episode id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $episode = $this->Episodes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $episode = $this->Episodes->patchEntity($episode, $this->request->getData());
            if ($this->Episodes->save($episode)) {
                $this->Flash->success(__('The episode has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The episode could not be saved. Please, try again.'));
        }
        $feeds = $this->Episodes->Feeds->find('list', ['limit' => 200]);
        $this->set(compact('episode', 'feeds'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Episode id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $episode = $this->Episodes->get($id);
        if ($this->Episodes->delete($episode)) {
            $this->Flash->success(__('The episode has been deleted.'));
        } else {
            $this->Flash->error(__('The episode could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }



	public function download($id = null) {
		$episode = $this->Episodes->get($id);
		echo $episode->link;
		die ();
	}
}
