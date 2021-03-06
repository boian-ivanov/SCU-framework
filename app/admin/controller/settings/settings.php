<?php

class ControllerSettingsSettings extends Controller {

    public function index() {
        $this->redirect($this->url->admin . "/settings/settings/view");
    }

    public function edit() {
        $messages = array();

        $model = $this->load->model('settings/settings');

        if(!empty($this->request->post)) {
            if($model->updateSettings('general_settings', $this->validate($this->request->post['data']))) {
                $messages['success'][] = "Settings have been updated.";
            }
        }

        $this->redirect($this->url->admin . "/settings/settings/view", $messages);
    }

    public function view() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $data['header'] = $this->load->controller('common/header/index', 'Settings > Settings');
            $data['footer'] = $this->load->view('common/footer');

            $model = $this->load->model('settings/settings');

            $data['setting_data'] = $model->getSettingData('general_settings'); // add settings key

            $data['form_post_link'] = $this->url->admin . "/settings/settings/edit";

            $data['title'] = "Edit setting";
            return $this->load->view('settings/setting_form', $data);
        } else {
            $this->redirect($this->url->admin);
        }
    }

    private function validate($data) {
        // TODO : secure that data is what is expected, remove unexpected ones and remove empty data
        $required = array('name', 'card1', 'card2', 'card3', 'captcha', 'office', 'social');
        foreach ($data as $key => $value) { // remove unnecessary key value pairs
            if(!in_array($key, $required))
                unset($data[$key]);
        }
        return $data;
    }
}