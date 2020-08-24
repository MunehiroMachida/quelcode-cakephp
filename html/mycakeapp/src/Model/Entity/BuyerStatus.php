<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BuyerStatus Entity
 *
 * @property int $id
 * @property int $buyer_id
 * @property string $name
 * @property string $phone_number
 * @property string $address
 * @property bool $is_received
 *
 * @property \App\Model\Entity\Buyer $buyer
 */
class BuyerStatus extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'buyer_id' => true,
        'name' => true,
        'phone_number' => true,
        'address' => true,
        'is_received' => true,
        'buyer' => true,
    ];
}
