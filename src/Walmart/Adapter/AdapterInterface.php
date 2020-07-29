<?php
declare(strict_types=1);

namespace rollun\Walmart\Adapter;

/**
 * Class AdapterInterface
 *
 * @author r.ratsun <r.ratsun@gmail.com>
 */
interface AdapterInterface
{
    /**
     * @return array
     */
    public function getActiveItems();

    /**
     * @return array
     */
    public function getAllItems();

    /**
     * @return array
     */
    public function getLastUpdatedItems();

    /**
     * @param array $items
     *
     * @return array
     */
    public function endItems($items);

    /**
     * @param array $items
     *
     * @return mixed
     */
    public function createItems($items);

    /**
     * @param array $items
     *
     * @return mixed
     */
    public function updateItems($items);
}
