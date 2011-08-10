<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'tienda_checkout_onepage.css', 'media/com_tienda/css/'); ?>
<?php JHTML::_('script', 'tienda.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('script', 'tienda_checkout.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('script', 'tienda_checkout_onepage.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('behavior.mootools' ); ?>

<a name="tienda-method"></a> 

<div id="tienda_checkout_pane">
<a name="tiendaRegistration" id="tiendaRegistration"></a>

<?php // login link ?>
<?php if(!$this->user->id ) : ?>
	<div class="tienda_checkout_method">
		<?php
			$uri = JFactory::getURI( );
			$return_link = base64_encode( $uri->toString( ) );
			$asklink = "index.php?option=com_tienda&view=checkout&task=registrationLink&tmpl=component&return=" . $return_link;
				
				$asktxt = TiendaUrl::popup( "{$asklink}.&tmpl=component", JText::_( "Click here to login" ),
						array(
							'width' => '490', 'height' => '320'
						) );
				$asktxt = "<a class=\"modal\" href='{$asklink}'>";
				$asktxt .= JText::_( "Click here to login" );
				$asktxt .= "</a>";
		?>
		[<?php echo $asktxt; ?>]
	</div>
<?php endif; ?>

<form action="<?php echo JRoute::_( @$form['action'] ); ?>" method="post" name="adminForm" enctype="multipart/form-data">
<div class="floatbox">
	<div class="left33">
		<div class="inner col3">
			<div class="tienda-collapse-processed contentheading"><?php echo JText::_('Customer Information')?></div>
				<div id="tienda_customer">
					<div class="note">
						<?php echo JText::_('Order information will be sent to your account e-mail listed below')?>	
					</div>
					<div class="tienda_checkout_method_user_email">
					<?php
						if($this->user->id)
							$email_address = $this->user->email;
						else
							$email_address = '';
					?>

					<?php echo JText::_('E-mail address');?>: <span id="user_email_span"><?php echo $email_address; ?></span>
						<input type="text" id="email_address" class="inputbox" name="email_address" value="<?php echo $email_address; ?>" onblur="tiendaCheckoutCheckEmail( 'user_email_validation',document.adminForm )"/>
					</div>
					<div id="user_email_validation"></div>
				</div> <?php // end id - customer pane ?>
			
			<?php // start billing address ?>
			<div class="tienda-expanded" id="billing-shipping-pane">
				<div class="tienda-collapse-processed contentheading"><?php echo $this->showShipping ? JText::_('Billing and Shipping Information') : JText::_('Billing Information'); ?></div>
				<div id="tienda_billing-shipping">
        			<?php 
						 		$baseurl = "index.php?option=com_tienda&format=raw&controller=addresses&task=getAddress&address_id=";                   
            		$billattribs = array(
                		'class' => 'inputbox',    
                    	'size' => '1',
                    	'onchange' => "tiendaCheckoutSetBillingAddress('$baseurl'+this.options[this.selectedIndex].value, 'billingDefaultAddress', this.options[this.selectedIndex].value );"
                	);
                        
                	// display select list of stored addresses
                	echo TiendaSelect::address( $this->user->id, @$this->billing_address->address_id, 'billing_address_id', 1, $billattribs, 'billing_address_id', false, true );
           		?>
            	<div>
					<div><?php echo JText::_('Billing Address')?></div>
					<span id="billingDefaultAddress">
				<?php 
					if ( !empty( $this->billing_address ) ):
						echo $this->billing_address->title . " ". $this->billing_address->first_name . " ". $this->billing_address->last_name . "<br>";
						echo $this->billing_address->company . "<br>";
						echo $this->billing_address->address_1 . " " . $this->billing_address->address_2 . "<br>";
						echo $this->billing_address->city . ", " . $this->billing_address->zone_name ." " . $this->billing_address->postal_code . "<br>";
						echo $this->billing_address->country_name . "<br>";
					endif;
					?>
				</span>
					<?php echo @$this->billing_address_form; ?>
				</div>
          		
          		<div class="reset marginbot"></div>
          		<?php if(!$this->user->id ) : ?>
					<div class="tienda_checkout_method">
						<input type="checkbox" id="create_account" name="create_account" value="" />
						<label for="field-create-account"><?php echo JText::_( 'Create a New Account' );?></label>
						<div id="tienda_user_additional_info">
                            <?php echo $this->form_user_register;?>
                        </div>
           			</div>
           		<?php endif; ?>
           		
           		<?php if($this->showShipping):?>
           		<div class="reset marginbot"></div>
            	<?php
                    $shipping_rates_text = JText::_( "Getting Shipping Rates" ); 
	                $shipattribs = array(
	                   'class' => 'inputbox',    
	                   'size' => '1',
	                   'onchange' => "tiendaCheckoutSetShippingAddress('$baseurl'+this.options[this.selectedIndex].value, 'shippingDefaultAddress', '$shipping_rates_text', this.form, this.options[this.selectedIndex].value ); "
	                ); // tiendaCheckoutSetShippingAddress();
	                
	                // display select list of stored addresses
	                echo TiendaSelect::address( JFactory::getUser()->id, @$this->shipping_address->address_id, 'shipping_address_id', 2, $shipattribs, 'shipping_address_id', false, true );
							?>
	
                <?php if (empty($this->shipping_address)) : ?>
                    <div>
                        <input id="sameasbilling" name="sameasbilling" type="checkbox" checked="checked" />&nbsp;
                        <?php echo JText::_( 'Same As Billing Address' ); ?>:
                    </div>
				<?php endif; ?>
				
				<!--    SHIPPING ADDRESS FORM  -->	         
	            <div>
					<span id="shippingDefaultAddress">
					<div><?php echo JText::_('Shipping Address'); ?></div>
					<?php 
					if ( !empty( $this->shipping_address ) )
					{
		        echo $this->shipping_address->title . " ". $this->shipping_address->first_name . " ". $this->shipping_address->last_name . "<br>";
						echo $this->shipping_address->company . "<br>";
						echo $this->shipping_address->address_1 . " " . $this->shipping_address->address_2 . "<br>";
						echo $this->shipping_address->city . ", " . $this->shipping_address->zone_name ." " . $this->shipping_address->postal_code . "<br>";
						echo $this->shipping_address->country_name . "<br>";
					}
					?>
					 </span>
					<?php echo @$this->shipping_address_form; ?>
				</div>
           		<?php else :?>
           		<input type="hidden" id="shippingrequired" name="shippingrequired" value="0"  />
           		<?php endif;?>
           
				</div>		
			</div> 
		</div>
	</div> <?php // end 1st column ?>
	<div class="left33">
		<div class="inner col3">
			<?php if($this->showShipping):?>	
			<div class="tienda-expanded" id="shippingcost-pane">
				<div class="tienda-collapse-processed contentheading"><?php echo JText::_('Select a Shipping Method')?></div>
				<div id="onCheckoutShipping_wrapper">
					<?php echo $this->shipping_method_form;?>
				</div>		
			</div>  
			<?php endif;?>
			
			<div class="reset marginbot"></div>	
			
			<div class="tienda-expanded" id="paymentmethod-pane">
				<div class="tienda-collapse-processed contentheading"><?php echo JText::_('Select a Payment Method')?></div>		
				<div id="onCheckoutPayment_wrapper">
					<?php echo $this->payment_options_html;?>                   
				</div>		
			</div> 
		</div>
	</div> <?php // end 2nd column ?>
	<div class="left33">
		<div class="inner col3">
			 <!--    ORDER SUMMARY   -->
			<h3 class="contentheading"><?php echo JText::_("Order Summary") ?></h3>
			<div id='onCheckoutCart_wrapper'> 
				<?php echo @$this->orderSummary; 	?> 
			</div>
			
			<div class="reset marginbot"></div>
			
			<?php $coupons_enabled = TiendaConfig::getInstance()->get('coupons_enabled'); ?>
	 		<?php if ($coupons_enabled && $this->coupons_present) : ?>
				<div class="tienda-expanded" id="coupon-pane">
					<div class="tienda-collapse-processed contentheading"><?php echo JText::_('Coupon Code')?></div>
					 <div id="coupon_code_area">
	            	 <div id="coupon_code_form">          
	           	 	<?php $mult_enabled = TiendaConfig::getInstance()->get('multiple_usercoupons_enabled'); ?>
	            	<?php $string = "Coupon Code Help"; if ($mult_enabled) { $string = "Coupon Code Help Multiple"; } ?>
	            		<div id="coupon_code_help" class="note"><?php echo JText::_($string); ?></div>
	            		<div id="coupon_code_message"></div>
	            		<input type="text" name="new_coupon_code" id="new_coupon_code" value="" />
	            		<input type="button" name="coupon_submit" value="<?php echo JText::_('Add Coupon to Order'); ?>"  onClick="tiendaAddCoupon( document.adminForm, '<?php if ($mult_enabled) { echo "1"; } else { echo "0"; } ?>' );"/>
	            	</div>
	            	<div id='coupon_codes' style="display: none;"></div>
	        		</div>	
				</div>  
			<?php endif;?>
			
			<div class="reset marginbot"></div>
			
			<div class="tienda-expanded" id="comments-pane">
			<div class="tienda-collapse-processed contentheading"><?php echo JText::_('Order Comments')?></div>
			<div class='note'>
	    		<?php echo JText::_('Use this area for special instructions or questions regarding your order.');?>
        	</div>
		
			<div id="tienda_comments">	
				<textarea id="customer_note" name="customer_note" rows="5" cols="48"></textarea>		
			</div>		
			</div>  
			
			<div class="reset marginbot"></div>	
			<div class="tienda-expanded" id="shipping_terms-pane">
			 <?php 
	    		if( TiendaConfig::getInstance()->get('require_terms', '1') )
	    		{
	    			$terms_article = TiendaConfig::getInstance()->get('article_terms');
	    			$terms_link = JRoute::_('index.php?option=com_content&view=article&id='.$terms_article);
	    		?>
            	<div class="tienda-collapse-processed"><?php echo JText::_("Terms & Conditions"); ?></div>
				<div id="shipping_terms" >
					<br/>
					<input type="checkbox" name="shipping_terms" value="1" /> <a href="<?php echo $terms_link; ?>" target="_blank"><?php echo JText::_('Accept Terms & Conditions');?></a>
         			<br/>
            	</div>
				
        	<?php } ?>
			</div>
			<div id="validationmessage" style="padding-top: 10px;"></div> 
			<div id="tienda_btns">
				<input type="button" class="button" onclick="tiendaSaveOnepageOrder('tienda_checkout_pane', 'validationmessage', this.form)" value="<?php echo JText::_('Click Here to Continue'); ?>" />
				<div class="reset marginbot"></div>	
				<a href="<?php echo JRoute::_('index.php?option=com_tienda&view=carts'); ?>"><?php echo JText::_('Return to Shopping Cart'); ?></a> 
			</div>
		</div>
	</div> <?php // end col 3 ?>
</div> <?php // end floatbox ?>
</div>
<input type="hidden" id="currency_id" name="currency_id" value="<?php echo $this->order->currency_id; ?>" />
<input type="hidden" id="order_total" name="order_total" value="<?php echo $this->order->order_total; ?>" />
<input type="hidden" id="task" name="task" value="onepageSaveOrder" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<div id="refreshpage" style="display: none; text-align: right;"><a href="<?php echo JRoute::_('index.php?option=com_tienda&view=checkout')?>"><?php echo JText::_('Back')?></a></div>

<script type="text/javascript">
window.addEvent('domready', function() {
<?php if( @$this->billing_address->address_id ): ?>
	tiendaShowHideDiv( 'billing_input_addressForm' );
<?php endif; ?>

<?php if( !$this->user->id ) : ?>
	tiendaHideInfoCreateAccount();
<?php endif; ?>

<?php if( $this->showShipping  ):?>	

	<?php if( !$this->user->id ) : ?>
	tiendaHideShippingFields('<?php echo JText::_( "Getting Shipping Rates" ); ?>');
	<?php endif; ?>
	
	<?php if( @$this->shipping_address->address_id ): ?>
	tiendaShowHideDiv( 'shipping_input_addressForm' );
	<?php endif; ?>

<?php endif; ?>
});
</script>