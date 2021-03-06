<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $currency = Tienda::getInstance()->get( 'default_currencyid', 1); ?>

<form id="opc-shipping-method-form" name="opc-shipping-method-form" action="" method="post">
    
    <ul class="unstyled">
    <?php
    if (!empty($this->rates)) 
    {
        foreach ($this->rates as $key=>$rate) 
        {
            ?>
            <li class="control">
                <?php
                $checked = "";
                if ( !empty($this->default_rate) && $this->default_rate['name'] == $rate['name'] )
                {
                	$checked = "checked";                        
                }        	        		
                ?>
                <label for="shipping_<?php echo $rate['element'] . "_" . $key; ?>" class="radio">
                    <input class="shipping-plugin" id="shipping_<?php echo $rate['element'] . "_" . $key; ?>" name="shipping_plugin" rel="<?php echo $rate['name']; ?>" type="radio" value="<?php echo $rate['element'] . "." . $key ?>" <?php echo $checked; ?> />
                    <?php echo $rate['name']; ?> ( <?php echo TiendaHelperBase::currency( $rate['total'], $currency ); ?> )
                </label>
            </li>
            <?php
        }
    }
        else
    {
        ?>
        <li id="opc-no-shipping-rates" class="control">
            <p class="text-error">
            <?php echo JText::_('COM_TIENDA_NO_SHIPPING_RATES_FOUND'); ?>
            </p>
        </li>
        <?php
    }
    ?>
    </ul>
    
    <div id="opc-shipping-method-validation"></div>
    
    <a id="opc-shipping-method-button" class="btn btn-primary"><?php echo JText::_('COM_TIENDA_CONTINUE') ?></a>

</form>