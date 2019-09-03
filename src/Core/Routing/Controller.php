<?php

namespace Ximdex\Core\Routing;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $forceJson = false;

    public function response($message, $data = null, $statusCode = 200)
    {
        $statusCode = $statusCode != 0 ? $statusCode : 500;
        $result = $statusCode < 300 ? 'data' : 'error';
        $response = response();
        if (Request::isJson() || $this->forceJson) {
            $response = $response->json([
                'message' => $message,
                $result => $data,
            ], $statusCode);
        } elseif ($result === 'data') {
            $data = is_null($data) ? [] : $data;
            $response = $response->view($message, $data, $statusCode);
        } else {
            abort($statusCode, $message);
        }
        return $response;
    }

    public function redirect(string $name, ?array $params = [])
    {
        return redirect()->route($name, $params ?? []);
    }
}
