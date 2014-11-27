<?php

/**
 * Index controller
 *
 * @category   Webinse
 * @package    Webinse_DailyDeals
 * @author     Webinse Team <info@webinse.com.com>
 */
class Webinse_DailyDeals_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_title('Daily Deals');
        $this->loadLayout();
        $this->renderLayout();
    }
}


