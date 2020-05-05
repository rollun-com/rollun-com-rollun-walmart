<?php
declare(strict_types=1);

namespace rollun\walmart\Marketplace;

/**
 * Class Inventory
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Inventory extends Base
{
    /**
     * @param string $sku
     * @param string $shipNode
     *
     * @return array
     * @throws \Exception
     */
    public function getInventory(string $sku = '', string $shipNode = ''): array
    {
        return $this->request("inventory?shipNode=$shipNode&sku=$sku");
    }

    /**
     * @param array $inventory
     *
     * @return array
     * @throws \Exception
     */
    public function updateInventory(array $inventory): array
    {
        return $this->request("inventory?sku={$inventory['sku']}", 'PUT', $inventory);
    }
}
