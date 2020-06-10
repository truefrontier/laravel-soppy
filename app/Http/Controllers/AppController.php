<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller {

    public function __construct() {
        $this->view = 'app';
        $this->data = [];
    }

    public function maybeJsonResponse(Request $request) {
        return $request->wantsJson() ? $this->data : view($this->view, ['data' => $this->data]);
    }

    public function welcome(Request $request) {
        return $this->maybeJsonResponse($request);
    }
}
