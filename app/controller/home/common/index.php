<?php

class ControllerCommonIndex extends Controller{

    public function index() {
        /*$model = $this->load->model('common/index');

        $model->index();*/
        $data['hello'] = "Hellow worlds";
        $data['data'] = 'diz iz views, iz last mvc step. mi iz hepi';

        return $this->load->view('common/index', $data);
    }
}