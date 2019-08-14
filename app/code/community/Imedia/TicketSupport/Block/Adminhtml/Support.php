<?php
/**
 *iMedia Ticket Support Widget Grid Container
 */
class Imedia_TicketSupport_Block_Adminhtml_Support extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup='imedia_ticketsupport';
        $this->_controller='adminhtml_support';
        $this->_headerText= Mage::helper('imedia_ticketsupport')->__('Imedia Ticket Support');
        parent::__construct();
        $this->removeButton('add');
    }
}