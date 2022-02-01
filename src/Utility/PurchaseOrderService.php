<?php

namespace App\Utility;

use Cake\Http\Client;
use function Webmozart\Assert\Tests\StaticAnalysis\integer;

class PurchaseOrderService
{
    /**
     * @param array $ids
     */
    public function calculateTotals(array $ids)
    {
        $orders = [];
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                if (intval($id) > 0) {
                    $orders[] = $this->fetchOrderInfo(intval($id));
                }
            }
        }
        return $this->calTotalsByOrders($orders);
    }

    /**
     * @param integer $ids
     */
    private function fetchOrderInfo(int $id)
    {
        $http = new Client();
        $response = $http->get('https://api.cartoncloud.com.au/CartonCloud_Demo/PurchaseOrders/'.$id, ['version' => 5, 'associated' => true], [
            'auth' => ['username' => 'interview-test@cartoncloud.com.au', 'password' => 'test123456']
        ]);
        if ($response->getStatusCode() == 200) {
            $result = $response->getJson();
            if ($result['info'] == 'SUCCESS') {
                return $result['data'];
            }
        }
        return [];
    }

    /**
     * @param array $orders
     */
    private function calTotalsByOrders(array $orders)
    {
        $products = [];
        foreach ($orders as $order) {
            if (isset($order['PurchaseOrderProduct'])) {
                foreach ($order['PurchaseOrderProduct'] as $product) {
                    $products[] = $product;
                }
            }
        }
        $result = [];
        $columnMapping = [
            1 => 'weight',
            2 => 'volume',
            3 => 'weight'
        ];
        foreach ($columnMapping as $productTypeId => $column) {
            $values = [];
            foreach ($products as $product) {
                $quantity = $product['unit_quantity_initial'];
                $columnValue = $product['Product'][$column];
                $values[] = $quantity * $columnValue;
            }
            $result[] = [
                'product_type_id' => $productTypeId,
                'total' => array_sum($values)
            ];
        }
        return $result;
    }
}