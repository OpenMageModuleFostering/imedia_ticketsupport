<?php
/**
 *iMedia Ticket Support Controller
 */
class Imedia_TicketSupport_Adminhtml_SupportController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }


    /**
     * Initialize action
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('imedia_ticketsupport_support')
            ->_title(Mage::helper('imedia_ticketsupport')->__('Ticket Support'))
            ->_addBreadcrumb(Mage::helper('imedia_ticketsupport')->__('Ticket Support'))
            ->_addBreadcrumb(Mage::helper('imedia_ticketsupport')->__('Ticket Support'));

        return $this;
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
		
		$this->loadLayout();
		$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('imedia_ticketsupport/adminhtml_customform'));
		$this->renderLayout();
	
    }
	public function updateticketAction()
    {
          $post = $this->getRequest()->getParams();
		  $content = $post['content'];
		  $ticketid = $post['id'];
		  $commentdate = date('Y-M-d');
		  		  
		  $postData1 = array('ticketid'=>$ticketid,'sender'=>'admin','comments'=> htmlspecialchars ($content),'commentdate'=>$commentdate);
		  
		  $detailsModel = Mage::getModel('imedia_ticketsupport/details');
		  $detailsModel->setData($postData1);
		  $detailsModel->save();
		  		  
		  $supportModel = Mage::getModel('imedia_ticketsupport/support')->getCollection()->addFieldToFilter('id',$ticketid)->getFirstItem();
		  $customerid = $supportModel->getCustomerid();
		  $title = $supportModel->getTitle();
		  
		  $customer = Mage::getModel('customer/customer')->load($customerid);
		  $customerEmail = $customer->getEmail();
		  $customerName = $customer->getName();
		  
		  $customerQues = Mage::getModel('imedia_ticketsupport/details')->getCollection();
		  $customerQues->addFieldToFilter('ticketid', $ticketid );
		  $customerQues->addFieldToFilter('sender', $customerName );
		  $customerQues->getSelect()->order('comments DESC');
		  $custQue = $customerQues->getFirstItem()->getComments();
		  
		  //$customerData = Mage::getModel('customer/customer')->load($customerid)->getData();
		  //echo $customerEmail = $customerData->getEmail(); die();
		 
		 try{	
			//customer notification email
			
			$emailTemplate = Mage::getModel('core/email_template')->loadDefault('ticket_customer_notification');
			//Getting the Store E-Mail Sender Name.
			$storeName = Mage::getStoreConfig('general/store_information/name');
			$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email');
			//Getting the Store General E-Mail.
			$customerEmail = $customerEmail;
			//Variables for Confirmation Mail.
			$emailTemplateVariables = array();
			$emailTemplateVariables['ticketId'] =' (#'.$ticketid.')';
			$emailTemplateVariables['adminName'] = ucfirst($storeName);
			$emailTemplateVariables['adminComment'] = htmlspecialchars($content);
			$emailTemplateVariables['custlastComment'] = htmlspecialchars($custQue);
			
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
			
			$subject = $title.' (#'.$ticketid.')';
			$mail = Mage::getModel('core/email')
						->setToName($customerName)
						->setToEmail($customerEmail)
						->setBody($processedTemplate)
						->setSubject('Ticket Support - '.$subject)
						->setFromEmail($adminEmail)
						->setFromName($storeName)
						->setType('html');
			
			$mail->send();	
						
		 Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('imedia_ticketsupport')->__('Comment was successfully posted'));
		 $this->_redirect('*/*/');
		 
		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');
		}
		
    }
	
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('imedia_ticketsupport/support');
				$model->setId($this->getRequest()->getParam('id'))->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('imedia_ticketsupport')->__('Ticket was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

   
    public function massDeleteAction()
    {
        $adListingIds = $this->getRequest()->getParam('id');
        if(!is_array($adListingIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('imedia_ticketsupport')->__('Please select Any Listing(s).'));
        } else {
            try {
                $model = Mage::getSingleton('imedia_ticketsupport/support');
				
                foreach ($adListingIds as $adId) {
				
					$model->load($adId)->delete();
				
				}
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('imedia_ticketsupport')->__('Total of %d record(s) were deleted.', count($adListingIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
	
	
	 public function massOpenAction()
    {

		$ids = $this->getRequest()->getParam('id');
		if(!is_array($ids)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select the records to change ticket status'));
		} 
		else {
			try {
				$model = Mage::getModel('imedia_ticketsupport/support');
				foreach ($ids as $id) {
					$model->load($id)->setStatus('open')->save(); 
				}
			} 
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		Mage::getSingleton('core/session')->addSuccess($this->__('Ticket status successfully changed for Ticket #id %s',$id));
		$this->_redirect('*/*/');
    }
    
    /**
     * Set featured product
     *
     */
    public function massCloseAction()
    {
		$ids = $this->getRequest()->getParam('id');
		if(!is_array($ids)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select the records to change ticket status'));
		} 
		else {
			try {
				$model = Mage::getModel('imedia_ticketsupport/support');
				foreach ($ids as $id) {
					$model->load($id)->setStatus('close')->save(); 
				}
			} 
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		Mage::getSingleton('core/session')->addSuccess($this->__('Ticket status successfully changed for Ticket #id %s',$id));
		$this->_redirect('*/*/');
    }
	
	 public function closeticketAction()
	 {
		 $id = $this->getRequest()->getParam('id');
			
			$model = Mage::getModel('imedia_ticketsupport/support');
			$model->load($id)->setStatus('close')->save(); 
				
        Mage::getSingleton('core/session')->addSuccess($this->__('Ticket status successfully changed for Ticket #id %s',$id));
		$this->_redirect('*/*/'); 
	 }
	
	
    public function messageAction()
    {
        $data = Mage::getModel('imedia_ticketsupport/support')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('imedia_ticketsupport_support');
    }
   
}