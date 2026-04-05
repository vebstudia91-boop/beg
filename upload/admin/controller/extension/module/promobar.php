<?php
/**
 * Promo Bar Module for OpenCart
 * Admin Controller
 */

class ControllerExtensionModulePromoBar extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/promobar');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_promobar', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/promobar', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/promobar', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        // Status
        if (isset($this->request->post['module_promobar_status'])) {
            $data['module_promobar_status'] = $this->request->post['module_promobar_status'];
        } else {
            $data['module_promobar_status'] = $this->config->get('module_promobar_status');
        }

        // Text content
        if (isset($this->request->post['module_promobar_text'])) {
            $data['module_promobar_text'] = $this->request->post['module_promobar_text'];
        } else {
            $data['module_promobar_text'] = $this->config->get('module_promobar_text');
        }

        // Link URL
        if (isset($this->request->post['module_promobar_link'])) {
            $data['module_promobar_link'] = $this->request->post['module_promobar_link'];
        } else {
            $data['module_promobar_link'] = $this->config->get('module_promobar_link');
        }

        // Make entire bar clickable
        if (isset($this->request->post['module_promobar_clickable'])) {
            $data['module_promobar_clickable'] = $this->request->post['module_promobar_clickable'];
        } else {
            $data['module_promobar_clickable'] = $this->config->get('module_promobar_clickable');
        }

        // Background color
        if (isset($this->request->post['module_promobar_bg_color'])) {
            $data['module_promobar_bg_color'] = $this->request->post['module_promobar_bg_color'];
        } else {
            $data['module_promobar_bg_color'] = $this->config->get('module_promobar_bg_color');
        }

        // Text color
        if (isset($this->request->post['module_promobar_text_color'])) {
            $data['module_promobar_text_color'] = $this->request->post['module_promobar_text_color'];
        } else {
            $data['module_promobar_text_color'] = $this->config->get('module_promobar_text_color');
        }

        // Scroll speed
        if (isset($this->request->post['module_promobar_speed'])) {
            $data['module_promobar_speed'] = $this->request->post['module_promobar_speed'];
        } else {
            $data['module_promobar_speed'] = $this->config->get('module_promobar_speed');
        }

        // Date start
        if (isset($this->request->post['module_promobar_date_start'])) {
            $data['module_promobar_date_start'] = $this->request->post['module_promobar_date_start'];
        } else {
            $data['module_promobar_date_start'] = $this->config->get('module_promobar_date_start');
        }

        // Date end
        if (isset($this->request->post['module_promobar_date_end'])) {
            $data['module_promobar_date_end'] = $this->request->post['module_promobar_date_end'];
        } else {
            $data['module_promobar_date_end'] = $this->config->get('module_promobar_date_end');
        }

        // Display pages
        if (isset($this->request->post['module_promobar_display_pages'])) {
            $data['module_promobar_display_pages'] = $this->request->post['module_promobar_display_pages'];
        } else {
            $data['module_promobar_display_pages'] = $this->config->get('module_promobar_display_pages');
        }

        // Duplicate text for seamless loop
        if (isset($this->request->post['module_promobar_duplicate'])) {
            $data['module_promobar_duplicate'] = $this->request->post['module_promobar_duplicate'];
        } else {
            $data['module_promobar_duplicate'] = $this->config->get('module_promobar_duplicate');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/promobar', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/promobar')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install() {
        $this->load->model('setting/setting');
        
        // Set default settings on first install
        $existing = $this->model_setting_setting->getSetting('module_promobar');
        if (empty($existing)) {
            $default_settings = array(
                'module_promobar_status' => 1,
                'module_promobar_text' => '🔥 Акция! Скидка 15% на все букеты! 🔥',
                'module_promobar_link' => '',
                'module_promobar_clickable' => 1,
                'module_promobar_bg_color' => '#e74c3c',
                'module_promobar_text_color' => '#ffffff',
                'module_promobar_speed' => 30,
                'module_promobar_date_start' => '',
                'module_promobar_date_end' => '',
                'module_promobar_display_pages' => 'all',
                'module_promobar_duplicate' => 1
            );
            $this->model_setting_setting->editSetting('module_promobar', $default_settings);
        }
    }
    
    public function uninstall() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('module_promobar');
    }
}
