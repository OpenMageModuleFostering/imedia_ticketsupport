<?php
/**
 *iMedia Ticket Support form Container
 */
class Imedia_TicketSupport_Block_Adminhtml_Support_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
public function __construct()
{
    $this->_blockGroup = 'imedia_ticketsupport';
    $this->_controller = 'adminhtml_support';

    parent::__construct();

   
}

/**
 * Get Header text
 *
 * @return string
 */
public function getHeaderText()
{
    if (Mage::registry('imedia_ticketsupport')->getId()) {
        return Mage::helper('imedia_ticketsupport')->__('Edit Ticket Support');
    }
    else {
        return Mage::helper('imedia_ticketsupport')->__('New Ticket Support');
    }
}
}