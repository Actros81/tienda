<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Tienda::load( 'TiendaModelBase', 'models._base' );

class TiendaModelProductPrices extends TiendaModelBase
{
    protected function _buildQueryWhere(&$query)
    {
        $filter_id	        = $this->getState('filter_id');
        $filter_quantity	= $this->getState('filter_quantity');
       	$filter_user_group	= $this->getState('filter_user_group');       	
        $filter_date		= $this->getState('filter_date');
        
		if (strlen($filter_id))
        {
            $query->where('tbl.product_id = '.(int) $filter_id);
       	}
    	if (strlen($filter_user_group))
        {
            $query->where('tbl.user_group_id = '.(int) $filter_user_group);
       	}
    	if (strlen($filter_quantity))
        {
        	$query->where("(
        		(tbl.price_quantity_start <= '".(int) $filter_quantity."' AND tbl.price_quantity_end >= '".(int) $filter_quantity."')
        		OR 
        		(tbl.price_quantity_end = '0' AND tbl.price_quantity_start <= '".(int) $filter_quantity."' )
			)");
       	}
        if (strlen($filter_date))
        {
        	$nullDate	= JFactory::getDBO()->getNullDate();
        	$query->where("tbl.product_price_startdate <= '".$filter_date."'");
  			$query->where("(tbl.product_price_enddate >= '".$filter_date."' OR tbl.product_price_enddate = '$nullDate' )");
       	}
    }
        	
	public function getList()
	{
		$nullDate = JFactory::getDBO()->getNullDate();
		$list = parent::getList(); 
		
		// If no item in the list, return an array()
        if( empty( $list ) ){
        	return array();
        }
		
		foreach($list as $item)
		{
			$item->link_remove = 'index.php?option=com_tienda&controller=productprices&task=delete&cid[]='.$item->product_price_id;
			// convert working dates to localtime for display
			$item->product_price_startdate = ($item->product_price_startdate != $nullDate) ? JHTML::_( "date", $item->product_price_startdate, '%Y-%m-%d %H:%M:%S' ) : $item->product_price_startdate;
			$item->product_price_enddate = ($item->product_price_enddate != $nullDate) ? JHTML::_( "date", $item->product_price_enddate, '%Y-%m-%d %H:%M:%S' ) : $item->product_price_enddate;
		}
		return $list;
	}
	
    /**
     * Gets an item for displaying (as opposed to saving, which requires a JTable object)
     * using the query from the model
     *
     * @return database->loadObject() record
     */
    public function getItem()
    {
        if (empty( $this->_item ))
        {
            $query = $this->getQuery();
            $this->_db->setQuery( (string) $query );
            $this->_item = $this->_db->loadObject();
            if (is_object($this->_item))
            {
                $nullDate = JFactory::getDBO()->getNullDate();
                // convert working dates to localtime for display
                $this->_item->product_price_startdate = ($this->_item->product_price_startdate != $nullDate) ? JHTML::_( "date", $this->_item->product_price_startdate, '%Y-%m-%d %H:%M:%S' ) : $this->_item->product_price_startdate;
                $this->_item->product_price_enddate = ($this->_item->product_price_enddate != $nullDate) ? JHTML::_( "date", $this->_item->product_price_enddate, '%Y-%m-%d %H:%M:%S' ) : $this->_item->product_price_enddate;
            }
        }
        return $this->_item;
    }
}
