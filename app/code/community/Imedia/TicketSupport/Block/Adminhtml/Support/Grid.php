<?php
/**
 *iMedia Ticket Support Widget Grid 
 */
class Imedia_TicketSupport_Block_Adminhtml_Support_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('id');
        $this->setId('imedia_ticketsupport_support_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _getCollectionClass()
    {
        return 'imedia_ticketsupport/support_collection';
    }

    protected function _prepareCollection()
    {
       
		$collection = Mage::getResourceModel($this->_getCollectionClass());
		$this->setCollection($collection);
	    return parent::_prepareCollection();
    
	}

    protected function _prepareColumns()
    {
       
		$this->addColumn('id',array(
                'header'=> Mage::helper('imedia_ticketsupport')->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'id'
            )
        );
		
		$this->addColumn('title',
            array(
                'header'=> Mage::helper('imedia_ticketsupport')->__('title'),
                'index' => 'title'
            )
        );
		
		$this->addColumn('dept',
            array(
                'header'=> Mage::helper('imedia_ticketsupport')->__('Department'),
                'index' => 'dept'
            )
        );
        $this->addColumn('priority',
            array(
                'header'=> Mage::helper('imedia_ticketsupport')->__('Priority'),
                'index' => 'priority'
            )
        );
		$this->addColumn('created_at',
            array(
                'header'=> Mage::helper('imedia_ticketsupport')->__('Created on'),
                'index' => 'created_at'
            )
        );
		$this->addColumn('status',
            array(
                'header'=> Mage::helper('imedia_ticketsupport')->__('status'),
                'index' => 'status'
            )
        );
       
        $this->addColumn('action',
            array(
                'header'    => Mage::helper('imedia_ticketsupport')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('imedia_ticketsupport')->__('View'),
                        'url'     => array(
                            'base'=>'*/*/edit',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
            ));

        return parent::_prepareColumns();
    }
    protected function _prepareMassAction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
        
		$this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('imedia_ticketsupport')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete', array('' => '')),
            'confirm' => Mage::helper('imedia_ticketsupport')->__('Are you sure you want to delete the selected listing(s)?')
        ));
		
		$this->getMassactionBlock()->addItem('closed', array(
             'label'=> Mage::helper('imedia_ticketsupport')->__('Close Ticket'),
             'url'  => $this->getUrl('*/*/massClose', array('_current'=>true)),
        ));
         
        $this->getMassactionBlock()->addItem('open', array(
             'label'=> Mage::helper('imedia_ticketsupport')->__('Open Ticket'),
             'url'  => $this->getUrl('*/*/massOpen', array('_current'=>true)),
        ));
		
        return $this;
    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    }