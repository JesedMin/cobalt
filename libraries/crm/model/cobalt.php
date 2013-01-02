<?php
/*------------------------------------------------------------------------
# Cobalt
# ------------------------------------------------------------------------
# @author Cobalt
# @copyright Copyright (C) 2012 cobaltcrm.org All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.cobaltcrm.org
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class CobaltModelCobalt extends JModelBase
{

	var $view = null;
	var $_model = null;

	/**
	 * 
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		$app = JFactory::getApplication();
		parent::__construct();
		$this->getListLimits();
		$this->view = $app->input->get('view');
		
	}

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 *
	 * @return  mixed
	 *
	 * @since   11.1
	 */
	public function saveorder($pks = null, $order = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$data = $app->input->getRequest('post');
		$table = JTable::getInstance($data['view'],'Table');
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
		}

		// update ordering values
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);
			if ($table->ordering != $order[$i])
			{
				$table->ordering = $order[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}

				// Remember to reorder within position and client_id
				$condition = $this->getReorderConditions($table);
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		return true;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table  A JTable object.
	 *
	 * @return  array  An array of conditions to add to ordering queries.
	 *
	 * @since   11.1
	 */
	protected function getReorderConditions($table)
	{
		return array();
	}

	 /**
     * Method to get a JPagination object for the data set.
     *
     * @return  JPagination  A JPagination object for the data set.
     *
     * @since   11.1
     */
    public function getPagination()
    {
        // Get a storage key.
        $store = $this->getStoreId('getPagination');

        // Try to load the data from internal storage.
        if (isset($this->cache[$store]))
        {
            return $this->cache[$store];
        }

        // Create the pagination object.
        jimport('joomla.html.pagination');
        $page = new JPagination($this->getTotal(), $this->getState($this->view.'_limitstart'), $this->getState($this->view.'_limit'));

        // Add the object to the internal cache.
        $this->cache[$store] = $page;

        return $this->cache[$store];
    }

    public function getListLimits(){

    	$app = JFactory::getApplication();

    	// Get pagination request variables
        $limit = $app->getUserStateFromRequest($this->view.'_limit','limit',10);
        $limitstart = $app->getUserStateFromRequest($this->view.'_limitstart','limitstart',0);
        
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $state = new JRegistry();
 
        $state->set($this->view.'_limit', $limit);
        $state->set($this->view.'_limitstart', $limitstart);

        $this->setState($state);
    }

    function getTotal(){
		if ( empty ( $this->_total ) ){
          $query = $this->__buildQuery();
          $this->_total = $this->_getListCount($query);
          $this->_total = $this->_total ? $this->_total : 0;
      	}
      	return $this->_total;
    }

    /**
	 * Method to adjust the ordering of a row.
	 *
	 * Returns NULL if the user did not have edit
	 * privileges for any of the selected primary keys.
	 *
	 * @param   integer  $pks    The ID of the primary key to move.
	 * @param   integer  $delta  Increment, usually +1 or -1
	 *
	 * @return  mixed  False on failure or error, true on success, null if the $pk is empty (no items selected).
	 *
	 * @since   11.1
	 */
	public function reorder($pks, $delta = 0)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$data = $app->input->getRequest('post');
		$table = JTable::getInstance($data['view'],'Table');
		$pks = (array) $pks;
		$result = true;

		$allowed = true;

		foreach ($pks as $i => $pk)
		{
			$table->reset();

			if ($table->load($pk))
			{

				$where = array();
				$where = $this->getReorderConditions($table);

				if (!$table->move($delta, $where))
				{
					$this->setError($table->getError());
					unset($pks[$i]);
					$result = false;
				}

			}
			else
			{
				$this->setError($table->getError());
				unset($pks[$i]);
				$result = false;
			}
		}

		if ($allowed === false && empty($pks))
		{
			$result = null;
		}

		return $result;
	}

}