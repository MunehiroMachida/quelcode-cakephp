<?php
use Migrations\AbstractMigration;

class CreateBuyerStatus extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('buyer_status');
        $table->addColumn('buyer_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        $table->addColumn('phone_number', 'string', [
            'default' => null,
            'limit' => 13,
            'null' => false,
        ]);
        $table->addColumn('address', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        $table->addColumn('is_received', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
