<?php

/**
 * Adminhtml Deals controller
 *
 * @category   Webinse
 * @package    Webinse_DailyDeals
 * @author     Webinse Team <info@webinse.com.com>
 */
class Webinse_DailyDeals_Adminhtml_DealsController extends Mage_Adminhtml_Controller_Action {
	/**
	 * Init actions
	 *
	 * @return Mage_Adminhtml_Cms_PageController
	 */
	protected function _initAction() {
		// load layout, set active menu and breadcrumbs
		$this->loadLayout()
		     ->_setActiveMenu('webinse')
		     ->_title($this->__('Daily Deals'))
		     ->_title($this->__('Webinse'));
		$this->_addBreadcrumb(Mage::helper('dailydeals')->__('Daily deals'), Mage::helper('dailydeals')->__('Daily deals'), $this->getUrl());

		return $this;
	}

	protected function _initGroup() {
		$this->_title($this->__('Product Discount'))->_title($this->__('Manage'));

		Mage::register('current_product', Mage::getModel('catalog/product'));
		$userId = $this->getRequest()->getParam('id');
		if( ! is_null($userId)) {
			Mage::registry('current_product')->load($userId);
		}
	}

	/**
	 * List action for students grid
	 */

	public function listAction() {
		$this->_initAction();
		$this->renderLayout();
	}

	public function newAction() {
		$this->_initAction();
		$this->renderLayout();
	}

	public function editAction() {
		$this->_initGroup();
		$this->loadLayout();
		$this->_setActiveMenu('webinse/deals');
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Product Discount'), Mage::helper('adminhtml')->__('Product Discount'));
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Discount'), Mage::helper('adminhtml')->__('Discount'), $this->getUrl('*/discount'));

		if($this->getRequest()->getParam('id')) {
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Discount'), Mage::helper('adminhtml')->__('Edit Discount'));
		} else {
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('New Discount'), Mage::helper('adminhtml')->__('New Discount'));
		}
		$this->getLayout()->getBlock('content')
		     ->append($this->getLayout()->createBlock('dailydeals/adminhtml_deals_edit', 'discount')
		                   ->setEditMode((bool) Mage::registry('current_product')->getId()));

		$this->renderLayout();
	}

	protected function filterData($data) {
		$data = $this->_filterDates($data, array('deal_start_time', 'deal_end_time'));

		return $data;
	}

	/**
	 * Controller for save new deal product
	 */
	public function saveAction() {

		$id      = $this->getRequest()->getParam('id');
		$product = Mage::getModel('catalog/product');
		$data    = $this->getRequest()->getParams();
		$data    = $this->filterData($data);
		if($id) {
			try {
				if( ! $data['deal_status']) {
					$data['deal_status']   = true;
					$data['deal_statuses'] = 'Disabled';
				}
				$product->load($id)->addData($data)->save();
				$this->getResponse()->setRedirect($this->getUrl('*/*/list'));

				return;
			} catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setGroupData($product->getData());
				$this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('id' => $id)));

				return;
			}
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Deal with id \'' . (int) $id . '\' not found.'));
			$this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('id' => $id)));

			return;
		}
	}

	/**
	 * Controller for change deal status
	 */
	public function massStatusAction() {

		$status = $this->getRequest()->getParam('status');
		$ids    = $this->getRequest()->getParam('banners');
		if( ! $ids) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Banner(s)'));
		} else {
			try {
				foreach($ids as $id) {
					$product = Mage::getModel('catalog/product')
					               ->load($id);
					if($status == 0) {
						$product->setDealStatus(true);
						$product->setDealStatuses('Disabled');
					} else {
						$product->setDealStatus($status);
						$product->setDealStatuses(null);
					}

					$product->save();
				}
				$this->_getSession()->addSuccess(
					$this->__('Total of %d record(s) were successfully updated', count($ids))
				);
			} catch(Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/list');
	}

	/**
	 * for grid ajax
	 */
	public function gridAction() {
		$this->loadLayout()->renderLayout();
	}

	/**
	 * for grid "products" ajax
	 */
	public function productsAction() {
		$this->loadLayout()->renderLayout();
	}

	public function massRemoveAction() {
		$entityIds = $this->getRequest()->getParam('banners');
		if( ! $entityIds) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Banner(s)'));
		} else {
			try {
				foreach($entityIds as $entityId) {
					$product = Mage::getModel('catalog/product')
					               ->load($entityId);
					Mage::helper('dailydeals')->returnSpecialDataWhenStatusesDisabled($product);
					$product->addData(array(
						'deal_status'     => null,
						'deal_statuses'   => 'removed',
						'deal_qty'        => null,
						'deal_price'      => null,
						'deal_start_time' => null,
						'deal_end_time'   => null,
						'deal_bought'     => null,
						'deal_qty'        => null,

					))->save();
				}
				$this->_getSession()->addSuccess(
					$this->__('Total of %d record(s) were successfully remove', count($entityIds)));
			} catch(Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/list');
	}
}