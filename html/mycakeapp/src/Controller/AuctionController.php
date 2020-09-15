<?php

namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event; // added.
use Exception; // added.

class AuctionController extends AuctionBaseController
{
	// デフォルトテーブルを使わない
	public $useTable = false;

	// 初期化処理
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');
		// 必要なモデルをすべてロード
		$this->loadModel('Users');
		$this->loadModel('Biditems');
		$this->loadModel('Bidrequests');
		$this->loadModel('Bidinfo');
		$this->loadModel('Bidmessages');
		$this->loadModel('BuyerStatus');
		$this->loadModel('Ratings');
		// ログインしているユーザー情報をauthuserに設定
		$this->set('authuser', $this->Auth->user());
		// レイアウトをauctionに変更
		$this->viewBuilder()->setLayout('auction');
	}

	// トップページ
	public function index()
	{
		// ページネーションでBiditemsを取得
		$auction = $this->paginate('Biditems', [
			'order' => ['endtime' => 'desc'],
			'limit' => 10
		]);
		$this->set(compact('auction'));
	}

	// 商品情報の表示
	public function view($id = null)
	{
		// $idのBiditemを取得
		$biditem = $this->Biditems->get($id, [
			'contain' => ['Users', 'Bidinfo', 'Bidinfo.Users']
		]);
		// オークション終了時の処理
		if ($biditem->endtime < new \DateTime('now') and $biditem->finished == 0) {
			// finishedを1に変更して保存
			$biditem->finished = 1;
			$this->Biditems->save($biditem);
			// Bidinfoを作成する
			$bidinfo = $this->Bidinfo->newEntity();
			// Bidinfoのbiditem_idに$idを設定
			$bidinfo->biditem_id = $id;
			// 最高金額のBidrequestを検索
			$bidrequest = $this->Bidrequests->find('all', [
				'conditions' => ['biditem_id' => $id],
				'contain' => ['Users'],
				'order' => ['price' => 'desc']
			])->first();
			// Bidrequestが得られた時の処理
			if (!empty($bidrequest)) {
				// Bidinfoの各種プロパティを設定して保存する
				$bidinfo->user_id = $bidrequest->user->id;
				$bidinfo->user = $bidrequest->user;
				$bidinfo->price = $bidrequest->price;
				$this->Bidinfo->save($bidinfo);
			}
			// Biditemのbidinfoに$bidinfoを設定
			$biditem->bidinfo = $bidinfo;
		}
		// Bidrequestsからbiditem_idが$idのものを取得
		$bidrequests = $this->Bidrequests->find('all', [
			'conditions' => ['biditem_id' => $id],
			'contain' => ['Users'],
			'order' => ['price' => 'desc']
		])->toArray();
		// オブジェクト類をテンプレート用に設定
		$this->set(compact('biditem', 'bidrequests'));
	}

	// 出品する処理
	public function add()
	{
		// Biditemインスタンスを用意
		$biditem = $this->Biditems->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $biditemにフォームの送信内容を反映
			$biditem = $this->Biditems->patchEntity($biditem, $this->request->getData());
			// $biditemを保存する
			if ($this->Biditems->save($biditem)) {
				// 成功時のメッセージ
				$this->Flash->success(__('保存しました。'));
				// トップページ（index）に移動
				return $this->redirect(['action' => 'index']);
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
		}
		// 値を保管
		$this->set(compact('biditem'));
	}

	// 入札の処理
	public function bid($biditem_id = null)
	{
		// 入札用のBidrequestインスタンスを用意
		$bidrequest = $this->Bidrequests->newEntity();
		// $bidrequestにbiditem_idとuser_idを設定
		$bidrequest->biditem_id = $biditem_id;
		$bidrequest->user_id = $this->Auth->user('id');
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $bidrequestに送信フォームの内容を反映する
			$bidrequest = $this->Bidrequests->patchEntity($bidrequest, $this->request->getData());
			// Bidrequestを保存
			if ($this->Bidrequests->save($bidrequest)) {
				// 成功時のメッセージ
				$this->Flash->success(__('入札を送信しました。'));
				// トップページにリダイレクト
				return $this->redirect(['action' => 'view', $biditem_id]);
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('入札に失敗しました。もう一度入力下さい。'));
		}
		// $biditem_idの$biditemを取得する
		$biditem = $this->Biditems->get($biditem_id);
		$this->set(compact('bidrequest', 'biditem'));
	}

	// 落札者とのメッセージ
	public function msg($bidinfo_id = null)
	{
		// Bidmessageを新たに用意
		$bidmsg = $this->Bidmessages->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 送信されたフォームで$bidmsgを更新
			$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $this->request->getData());
			// Bidmessageを保存
			if ($this->Bidmessages->save($bidmsg)) {
				$this->Flash->success(__('保存しました。'));
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		try { // $bidinfo_idからBidinfoを取得する
			$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems']]);
		} catch (Exception $e) {
			$bidinfo = null;
		}
		// Bidmessageをbidinfo_idとuser_idで検索
		$bidmsgs = $this->Bidmessages->find('all', [
			'conditions' => ['bidinfo_id' => $bidinfo_id],
			'contain' => ['Users'],
			'order' => ['created' => 'desc']
		]);
		$this->set(compact('bidmsgs', 'bidinfo', 'bidmsg'));
	}

	// 落札情報の表示
	public function home()
	{
		// 自分が落札したBidinfoをページネーションで取得
		$bidinfo = $this->paginate('Bidinfo', [
			'conditions' => ['Bidinfo.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Biditems'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('bidinfo'));
		$buyer_status = $this->BuyerStatus->find('all')->toArray(); //出品者
		if ($this->request->is('post')) {
			$information = false;
			for ($i = 0; $i < count($buyer_status); $i++) {
				if ($buyer_status[$i]["id"] === intval($this->request->getData(['buyer'])) && $buyer_status[$i]["buyer_id"] === $this->Auth->user('id')) {
					$buyer_status_id = $buyer_status[$i]["id"];
					$is_received = $buyer_status[$i]['is_received'];
					$information = true;
					break;
				}
			}
			if ($is_received === false && $information === true) {
				$entity = $this->BuyerStatus->get($buyer_status_id);
				$this->BuyerStatus->patchEntity($entity, ['is_received' => true]);
				$this->BuyerStatus->save($entity);
				if ($this->BuyerStatus->save($entity)) {
					$bidinfo_array = $this->Bidinfo->find('all')->toArray();
					for ($i = 0; $i <= count($bidinfo_array); $i++) {
						if ($bidinfo_array[$i]['biditem_id'] === $entity["biditem_id"]) {
							$bidinfo_id = $bidinfo_array[$i]['id']; //bidinfoのid
							break;
						}
					}
					$bidmsg = $this->Bidmessages->newEntity();
					$bidmsgs = ['bidinfo_id' => $bidinfo_id, 'user_id' => $entity["buyer_id"], 'message' => '落札者が商品を受け取りました'];
					$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $bidmsgs);
					// Bidmessageに発送完了を保存
					$this->Bidmessages->save($bidmsg);
					return $this->redirect(['action' => 'msg', $bidinfo_array[$i]['id']]);
				}
			}
		} else {
			$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
		}
		$ratings = $this->Ratings->find('all')->toArray();
		$this->set(compact('buyer_status', 'ratings'));
	}

	// 出品情報の表示
	public function home2()
	{
		// 自分が出品したBiditemをページネーションで取得
		$biditems = $this->paginate('Biditems', [
			'conditions' => ['Biditems.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Bidinfo'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('biditems'));

		$biditems = $this->Biditems->find('all')->toArray(); //出品者
		$buyer_status = $this->BuyerStatus->find('all')->toArray(); //落札者の情報
		// 出品者を取得
		if ($this->request->is('post')) {
			for ($i = 0; $i < count($biditems); $i++) {
				if ($biditems[$i]["id"] === intval($this->request->getData(['item'])) && $biditems[$i]["user_id"] === $this->Auth->user('id')) {
					$biditems_id = $biditems[$i]['id'];
					$biditems_is_sent = $biditems[$i]['is_sent'];
					break;
				}
			}
			// 落札者の情報が入力されているか確認
			$information = false;
			for ($j = 0; $j < count($buyer_status); $j++) {
				if ($buyer_status[$j]['biditem_id'] === $biditems_id) {
					$information = true;
					break;
				}
			}
			if ($information === true && $biditems_is_sent === false) {
				$entity = $this->Biditems->get($biditems_id);
				$this->Biditems->patchEntity($entity, ['is_sent' => true]);
				$this->Biditems->save($entity);
				if ($this->Biditems->save($entity)) {
					$bidinfo_array = $this->Bidinfo->find('all')->toArray();
					for ($i = 0; $i <= count($bidinfo_array); $i++) {
						if ($bidinfo_array[$i]['biditem_id'] === $entity["id"]) {
							$bidinfo_id = $bidinfo_array[$i]['id']; //bidinfoのid
							break;
						}
					}
					$bidmsg = $this->Bidmessages->newEntity();
					$bidmsgs = ['bidinfo_id' => $bidinfo_id, 'user_id' => $entity["user_id"], 'message' => '出品者から商品が発送されました。'];
					$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $bidmsgs);
					// Bidmessageに発送完了を保存
					$this->Bidmessages->save($bidmsg);
					return $this->redirect(['action' => 'msg', $bidinfo_array[$i]['id']]);
				}
			}
		} else {
			$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
		}
		$ratings = $this->Ratings->find('all')->toArray();
		$this->set(compact('ratings', 'buyer_status'));
	}
	// 落札者情報
	public function buyerinfo()
	{
		// 落札者とログインユーザーが一緒か、商品idが落札の商品idと一緒か==================================
		// 自分が落札したBidinfoをページネーションで取得
		$bidinfos = $this->paginate('Bidinfo', [
			'conditions' => ['Bidinfo.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Biditems']
		])->toArray();

		$bidinfo = $this->Bidinfo->find('all')->toArray();
		for ($j = 0; $j < count($bidinfo); $j++) {
			//商品idが落札されているかチェック
			if ($bidinfo[$j]['biditem_id'] === intval($_GET["biditem_id"])) {
				$bidinfo_biditem_id = $bidinfo[$j]['biditem_id'];
				break;
			} else {
				return $this->redirect(['action' => 'index']);
			}
		}
		// getの商品idで、すでに落札者情報が入力されていたら
		$buyer_status = $this->BuyerStatus->find('all')->toArray();
		for ($i = 0; $i < count($buyer_status); $i++) {
			if ($buyer_status[$i]['biditem_id'] === $bidinfo_biditem_id) {
				return $this->redirect(['action' => 'index']);
			}
		}
		// ========================================================================================

		// >BuyerStatusインスタンスを用意
		$buyerstatus = $this->BuyerStatus->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			if ($bidinfo_biditem_id === intval($_POST["biditem_id"])) { //ここでpostされた値が書き換えられていないかチェック
				// $buyerstatusにフォームの送信内容を反映
				$buyerstatus = $this->BuyerStatus->patchEntity($buyerstatus, $this->request->getData());
				// $buyerstatusを保存する
				if ($this->BuyerStatus->save($buyerstatus)) {
					// 成功時のメッセージ
					$this->Flash->success(__('保存しました。'));
					// トップページ（index）に移動
					return $this->redirect(['action' => 'home']);
				}
				// 失敗時のメッセージ
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		// 値を保管
		$this->set(compact('buyerstatus'));
	}

	public function rating()
	{
		// 自分が出品したRatingsをページネーションで取得
		$ratings = $this->paginate('Ratings', [
			'conditions' => ['Ratings.target' => $this->Auth->user('id')],
			'contain' => ['Users'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();

		$sum = 0;
		if (!empty($ratings)) {
			for ($i = 0; $i < count($ratings); $i++) {
				if (is_int($ratings[$i]['score'])) {
					$sum += $ratings[$i]['score'];
				}
			}
			$avg = $sum / count($ratings);
			$avg = round($avg, 1);
		} else {
			$avg = 0;
		}

		$this->set(compact('ratings', 'avg'));
	}
}
