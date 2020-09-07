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
class RatingsController extends AppController
{
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
        $ratings = $this->Ratings->find('all')->toArray();
        $is_rating = false;
        for ($i = 0; $i < count($ratings); $i++) {
            if ($ratings[$i]["biditem_id"] === intval($_GET["biditem_id"]) && $ratings[$i]["target"] === intval($_GET["target"]) && $ratings[$i]["rater"] === intval($_GET["rater"])) {
                $is_rating = true;
                break;
            }
        }
        // こっから、いつの商品に対して一回判定する まず商品のid、出品者、落札者をだそう
        $this->loadModel('BuyerStatus');
        $this->loadModel('Biditems');
        // 出品者を取得
        $biditems = $this->Biditems->find('all')->toArray();
        for ($j = 0; $j < count($biditems); $j++) {
            if ($biditems[$j]["id"] === intval($_GET["biditem_id"])) {
                $biditems_id = $biditems[$j]["id"];
                $user_id = $biditems[$j]["user_id"];
                break;
            }
        }
        // 落札者を取得
        $buyerstatus = $this->BuyerStatus->find('all')->toArray();
        for ($x = 0; $x < count($buyerstatus); $x++) {
            if ($buyerstatus[$x]["biditem_id"] === intval($_GET["biditem_id"])) {
                $buyerstatus_biditem_id = $buyerstatus[$x]["biditem_id"];
                $buyer_id = $buyerstatus[$x]["buyer_id"];
                break;
            }
        }
        $query_parameter_isError = true;
        if ((intval($_GET["target"]) === $user_id && intval($_GET["rater"]) === $buyer_id) || (intval($_GET["target"]) === $buyer_id && intval($_GET["rater"]) === $user_id)) {
            $query_parameter_isError = false;
        }
        // var_dump($query_parameter_isError);
        // var_dump($_GET);
        // var_dump($user_id);
        // var_dump($buyer_id);
        // // if (($user_id !== intval($_GET["target"]) && $buyer_id === intval($_GET["rater"])) || $user_id === intval($_GET["target"]) && $buyer_id !== intval($_GET["rater"]) || ($user_id !== intval($_GET["target"]) && $buyer_id !== intval($_GET["rater"]))) {
        // //     $query_parameter_isError = true;
        // // }
        // ちょっと違うかも。同じ商品に落札者と出品者が１回ずつやらんと
        // var_dump($buyer_id);
        if ($is_rating === true || $query_parameter_isError === true) {
            return $this->redirect(['action' => '../auction']);
        } else {
            $rating = $this->Ratings->newEntity();
            if ($this->request->is('post')) {
                $rating = $this->Ratings->patchEntity($rating, $this->request->getData());
                if ($this->Ratings->save($rating)) {
                    $this->Flash->success(__('The rating has been saved.'));
                    return $this->redirect(['action' => '../auction']);
                }
                $this->Flash->error(__('The rating could not be saved. Please, try again.'));
            }
            $biditems = $this->Ratings->Biditems->find('list', ['limit' => 200]);
            $this->set(compact('rating', 'biditems'));
        }
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
