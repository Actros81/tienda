<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this -> form; ?>
<?php $row = @$this -> row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data">

    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="group" value="<?php echo $this->getLayout(); ?>" /> 
    </div>
    
    <div class="tabbable">
    
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab1" data-toggle="tab"><?php echo JText::_('COM_TIENDA_GENERAL_SETTINGS'); ?></a>
            </li>
            <li>
                <a href="#tab2" data-toggle="tab"><?php echo JText::_('COM_TIENDA_SOCIAL_SETTINGS'); ?></a>
            </li>
            <li>
                <a href="#tab3" data-toggle="tab"><?php echo JText::_('COM_TIENDA_IMAGES_SETTINGS'); ?></a>
            </li>
            <li>
                <a href="#tab4" data-toggle="tab"><?php echo JText::_('COM_TIENDA_ADVANCED_SETTINGS'); ?></a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <?php $this->setLayout( 'displaysettings_general' ); echo $this->loadTemplate(); ?>
            </div>
            
            <div class="tab-pane" id="tab2">
                <?php $this->setLayout( 'displaysettings_social' ); echo $this->loadTemplate(); ?>
            </div>
            
            <div class="tab-pane" id="tab3">
                <?php $this->setLayout( 'displaysettings_images' ); echo $this->loadTemplate(); ?>
            </div>
            
            <div class="tab-pane" id="tab4">
                <?php $this->setLayout( 'displaysettings_advanced' ); echo $this->loadTemplate(); ?>
            </div>
        </div>
    </div>
    
</form>
