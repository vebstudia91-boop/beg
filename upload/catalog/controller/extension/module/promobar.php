<?php
/**
 * Promo Bar Module for OpenCart
 * Catalog Controller - renders the promo bar on frontend
 */

class ControllerExtensionModulePromoBar extends Controller {
    public function index() {
        // Check if module is enabled
        $status = $this->config->get('module_promobar_status');
        if (!$status) {
            return '';
        }

        // Check date range
        $date_start = $this->config->get('module_promobar_date_start');
        $date_end = $this->config->get('module_promobar_date_end');
        
        if ($date_start || $date_end) {
            $current_date = date('Y-m-d');
            
            if ($date_start && $current_date < $date_start) {
                return '';
            }
            
            if ($date_end && $current_date > $date_end) {
                return '';
            }
        }

        // Check display pages
        $display_pages = $this->config->get('module_promobar_display_pages');
        $route = isset($this->request->get['route']) ? $this->request->get['route'] : 'common/home';
        
        $show_on_page = false;
        
        switch ($display_pages) {
            case 'all':
                $show_on_page = true;
                break;
            case 'home':
                if ($route == 'common/home') {
                    $show_on_page = true;
                }
                break;
            case 'category':
                if ($route == 'common/home' || strpos($route, 'product/category') !== false) {
                    $show_on_page = true;
                }
                break;
            case 'product':
                if ($route == 'common/home' || strpos($route, 'product/product') !== false) {
                    $show_on_page = true;
                }
                break;
        }
        
        if (!$show_on_page) {
            return '';
        }

        // Get settings
        $data['text'] = html_entity_decode($this->config->get('module_promobar_text'), ENT_QUOTES, 'UTF-8');
        $data['link'] = $this->config->get('module_promobar_link');
        $data['clickable'] = $this->config->get('module_promobar_clickable');
        $data['bg_color'] = $this->config->get('module_promobar_bg_color');
        $data['text_color'] = $this->config->get('module_promobar_text_color');
        $data['speed'] = (int)$this->config->get('module_promobar_speed');
        $data['duplicate'] = $this->config->get('module_promobar_duplicate');

        if (empty($data['bg_color'])) {
            $data['bg_color'] = '#e74c3c';
        }
        
        if (empty($data['text_color'])) {
            $data['text_color'] = '#ffffff';
        }
        
        if (empty($data['speed']) || $data['speed'] < 5) {
            $data['speed'] = 30;
        }

        return $this->load->view('extension/module/promobar', $data);
    }
}
