<?php

class Imedia_TicketSupport_Model_Mysql4_Details_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('imedia_ticketsupport/details');
    }
}