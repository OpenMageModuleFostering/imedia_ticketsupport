<?php
/**
 *iMedia Ticket Support Form
 */ 
class Imedia_TicketSupport_Block_Adminhtml_Customform extends Mage_Adminhtml_Block_Template

{

          public function __construct() {

                   parent::__construct();

                   $this->setTemplate('imedia/tickets.phtml');
				   
				   $post = $this->getRequest()->getParams();
				   $id = $post['id'];

                   $this->setFormAction(Mage::helper("adminhtml")->getUrl("*/*/updateticket",array("id"=>$id)));  // If you want we can set the .html form action here

          }

}
?>