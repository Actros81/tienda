<?php
/**
 * @package Tienda
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if (!class_exists('TiendaHelperBase')) {
    JLoader::register( "TiendaHelperBase", JPATH_ADMINISTRATOR.DS."components".DS."com_tienda".DS."helpers".DS."_base.php" );
}

class TiendaHelperEmail extends TiendaHelperBase
{
    /**
     * Protected! Use getInstance()
     */ 
    protected function TiendaHelperEmail() 
    {
        parent::__construct();
        $this->use_html = true;
    }
    
    /**
     * Returns 
     * @param mixed Data to send
     * @param type  Type of mail.
     * @return boolean
     */
    public function sendEmailNotices( $data, $type = 'order' ) 
    {
        $mainframe = JFactory::getApplication();
        $success = false;
        $done = array();

        // grab config settings for sender name and email
        $config     = &TiendaConfig::getInstance();
        $mailfrom   = $config->get( 'emails_defaultemail', $mainframe->getCfg('mailfrom') );
        $fromname   = $config->get( 'emails_defaultname', $mainframe->getCfg('fromname') );
        $sitename   = $config->get( 'sitename', $mainframe->getCfg('sitename') );
        $siteurl    = $config->get( 'siteurl', JURI::root() );
        
        $recipients = $this->getEmailRecipients( $data->order_id, $type );
        $content = $this->getEmailContent( $data, $type );
        
        // trigger event onAfterGetEmailContent 
        $dispatcher=& JDispatcher::getInstance(); 
        $dispatcher->trigger('onAfterGetEmailContent', array( $data, &$content) ); 
                
        for ($i=0; $i<count($recipients); $i++) 
        {
            $recipient = $recipients[$i];
            if (!isset($done[$recipient])) 
            {
                if ( $send = $this->_sendMail( $mailfrom, $fromname, $recipient, $content->subject, $content->body ) ) 
                {
                    $success = true;
                    $done[$recipient] = $recipient;
                }   
            }
        }       
        
        return $success;
    }

    /**
     * Returns an array of user objects
     * of all users who should receive this email 
     *  
     * @param $data Object
     * @return array
     */
    private function getEmailRecipients( $id, $type = 'order' ) 
    {
        $recipients = array();
        
        switch ($type)
        {
            case "new_order":
                $system_recipients = $this->getSystemEmailRecipients();
                foreach ($system_recipients as $r)
                {
                    if (!in_array($r->email, $recipients))
                    {
                        $recipients[] = $r->email;    
                    }
                }
            case 'order':
            default:                
                $model = Tienda::getClass('TiendaModelOrders', 'models.orders');
                $model->setId( $id );
                $order = $model->getItem();

                $user = JUser::getInstance( $order->user_id );
                
                // is the email one of our guest emails?
                $pos = strpos($user->email, "guest");
                if ($pos === false) 
                {
                    // string needle NOT found in haystack
                    if (!in_array($user->email, $recipients))
                    {
                        $recipients[] = $user->email;    
                    }
                }
                    else 
                {
                    // add the userinfo email to the list of recipients
                    if (!in_array($order->userinfo_email, $recipients))
                    {
                        $recipients[] = $order->userinfo_email;    
                    }
                }
                
                // add the order user_email to the list of recipients
                if (!in_array($order->user_email, $recipients))
                {
                    $recipients[] = $order->user_email;    
                }
              break;
        }
        
        return $recipients;
    }

    /**
     * Returns 
     * 
     * @param object
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
    private function getEmailContent( $data, $type = 'order' ) 
    {
        $mainframe = JFactory::getApplication();
        $type = strtolower($type);  

        $lang = &JFactory::getLanguage();
        $lang->load('com_tienda', JPATH_ADMINISTRATOR);
        
        $return = array();
        
        $return = new stdClass();
        $return->body = '';
        $return->subject = '';

        // get config settings
        $config = &TiendaConfig::getInstance();
        
        $sitename = $config->get( 'sitename', $mainframe->getCfg('sitename') );
        $siteurl = $config->get( 'siteurl', JURI::root() );

        $lang = JFactory::getLanguage();
        $lang->load('com_tienda', JPATH_ADMINISTRATOR);
        
        switch ($type) 
        {
            case "subscription_new":
            case "new_subscription":
            case "subscription":
                $user = JUser::getInstance($data->user_id);
                $link = JURI::root()."index.php?option=com_tienda&view=orders&task=view&id=".$data->order_id;
                $link = JRoute::_( $link, false );
                
                if ( count($data->history) == 1 )
                {
                    // new order
                    $return->subject = sprintf( JText::_('EMAIL_NEW_ORDER_SUBJECT'), $data->order_id );

                    // set the email body
                    $text = JText::_('EMAIL_DEAR') ." ".$user->name.",\n\n";
                    $text .= JText::_("EMAIL_THANKS_NEW_SUBSCRIPTION")."\n\n";
                    $text .= JText::_("EMAIL_CHECK")." ".$link."\n\n";
                    $text .= JText::_("EMAIL_RECEIPT_FOLLOWS")."\n\n";
                    if ($this->use_html)
                    {
                        $text = nl2br( $text );
                    }
                    
                    // get the order body
                    Tienda::load( 'TiendaHelperOrder', 'helpers.order' );
                    $text .= TiendaHelperOrder::getOrderHtmlForEmail( $data->order_id );
                }
                    else
                {
                    // Status Change
                    $return->subject = JText::_( 'EMAIL_SUBSCRIPTION_STATUS_CHANGE' );
                    $last_history = count($data->history) - 1;
                    
                    $text  = JText::_('EMAIL_DEAR') ." ".$user->name.",\n\n";
                    $text .= sprintf( JText::_("EMAIL_ORDER_UPDATED"), $data->order_id );
                    if (!empty($data->history[$last_history]->comments))
                    {
                        $text .= sprintf( JText::_("EMAIL_ADDITIONAL_COMMENTS"), $data->history[$last_history]->comments );
                    }
                    $text .= JText::_("EMAIL_CHECK")." ".$link;

                    if ($this->use_html)
                    {
                        $text = nl2br( $text );
                    }
                }
                
                $return->body = $text;
                break;
            case "order":
            default:
                $user = JUser::getInstance($data->user_id);
                $link = JURI::root()."index.php?option=com_tienda&view=orders&task=view&id=".$data->order_id;
                $link = JRoute::_( $link, false );
                
                if ( count($data->orderhistory) == 1 )
                {
                    // new order
                    $return->subject = sprintf( JText::_('EMAIL_NEW_ORDER_SUBJECT'), $data->order_id );

                    // set the email body
                    $text = JText::_('EMAIL_DEAR') ." ".$user->name.",\n\n";
                    $text .= JText::_("EMAIL_THANKS_NEW_ORDER")."\n\n";
                    $text .= JText::_("EMAIL_CHECK")." ".$link."\n\n";
                    $text .= JText::_("EMAIL_RECEIPT_FOLLOWS")."\n\n";
                    if ($this->use_html)
                    {
                        $text = nl2br( $text );
                    }
                    
                    // get the order body
                    Tienda::load( 'TiendaHelperOrder', 'helpers.order' );
                    $text .= TiendaHelperOrder::getOrderHtmlForEmail( $data->order_id );
                }
                    else
                {
                    // Status Change
                    $return->subject = JText::_( 'EMAIL_ORDER_STATUS_CHANGE' );
                    $last_history = count($data->orderhistory) - 1;
                    
                    $text  = JText::_('EMAIL_DEAR') ." ".$user->name.",\n\n";
                    $text .= sprintf( JText::_("EMAIL_ORDER_UPDATED"), $data->order_id );
                    $text .= JText::_("EMAIL_NEW_STATUS")." ".$data->orderhistory[$last_history]->order_state_name."\n\n";
                    if (!empty($data->orderhistory[$last_history]->comments))
                    {
                        $text .= sprintf( JText::_("EMAIL_ADDITIONAL_COMMENTS"), $data->orderhistory[$last_history]->comments );
                    }
                    $text .= JText::_("EMAIL_CHECK")." ".$link;

                    if ($this->use_html)
                    {
                        $text = nl2br( $text );
                    }
                }
                
                $return->body = $text;
              break;
        }
        
        return $return;

    }

    /**
     * Prepares and sends the email
     * 
     * @param unknown_type $from
     * @param unknown_type $fromname
     * @param unknown_type $recipient
     * @param unknown_type $subject
     * @param unknown_type $body
     * @param unknown_type $actions
     * @param unknown_type $mode
     * @param unknown_type $cc
     * @param unknown_type $bcc
     * @param unknown_type $attachment
     * @param unknown_type $replyto
     * @param unknown_type $replytoname
     * @return unknown_type
     */
    private function _sendMail( $from, $fromname, $recipient, $subject, $body, $actions=NULL, $mode=NULL, $cc=NULL, $bcc=NULL, $attachment=NULL, $replyto=NULL, $replytoname=NULL ) 
    {
        $success = false;
        $mailer = JFactory::getMailer();
        $mailer->addRecipient( $recipient );
        $mailer->setSubject( $subject );
        
        // check user mail format type, default html
        $mailer->IsHTML($this->use_html);
        $body = htmlspecialchars_decode( $body );
        $mailer->setBody( $body );
            
        $sender = array( $from, $fromname );
        $mailer->setSender($sender);
            
        $sent = $mailer->send();
        if ($sent == '1') 
        {
            $success = true;
        }
        
        return $success;
    }
    
    /**
     * Gets all targets for system emails
     * 
     * return array of objects
     */
    function getSystemEmailRecipients()
    {
        $db =& JFactory::getDBO();
        $query = "
            SELECT tbl.email
            FROM #__users AS tbl
            WHERE tbl.sendEmail = '1';
        "; 
        $db->setQuery( $query );
        $items = $db->loadObjectList();
        if (empty($items))
        {
            return array();
        }
        return $items;
    }
}