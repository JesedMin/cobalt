<?php
/*------------------------------------------------------------------------
# Cobalt
# ------------------------------------------------------------------------
# @author Cobalt
# @copyright Copyright (C) 2012 cobaltcrm.org All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.cobaltcrm.org
-------------------------------------------------------------------------*/

namespace Cobalt\View\Calendar;

use JUri;
use JFactory;
use Cobalt\Model\Event as EventModel;
use Cobalt\Helper\UsersHelper;
use Joomla\View\AbstractHtmlView;

// no direct access
defined( '_CEXEC' ) or die( 'Restricted access' );

class Html extends AbstractHtmlView
{
    public function render()
    {
        //load model and retrieve events to pass to calendar
        $model = new EventModel;
        $events = $model->getEvents('calendar');

        //load js libs
        $document = JFactory::getDocument();
        $document->addScript( JURI::base().'src/Cobalt/media/js/fullcalendar.js' );
        $document->addScript( JURI::base().'src/Cobalt/media/js/calendar_manager.js' );

        //load required css for calendar
        $document->addStyleSheet( JURI::base().'src/Cobalt/media/css/fullcalendar.css' );

        //pass reference vars to view
        $this->events = json_encode($events);
        $team_members = UsersHelper::getUsers();
        $this->team_members = $team_members;

        //display
        return parent::render();
    }

}
