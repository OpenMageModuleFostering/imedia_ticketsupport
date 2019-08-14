<?php
/**
 *iMedia Ticket Support Controller
 */
class Imedia_TicketSupport_IndexController extends Mage_Core_Controller_Front_Action
{
    function indexAction()
    { 
        if( !Mage::getSingleton('customer/session')->isLoggedIn() ) {
            Mage::getSingleton('customer/session')->authenticate($this);
            return;
        }
        
        $this->loadLayout();
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('ticketsupport/index');
        }

        $this->renderLayout();
	}

	function ticketsAction()
    { 
        if( !Mage::getSingleton('customer/session')->isLoggedIn() ) {
            Mage::getSingleton('customer/session')->authenticate($this);
            return;
        }
        
        $this->loadLayout();
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('ticketsupport/index/tickets');
        }

        $this->renderLayout();
	}	 
	
	function sendAction()
    { 
	
		  $post = $this->getRequest()->getPost();
		  $title = $post['ticket_subject'];
		  $department = $post['t_department_id'];
		  $priority = $post['t_priority_id'];
		  $content = $post['content'];
		  $created_time = date('Y-M-d');
		  
		   if(Mage::getSingleton('customer/session')->isLoggedIn()) {
				$customerData = Mage::getSingleton('customer/session')->getCustomer();
				$customerid =   $customerData->getId();
				$customer_name = $customerData->getName();
				$customer_email = $customerData->getEmail();
				}
		  
		  $postData = array('title'=>$title,'created_at'=>$created_time, 'dept'=>$department, 'priority'=> $priority, 'customerid'=> $customerid, 'status'=>'open');
		  
		  $supportModel = Mage::getModel('imedia_ticketsupport/support');
		  $supportModel->setData($postData);
		  $supportModel->save();
		  
		  $ticket_id = $supportModel->getId();
		  
		  
		  $postData1 = array('ticketid'=>$ticket_id,'sender'=>$customer_name,'comments'=> htmlspecialchars ($content),'commentdate'=>$created_time);
		  
		$detailsModel = Mage::getModel('imedia_ticketsupport/details');
		
		try{	
			//admin notification email
			
			$emailTemplate = Mage::getModel('core/email_template')->loadDefault('ticket_admin_notification');
			//Getting the Store E-Mail Sender Name.
			$storeName = Mage::getStoreConfig('general/store_information/name');
			//Getting the Store General E-Mail.
			$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email');
			//Variables for Confirmation Mail.
			$emailTemplateVariables = array();
			$emailTemplateVariables['ticketId'] =' (#'.$ticketid.')';
			$emailTemplateVariables['customerName'] = ucfirst($customer_name);
			$emailTemplateVariables['customerComment'] = htmlspecialchars ($content);
			
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
			
			$subject = $title.' (#'.$ticket_id.')';
			$mail = Mage::getModel('core/email')
						->setToName($storeName)
						->setToEmail($adminEmail)
						->setBody($processedTemplate)
						->setSubject('Ticket Support - '.$subject)
						->setFromEmail($customer_email)
						->setFromName($customer_name)
						->setType('html');
			
			$mail->send();	
			
			$detailsModel->setData($postData1);
			$detailsModel->save();
			Mage::getSingleton('core/session')->addSuccess('Your ticket has been created.');
			Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl("ticketsupport/index/tickets/"));
		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl("ticketsupport/index/tickets/"));
		}
	}	
	function viewticketAction()
    { 
	
        if( !Mage::getSingleton('customer/session')->isLoggedIn() ) {
            Mage::getSingleton('customer/session')->authenticate($this);
            return;
        }
        
        $this->loadLayout();
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('ticketsupport/index/tickets');
        }

        $this->renderLayout();
	}	

 	function replyAction()
    { 
	
		  $post = $this->getRequest()->getParams();
		  $content = $post['content'];
		  $ticketid = $post['ticket_id'];
		  $commentdate = date('Y-M-d');
		  
		   if(Mage::getSingleton('customer/session')->isLoggedIn()) {
				$customerData = Mage::getSingleton('customer/session')->getCustomer();
				$customer_name = $customerData->getName();
				$customerEmail = $customerData->getEmail();
				}
		  
		  $postData1 = array('ticketid'=>$ticketid,'sender'=>$customer_name,'comments'=> htmlspecialchars ($content),'commentdate'=>$commentdate);
		  
		  $detailsModel = Mage::getModel('imedia_ticketsupport/details');
		  $detailsModel->setData($postData1);
		  $detailsModel->save();
		  
		  
		  $adminReplies = Mage::getModel('imedia_ticketsupport/details')->getCollection();
		  $adminReplies->addFieldToFilter('ticketid', $ticketid );
		  $adminReplies->addFieldToFilter('sender', 'admin' );
		  $adminReplies->getSelect()->order('comments DESC');
		  //$custQue = $customerQues->getFirstItem()->getComments();
		  $adminReply = $adminReplies->getFirstItem()->getComments();
		  
		  
		   try{	
			//customer notification email
			
			$emailTemplate = Mage::getModel('core/email_template')->loadDefault('ticket_customer_replied_notification');
			//Getting the Store E-Mail Sender Name.
			$storeName = Mage::getStoreConfig('general/store_information/name');
			$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email');
			//Getting the Store General E-Mail.
			$customerEmail = $customerEmail;
			//Variables for Confirmation Mail.
			$emailTemplateVariables = array();
			$emailTemplateVariables['ticketId'] =' (#'.$ticketid.')';
			$emailTemplateVariables['customerName'] = ucfirst($customer_name);
			$emailTemplateVariables['customerComment'] = htmlspecialchars($content);
			$emailTemplateVariables['adminlastComment'] = htmlspecialchars($adminReply);
			
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
			
			$subject = $title.' (#'.$ticketid.')';
			$mail = Mage::getModel('core/email')
						->setToName($storeName)
						->setToEmail($adminEmail)
						->setBody($processedTemplate)
						->setSubject('Ticket Support - '.$subject)
						->setFromEmail($customerEmail)
						->setFromName($customer_name)
						->setType('html');
			
			$mail->send();	
						
		Mage::getSingleton('core/session')->addSuccess('Your comment has been submitted.');
		Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl("ticketsupport/index/viewticket?id=".$ticketid));
		 
		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl("ticketsupport/index/viewticket?id=".$ticketid));
		}
		  

		
		
	}	 
  
}