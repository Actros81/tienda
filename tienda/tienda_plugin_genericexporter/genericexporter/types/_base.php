<?php
/**
 * @package	Tienda
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class TiendaGenericExporterBase
{
	public $_model 	= '';
	public $_format	= 'csv';

	function getName()
	{
		return $this->_model;
	}
	
	/**	 
	 * Generic method to display the data from a model/query
	 * can be override in the child class
	 * @return array - array object
	 */
	function loadDataList()
	{	 
      	JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_tienda'.DS.'models');     
      	$model = JModel::getInstance($this->getName(),'TiendaModel');
      	$this->_setModelState($model);
    	$items = $model->getList($model);
    	
		return $items;
	}
	
	/**
	 * Method to set the model state
	 * can be override in the child class
	 * @param unknown_type $model
	 * @return array
	 */
	
	function _setModelState($model)
	{
		$app = JFactory::getApplication();		
		$ns = $app->getName().'::'.'com.tienda.model.'.$model->getTable()->get($this->getName());

		$state = array();
		$state['limit']  	= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$state['limitstart'] = $app->getUserStateFromRequest($ns.'limitstart', 'limitstart', 0, 'int');
		$state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.'.$model->getTable()->getKeyName(), 'cmd');
		$state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'ASC', 'word');
		$state['filter']    = $app->getUserStateFromRequest($ns.'.filter', 'filter', '', 'string');
		$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
	
		foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	/**
	 * Method to get the colums of the model/table
	 * can be override in the child class
	 * @return array
	 */
	function getColumns()
	{
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_tienda'.DS.'models');     
      	$model = JModel::getInstance($this->getName(),'TiendaModel');
      	$model->setState( 'limit', '1' );
      	$items = $model->getList();
      	
      	$columns = array();
      	if(count($items))
      	{
      		$columns = get_object_vars($items[0]);
      	}
      
      	return array_keys($columns);
	}
	
	function doExport()
	{
		$this->loadDataList();
	
	}
}