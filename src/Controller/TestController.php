<?php

namespace App\Controller;

use App\Utility\PurchaseOrderService;
use Cake\Http\Response;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class TestController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');

        // hard coded Basic Auth
        $username = env('PHP_AUTH_USER');
        $pass = env('PHP_AUTH_PW');
        if (empty($username) || empty($pass)) {
            throw new AccessDeniedException();
        }
        if ($username !== 'demo' || $pass !== 'pwd1234') {
            throw new AccessDeniedException();
        }
    }

    public function add()
    {
        $this->request->allowMethod(['post']);
        $jsonData = $this->request->input('json_decode');
        $service = new PurchaseOrderService();
        $result = $service->calculateTotals(isset($jsonData->purchase_order_ids) ? $jsonData->purchase_order_ids : []);
        $response = new Response();
        $response = $response->withType('application/json')
            ->withStringBody(json_encode(['result' => $result]));
        return $response;
    }

    public function index()
    {
        return new Response();
    }

    public function view($id)
    {
        return new Response();
    }

    public function edit($id)
    {
        return new Response();
    }

    public function delete($id)
    {
        return new Response();
    }
}