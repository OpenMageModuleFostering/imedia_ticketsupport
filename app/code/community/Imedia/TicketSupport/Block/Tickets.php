<?php
/**
 *iMedia Ticket Support Ticket Block
 */
class Imedia_TicketSupport_Block_Tickets extends Mage_Core_Block_Template
{
 
	public function __construct() { 
				parent::__construct(); 
				$collection = Mage::getModel('imedia_ticketsupport/support')->getCollection();
				if (Mage::getSingleton('customer/session')->isLoggedIn()) {
					 $customer = Mage::getSingleton('customer/session')->getCustomer();
					 $cust_id = $customer->getId();
				}	
				$collection->addFieldToFilter( 'customerid', $cust_id );
				$collection->addFieldToFilter( 'status','open');
				$collection->getSelect()->order('created_at desc');
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