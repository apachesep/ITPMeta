<?php
/**
 * @package      ITPrism Components
 * @subpackage   ITPMeta
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPMeta is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ITPMetaViewUrls extends JView {
    
    protected $items;
    protected $pagination;
    protected $state;
    
    public function display($tpl = null){
        
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        
        // Prepare filters
        $listOrder  = $this->escape($this->state->get('list.ordering'));
        $listDirn   = $this->escape($this->state->get('list.direction'));
        
        $this->listOrder = $listOrder;
        $this->listDirn  = $listDirn;
        
        // Prepare actions
        $this->addToolbar();
        $this->setDocument();
        
        parent::display($tpl);
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar(){
        
        // Set toolbar items for the page
        JToolBarHelper::addNew('url.add');
        JToolBarHelper::editList('url.edit');
        JToolBarHelper::divider();
        JToolBarHelper::title(JText::_('COM_ITPMETA_URL_MANAGER'), 'itp-urls');
        JToolBarHelper::publishList("urls.publish");
        JToolBarHelper::unpublishList("urls.unpublish");
        JToolBarHelper::divider();
        JToolBarHelper::deleteList(JText::_("COM_ITPMETA_DELETE_ITEMS_QUESTION"), "urls.delete");
        JToolBarHelper::divider();
        JToolBarHelper::custom('urls.backToControlPanel', "itp-properties-back", "", JText::_("COM_ITPMETA_CPANEL_TITLE"), false);
    }

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() {
		$this->document->setTitle(JText::_('COM_ITPMETA_URLS_MANAGER_TITLE'));
	}
	
}