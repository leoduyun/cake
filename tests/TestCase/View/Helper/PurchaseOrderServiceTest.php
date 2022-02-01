<?php

namespace App\Test\TestCase\View\Helper;

use App\Utility\PurchaseOrderService;
use Cake\TestSuite\TestCase;

class PurchaseOrderServiceTest extends TestCase
{
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PurchaseOrderService();
    }

    public function testCalculateTotals(): void
    {
        $result = $this->service->calculateTotals([2344, 2345, 2346]);
        $case = [
            ['product_type_id' => 1, 'total' => 94.1],
            ['product_type_id' => 2, 'total' => 34.3],
            ['product_type_id' => 3, 'total' => 94.1],
        ];
        $this->assertEquals($case, $result);

        $result = $this->service->calculateTotals([]);
        $case = [
            ['product_type_id' => 1, 'total' => 0],
            ['product_type_id' => 2, 'total' => 0],
            ['product_type_id' => 3, 'total' => 0],
        ];
        $this->assertEquals($case, $result);

        $result = $this->service->calculateTotals([2344]);
        $case = [
            ['product_type_id' => 1, 'total' => 45.5],
            ['product_type_id' => 2, 'total' => 16.0],
            ['product_type_id' => 3, 'total' => 45.5],
        ];
        $this->assertEquals($case, $result);
    }
}