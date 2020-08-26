<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * BuyerStatus Controller
 *
 * @property \App\Model\Table\BuyerStatusTable $BuyerStatus
 *
 * @method \App\Model\Entity\BuyerStatus[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BuyerStatusController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['buyerStatus'],
        ];
        $buyerStatus = $this->paginate($this->BuyerStatus);

        $this->set(compact('buyerStatus'));
    }

    /**
     * View method
     *
     * @param string|null $id Buyer Status id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $buyerStatus = $this->BuyerStatus->get($id, [
            'contain' => ['Buyers'],
        ]);

        $this->set('buyerStatus', $buyerStatus);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $buyerStatus = $this->BuyerStatus->newEntity();
        if ($this->request->is('post')) {
            $buyerStatus = $this->BuyerStatus->patchEntity($buyerStatus, $this->request->getData());
            if ($this->BuyerStatus->save($buyerStatus)) {
                $this->Flash->success(__('The buyer status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The buyer status could not be saved. Please, try again.'));
        }
        $buyers = $this->BuyerStatus->Buyers->find('list', ['limit' => 200]);
        $this->set(compact('buyerStatus', 'buyers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Buyer Status id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $buyerStatus = $this->BuyerStatus->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $buyerStatus = $this->BuyerStatus->patchEntity($buyerStatus, $this->request->getData());
            if ($this->BuyerStatus->save($buyerStatus)) {
                $this->Flash->success(__('The buyer status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The buyer status could not be saved. Please, try again.'));
        }
        $buyers = $this->BuyerStatus->Buyers->find('list', ['limit' => 200]);
        $this->set(compact('buyerStatus', 'buyers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Buyer Status id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $buyerStatus = $this->BuyerStatus->get($id);
        if ($this->BuyerStatus->delete($buyerStatus)) {
            $this->Flash->success(__('The buyer status has been deleted.'));
        } else {
            $this->Flash->error(__('The buyer status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
