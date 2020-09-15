<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Ratings Controller
 *
 * @property \App\Model\Table\RatingsTable $Ratings
 *
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RatingsController extends AuctionBaseController
{

    // 初期化処理
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        // 必要なモデルをすべてロード
        $this->loadModel('Biditems');
        $this->loadModel('BuyerStatus');
        $this->loadModel('Ratings');
        // ログインしているユーザー情報をauthuserに設定
        $this->set('authuser', $this->Auth->user());
        // レイアウトをauctionに変更
        $this->viewBuilder()->setLayout('auction');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Biditems'],
        ];
        $ratings = $this->paginate($this->Ratings);

        $this->set(compact('ratings'));
    }

    /**
     * View method
     *
     * @param string|null $id Rating id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rating = $this->Ratings->get($id, [
            'contain' => ['Biditems'],
        ]);

        $this->set('rating', $rating);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $biditems = $this->Biditems->find('all')->toArray(); //出品者
        // 出品者を取得
        for ($j = 0; $j < count($biditems); $j++) {
            if ($biditems[$j]["id"] === intval($_GET["biditem_id"])) {
                $seller = $biditems[$j]["user_id"];
                break;
            } else {
                return $this->redirect(['action' => '../auction']);
            }
        }

        $buyerstatus = $this->BuyerStatus->find('all')->toArray(); //落札者
        // 落札者を取得
        for ($x = 0; $x < count($buyerstatus); $x++) {
            if ($buyerstatus[$x]["biditem_id"] === intval($_GET["biditem_id"])) {
                $buyer_id = $buyerstatus[$x]["buyer_id"];
                break;
            } else {
                return $this->redirect(['action' => '../auction']);
            }
        }
        $ratings = $this->Ratings->find('all')->toArray();
        for ($i = 0; $i < count($ratings); $i++) {
            // 商品が一致し、ログインユーザーの情報がすでにあったリダイレクト
            if (($ratings[$i]["biditem_id"] === intval($_GET["biditem_id"]) && ($seller === $ratings[$i]["target"] || $buyer_id === $ratings[$i]["target"]) && $this->Auth->user('id') === $ratings[$i]["rater"])) {
                return $this->redirect(['action' => '../auction']);
            }
        }
        $rating = $this->Ratings->newEntity();
        if ($this->Auth->user('id') === $seller) {
            // 評価しようとしてるユーザーが出品者だった場合
            if ($this->request->is('post')) {
                $entity = $this->request->getData();
                $entity['target'] = $buyer_id;
                $entity['rater'] = $this->Auth->user('id');
                $entity = $this->Ratings->patchEntity($rating, $entity);
                if ($this->Ratings->save($entity)) {
                    $this->Flash->success(__('評価の保存が成功しました'));
                    return $this->redirect(['action' => '../auction']);
                }
                $this->Flash->error(__('評価の保存に失敗しました。'));
            }
        } elseif ($this->Auth->user('id') === $buyer_id) {
            // 評価しようとしてるユーザーが落札者だった場合
            if ($this->request->is('post')) {
                $entity = $this->request->getData();
                $entity['target'] = $seller;
                $entity['rater'] = $this->Auth->user('id');
                $entity = $this->Ratings->patchEntity($rating, $entity);
                if ($this->Ratings->save($entity)) {
                    $this->Flash->success(__('評価の保存が成功しました'));
                    return $this->redirect(['action' => '../auction']);
                }
                $this->Flash->error(__('評価の保存に失敗しました。'));
            }
        }
        $this->set(compact('rating'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Rating id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rating = $this->Ratings->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rating = $this->Ratings->patchEntity($rating, $this->request->getData());
            if ($this->Ratings->save($rating)) {
                $this->Flash->success(__('The rating has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The rating could not be saved. Please, try again.'));
        }
        $biditems = $this->Ratings->Biditems->find('list', ['limit' => 200]);
        $this->set(compact('rating', 'biditems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Rating id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rating = $this->Ratings->get($id);
        if ($this->Ratings->delete($rating)) {
            $this->Flash->success(__('The rating has been deleted.'));
        } else {
            $this->Flash->error(__('The rating could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
