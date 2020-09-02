<h2><?= $authuser['username'] ?> の評価一覧</h2>
<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th scope="col"><?= __('評価者') ?></th>
            <th class="main" scope="col"><?= $this->Paginator->sort('評価ポイント') ?></th>
            <th scope="col"><?= __('コメント') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ratings as $rating) : ?>
            <tr>
                <td><?= h($rating->user->username) ?></td>
                <td><?= h($rating->score) ?></td>
                <td><?= h($rating->comment) ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
</div>
<h6><?= $this->Html->link(__('<< 落札情報に戻る'), ['action' => 'home']) ?></h6>