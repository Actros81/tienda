<?php defined('_JEXEC') or die('Restricted access');?>
<?php JHTML::_( 'behavior.modal' ); ?> 
<div class="table">
	<div class="row">
		<div class="cell step_body inactive">
			<?php echo $this->step1_inactive;?>

			<div class="go_back">
				<a href="index.php?option=com_tienda&view=pos">
				<?php echo JText::_("Go Back");?>
				</a>
			</div>
		</div>
		<div class="cell step_title inactive">
			<h2>
			<?php echo JText::_("POS_STEP1_SELECT_USER");?>
			</h2>
		</div>
	</div>
	<div class="row">
		<div class="cell step_body inactive">
			<div class="go_back">
				<a href="index.php?option=com_tienda&view=pos&nextstep=step2">
				<?php echo JText::_("Go Back");?>
				</a>
			</div>
			<div id="orderSummary">
				<?php echo $this->orderSummary;?>
			</div>
		</div>
		<div class="cell step_title inactive">
			<h2>
			<?php echo JText::_("POS_STEP2_SELECT_PRODUCTS");?>
			</h2>
		</div>
	</div>
	<div class="row">
		<div class="cell step_body inactive">
			<div id="validation_message">
			</div>
			<div id="addresses">
				<?php if($this->showShipping):?>
				<div>
					<h4 id='shipping_address_header' class="address_header">
					<?php echo JText::_("Shipping Information") ?>
					</h4>
				</div>
				<div class="reset">
				</div>
				<?php endif;?>
			</div>
		</div>
		<div class="cell step_title inactive">
			<h2>
			<?php echo JText::_("POS_STEP3_SELECT_PAYMENT_SHIPPING_METHODS");?>
			</h2>
		</div>
	</div>
	<div class="row">
		<div class="cell step_body active">
			<?php if (!empty($this->showBilling)) { ?>
			<div id="payment_info" class="address">
				<h3>
				<?php echo JText::_("Billing Information");?>
				</h3>
				<strong>
				<?php echo JText::_("Billing Address");?>
				</strong>:
				<br/>
				<?php
				echo $this->billing_info->first_name . " " . $this->billing_info->last_name . "<br/>";
				echo $this->billing_info->address_1 . ", ";
				echo $this->billing_info->address_2 ? $this->billing_info->address_2 . ", " : "";
				echo $this->billing_info->city . ", ";
				echo $this->billing_info->zone_name . " ";
				echo $this->billing_info->postal_code . " ";
				echo $this->billing_info->country_name;
				?>
			</div>
			<?php }?>

			<?php if (!empty($this->showShipping)) { ?>
			<div id="shipping_info" class="address">
				<h3>
				<?php echo JText::_("Shipping Information");?>
				</h3>
				<strong>
				<?php echo JText::_("Shipping Method");?>
				</strong>: <?php echo JText::_($this->shipping_method_name);?>
				<br/>
				<strong>
				<?php echo JText::_("Shipping Address");?>
				</strong>:
				<br/>
				<?php
				echo $this->shipping_info->first_name . " " . $this->shipping_info->last_name . "<br/>";
				echo $this->shipping_info->address_1 . ", ";
				echo $this->shipping_info->address_2 ? $this->shipping_info->address_2 . ", " : "";
				echo $this->shipping_info->city . ", ";
				echo $this->shipping_info->zone_name . " ";
				echo $this->shipping_info->postal_code . " ";
				echo $this->shipping_info->country_name;
				?>
			</div>
			<div class="reset">
			</div>
			
			<a id="modalWindowPayment" rel="{handler:'iframe',size:{x: window.innerWidth-80, y: window.innerHeight-80}, onShow:$('sbox-window').setStyles({'padding': 0})}" href="<?php echo JURI::root();?>/index.php?option=com_tienda&amp;controller=checkout&amp;task=poscheckout&amp;orderid=<?php echo $this->order->order_id;?>&amp;userid=<?php echo $this->session->get('user_id', '', 'tienda_pos'); ?>&amp;tmpl=component" class="modal">
				<span>
				TESTPOPUP
				</span>
			</a>
			
			<?php if(!empty($this->order->customer_note)):?>
			<div id="shipping_comments">
				<h3>
				<?php echo JText::_("Shipping Notes");?>
				</h3>
				<?php echo $this->order->customer_note;?>
			</div>
			<?php endif; ?>
			<?php }?>
		    <div class="reset"></div>
		        
		    <!--    PAYMENT METHOD   -->
		    <h3><?php echo JText::_("Payment Method"); ?></h3>
		
			<?php echo $this->plugin_html; ?>

			<div class="continue">
				<?php $onclick = "tiendaValidation( '" . $this->validation_url . "', 'validation_message', 'saveStep4', document.adminForm, true, '" . JText::_('Validating') . "' );";?>
				<input onclick="<?php echo $onclick;?>" value="<?php echo JText::_('Continue');?>" type="button" class="button" />
			</div>
		</div>
		<div class="cell step_title active">
			<h2>
			<?php echo JText::_("POS_STEP4_REVIEW_SUBMIT_ORDER");?>
			</h2>
		</div>
	</div>
	<div class="row">
		<div class="cell step_body inactive">
		</div>
		<div class="cell step_title inactive">
			<h2>
			<?php echo JText::_("POS_STEP5_PAYMENT_CONFIRMATION");?>
			</h2>
		</div>
	</div>
</div>
<input type="hidden" name="nextstep" id="nextstep" value="step5" />

<script type="text/javascript" >
	 window.addEvent('domready', function() {	 	
	 	window.addEvent('load', function(){
	 		 SqueezeBox.fromElement($('modalWindowPayment'));
	 	});	 
	 });
</script>