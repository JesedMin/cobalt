<?php
/*------------------------------------------------------------------------
# Cobalt
# ------------------------------------------------------------------------
# @author Cobalt
# @copyright Copyright (C) 2012 cobaltcrm.org All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.cobaltcrm.org
-------------------------------------------------------------------------*/

namespace Cobalt\Model;

use Cobalt\Helper\DateHelper;
use Cobalt\Helper\UsersHelper;
use Cobalt\Helper\ActivityHelper;
use Cobalt\Helper\CobaltHelper;
use Cobalt\Table\ConversationTable;

// no direct access
defined( '_CEXEC' ) or die( 'Restricted access' );

class Conversation extends DefaultModel
{
    public $published = 1;
    public $deal_id = null;

    /**
     * Method to store a record
     *
     * @return boolean True on success
     */
    public function store()
    {
        $app = \Cobalt\Container::get('app');

        //Load Tables
        $row = new ConversationTable;
        $oldRow = new ConversationTable;
        $data = $app->input->getRequest( 'post' );

        //date generation
        $date = DateHelper::formatDBDate(date('Y-m-d H:i:s'));

        if ( !array_key_exists('id',$data) ) {
            $data['created'] = $date;
            $status = "created";
        } else {
            $row->load($data['id']);
            $oldRow->load($data['id']);
            $status = "updated";
        }

        $data['modified'] = $date;
        $data['author'] = UsersHelper::getUserId();

        // Bind the form fields to the table
        if (!$row->bind($data)) {
            $this->setError($this->db->getErrorMsg());

            return false;
        }

        // Make sure the record is valid
        if (!$row->check()) {
            $this->setError($this->db->getErrorMsg());

            return false;
        }

        // Store the web link table to the database
        if (!$row->store()) {
            $this->setError($this->db->getErrorMsg());

            return false;
        }

        $id = array_key_exists('id',$data) ? $data['id'] : $this->db->insertId();

        ActivityHelper::saveActivity($oldRow, $row, 'conversation', $status);

        return $id;
    }

    public function getConversations()
    {
        $query = $this->db->getQuery(true)
            ->select("c.*, u.first_name as owner_first_name, u.last_name as owner_last_name, author.email")
            ->from("#__conversations AS c")
            ->leftJoin("#__users as u on u.id = c.author")
            ->leftJoin("#__users AS author ON author.id = u.id")
            ->where("c.deal_id=".$this->deal_id)
            ->where("c.published>0")
            ->order("c.modified DESC");

        $conversations = $this->db->setQuery($query)->loadAssocList();

        for ($i=0;$i<count($conversations);$i++) {
            $conversations[$i]['owner_avatar'] = CobaltHelper::getGravatar($conversations[$i]['email']);
        }

        return $conversations;
    }

    /*
     * Method to access conversations
     *
     * @return array
     */
    public function getConversation($id)
    {
        //initialize query
        $query = $this->db->getQuery(true)
            ->select("c.*, u.first_name as owner_first_name, u.last_name as owner_last_name,author.email")
            ->from("#__conversations as c")
            ->where("c.id=".(int) $id)
            ->where("c.published=".$this->published)
            ->leftJoin("#__users AS u ON u.id = c.author")
            ->leftJoin("#__users AS author on author.id=u.id");

        $results = $db->setQuery($query)->loadAssocList();

        //clean results
        if ( count($results) > 0 ) {
            foreach ($results as $key => $convo) {
                $results[$key]['created_formatted'] = DateHelper::formatDate($convo['created']);
                $results[$key]['owner_avatar'] = CobaltHelper::getGravatar($convo['email']);
            }
        }

        return $results;
    }

}
