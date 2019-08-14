<?php
/**
 *iMedia Ticket Support Widget Form
 */
class Imedia_TicketSupport_Block_Adminhtml_Support_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('imedia_ticketsupport_support_form');
        $this->setTitle(Mage::helper('imedia_ticketsupport')->__('Ticket Support Information'));
    }

    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('imedia_ticketsupport');
		
		
		$inquiryCollections = Mage::getModel('imedia_ticketsupport/support')->getCollection()
									->addFieldToFilter('id', $model->getData('id'))
									->getFirstItem();
										
		$active = $inquiryCollections->getIsActive();

		
		$form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action'    =>$this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('imedia_ticketsupport')->__('Ticket Support Information'),
            'class' => 'fieldset-wide',
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',				
            ));
        }

        $fieldset->addField('created_at', 'label', array(
            'created_at' => 'created_at',
            'label' => Mage::helper('imedia_ticketsupport')->__('created time'),
            'title' => Mage::helper('imedia_ticketsupport')->__('created time'),			
        ));
		
		$fieldset->addField('title', 'label', array(
            'title' => 'title',
            'label' => Mage::helper('imedia_ticketsupport')->__('Title'),
            'title' => Mage::helper('imedia_ticketsupport')->__('Title'),			
        ));
		
		$fieldset->addField('dept', 'label', array(
            'dept' => 'dept',
            'label' => Mage::helper('imedia_ticketsupport')->__('Department'),
            'title' => Mage::helper('imedia_ticketsupport')->__('Department'),
        ));

        $fieldset->addField('priority', 'label', array(
            'priority' => 'priority',
            'label' => Mage::helper('imedia_ticketsupport')->__('Priority'),
            'title' => Mage::helper('imedia_ticketsupport')->__('Priority'),
        ));
		
		 $fieldset->addField('message', 'label', array(
            'priority' => 'priority',
            'label' => Mage::helper('imedia_ticketsupport')->__('Message'),
            'title' => Mage::helper('imedia_ticketsupport')->__('Message'),
        ));
		
		 $fieldset->addField('status', 'label', array(
            'status' => 'status',
            'label' => Mage::helper('imedia_ticketsupport')->__('Status'),
            'title' => Mage::helper('imedia_ticketsupport')->__('Status'),
        ));
		
		/*$fieldset->addField('user_question', 'label', array(
            'user_question' => 'user_question',
            'label' => Mage::helper('imedia_productinquiry')->__('Question'),
            'title' => Mage::helper('imedia_productinquiry')->__('Question'),
        ));
		
		$fieldset->addField('admin_answer', 'textarea', array(
          'label'     => Mage::helper('imedia_productinquiry')->__('Answer'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'admin_answer',
        ));
		
		$fieldset->addField('is_active', 'select', array(
          'label'     => Mage::helper('imedia_productinquiry')->__('Is Active'),
          'class'     => 'required-entry',
		  'name'      => 'is_active',
          'required'  => true,
		  'value'=>$active,
          'values' => array('Yes' => 'Yes','No' => 'No'),
		  'after_element_html' => '<br/><small>Select Yes to show this answer on Product Page</small>',
		));
		$fieldset->addField('sand_mail', 'checkbox', array(
          'label'     => Mage::helper('imedia_productinquiry')->__('Notify User by Email'),
		  'onclick'   => 'this.value = this.checked ? 1 : 0;',
          'name'      => 'send_mail',
        ));*/
		
		$form_data = $model->getData();
		$form->setValues($form_data);
		$form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}