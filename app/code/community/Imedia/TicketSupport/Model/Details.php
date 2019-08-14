<?php
class Imedia_TicketSupport_Model_Details extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('imedia_ticketsupport/details');
    }
}