<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller {

    public function __construct() {
        $this->view = 'app';
        $this->data = [];
    }

    public function maybeJsonResponse(Request $request, $data = []) {
        $this->setData($request, $data);
        return $request->wantsJson() ? $this->data : view($this->view, ['data' => $this->data]);
    }

    public function welcome(Request $request) {
        return $this->maybeJsonResponse($request);
    }

    protected function setData(Request $request, $data = []) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->data[$key] = $value;
            }
        }

        // Here you can set data that goes with every request
    }
}
