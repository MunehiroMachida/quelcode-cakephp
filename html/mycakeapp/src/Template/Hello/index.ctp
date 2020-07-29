<ul>
    <?= $this->Html->nestedList(
        [
            'first line', 'second line',
            'third line' => ['one', 'two', 'three']
        ]
    ) ?>
</ul>