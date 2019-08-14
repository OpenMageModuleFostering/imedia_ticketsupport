<?php
/**
 *iMedia Ticket Support Widget Tabs
 */
class Imedia_TicketSupport_Block_Adminhtml_Support_Edit_Tab extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('imedia_ticketsupport')->__('Ticket Support Information'));
    }
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('imedia_ticketsupport')->__('Ticket Support Information'),
            'title'     => Mage::helper('imedia_ticketsupport')->__('Ticket Support Information'),
        ));

        return parent::_beforeToHtml();
    }
}