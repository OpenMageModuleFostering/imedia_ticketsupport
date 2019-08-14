<?php
/**
 *iMedia Ticket Support View Ticket
 */
class Imedia_TicketSupport_Block_Viewticket extends Mage_Core_Block_Template
{
 
	public function __construct() { 
				parent::__construct(); 
				$ticketid = $this->getRequest()->getParam('id');
				$collection = Mage::getModel('imedia_ticketsupport/details')->getCollection();
				$collection->addFieldToFilter('ticketid', $ticketid );
				$collection->getSelect()->order('commentdate desc');
				$this->setCollection($collection); 
	} 
   
	protected function _prepareLayout() { 
				parent::_prepareLayout(); 
				$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager'); 
				$pager->setAvailableLimit(array(10=>10,20=>20,30=>30,'all'=>'all')); 
				$pager->setCollection($this->getCollection()); 
				$this->setChild('pager', $pager); 
				$this->getCollection()->load(); 
				return $this; 
	} 
   
	public function getPagerHtml() { 
				return $this->getChildHtml('pager'); 
	}
}