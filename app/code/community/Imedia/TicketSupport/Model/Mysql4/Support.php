<?php

class Imedia_TicketSupport_Model_Mysql4_Support extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('imedia_ticketsupport/support', 'id');
    }
}