<?php
/**
 * @version    1.5
 * @package    Tienda
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if ($num > 0 && $addresses)
{
    ?>
    <table class="adminlist" style="margin-bottom: 5px;">
    <thead>
    <tr>
        <th colspan="3"><?php echo JText::_('COM_TIENDA_USER_ADDRESSES'); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th><?php echo JText::_('COM_TIENDA_NAME'); ?></th>
        <th><?php echo JText::_('COM_TIENDA_ADDRESS'); ?></th>
        <th><?php echo JText::_('COM_TIENDA_DEFAULT'); ?></th>
    </tr>
    <?php
    foreach ($addresses as $address)
    {
        ?>
        <tr>
            <td style="text-align: center;">
                <?php echo $address->address_name; ?>
            </td>
            <td style="text-align: left;">
                <?php // TODO Use sprintf to enable formatting?  How best to display addresses? ?>
                <!-- ADDRESS -->
                <b><?php echo @$address->first_name; ?> <?php echo @$address->middle_name; ?> <?php echo @$address->last_name; ?></b><br/>
                <?php if (!empty($address->company)) { echo $address->company; ?><br/><?php } ?>
                <?php echo $address->address_1; ?><br/>
                <?php if (!empty($address->address_2)) { echo $address->address_2; ?><br/><?php } ?>
                <?php echo @$address->city; ?>, <?php echo @$address->zone_name; ?> <?php echo @$address->postal_code; ?><br/>
                <?php echo @$address->country_name; ?><br/>
                <!-- PHONE NUMBERS -->
                <?php // if ($address->phone_1 || $address->phone_2 || $address->fax) { echo "<hr/>"; } ?>
                <?php if (!empty($address->phone_1)) { echo "&nbsp;&bull;&nbsp;<b>".JText::_('COM_TIENDA_PHONE')."</b>: ".$address->phone_1; ?><br/><?php } ?>
                <?php if (!empty($address->phone_2)) { echo "&nbsp;&bull;&nbsp;<b>".JText::_('COM_TIENDA_ALT_PHONE')."</b>: ".$address->phone_2; ?><br/><?php } ?>
                <?php if (!empty($address->fax)) { echo "&nbsp;&bull;&nbsp;<b>".JText::_('COM_TIENDA_FAX')."</b>: ".$address->fax; ?><br/><?php } ?>
            </td>
            <td style="text-align: center;">
                <?php if ($address->is_default_shipping && $address->is_default_billing)
                {
                    echo JText::_('COM_TIENDA_DEFAULT_BILLING_AND_SHIPPING_ADDRESS');
                }
                elseif ($address->is_default_shipping) 
                {
                echo JText::_('COM_TIENDA_DEFAULT_SHIPPING_ADDRESS');
                }
                elseif ($address->is_default_billing) 
                {
                echo JText::_('COM_TIENDA_DEFAULT_BILLING_ADDRESS');
                }
                ?>
            </td>
        </tr>
        <?php
    } 
    ?>
    </tbody>
    </table>
    <?php
}  
    elseif ($display_null == '1') 
{
    echo JText::_( $null_text );
}