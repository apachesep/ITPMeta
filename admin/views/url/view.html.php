<?php
/**
 * @package      ITPMeta
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.html.pane');
jimport('joomla.application.component.view');

class ItpMetaViewUrl extends JViewLegacy {
    
    protected $state;
    protected $item;
    protected $form;
    
    protected $documentTitle;
    protected $option;
    
    public function __construct($config) {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }
    
    /**
     * Display the view
     */
    public function display($tpl = null){
        
        $app = JFactory::getApplication();
        /** @var $app JAdministrator **/
        
        $this->state   = $this->get('State');
        $this->item    = $this->get('Item');
        $this->form    = $this->get('Form');

        $this->params  = $this->state->get("params");
        
        // Get URL ID
        $this->itemId  = $this->state->get($this->getName().".id");
        $app->setUserState("url.id", $this->itemId);
        
        // Prepare actions, behaviors, scritps and document
        $this->addToolbar();
        $this->setDocument();
        
        // Prepare Tags List
        if(!empty($this->itemId)) {
            $modelTags    = JModelLegacy::getInstance("Tags", "ItpMetaModel", array('ignore_request' => false));
            $this->items  = $modelTags->getItems();
        
            $this->prepareSorting($modelTags);
            $this->prepareTagsList();
        
        }
        
        parent::display($tpl);
    }
    
    /**
     * Prepare sortable fields, sort values and filters.
     * 
     */
    protected function prepareSorting($modelTags) {
    
        $tagsState = $modelTags->getState();
        
        // Prepare filters
        $this->listOrder  = $this->escape($tagsState->get('list.ordering'));
        $this->listDirn   = $this->escape($tagsState->get('list.direction'));
        $this->saveOrder  = (strcmp($this->listOrder, 'a.ordering') != 0 ) ? false : true;
    
        if ($this->saveOrder) {
            $this->saveOrderingUrl = 'index.php?option='.$this->option.'&task=tags.saveOrderAjax&format=raw';
            JHtml::_('sortablelist.sortable', 'tagsList', 'tagsForm', strtolower($this->listDirn), $this->saveOrderingUrl);
        }
    
    }
    
    protected function prepareTagsList() {
        
        // Load language string in JavaScript
        JText::script('COM_ITPMETA_EDIT_CONTENT');
        JText::script('COM_ITPMETA_ERROR_MAKE_SELECTION');
        JText::script('COM_ITPMETA_DELETE_ITEMS_QUESTION');
        JText::script('COM_ITPMETA_INFO_DISABLE_AUTOUPDATE');
        JText::script('COM_ITPMETA_ADDITIONAL_INFORMATION');
        
        // Load HTML helper
        JHtml::addIncludePath(ITPRISM_PATH_LIBRARY.'/ui/helpers');
        JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'html');
        
        // Scripts
        JHTML::_('behavior.framework');
        JHtml::_('behavior.multiselect');
        
        JHtml::_('bootstrap.framework');
        JHtml::_('formbehavior.chosen', 'select');
        JHtml::_('bootstrap.tooltip');
        
        JHTML::_("itprism.ui.pnotify");
        JHTML::_("itprism.ui.bootstrap_editable");
        
        $this->document->addScript('../media/'.$this->option.'/js/admin/tags.js');
        
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar(){
        
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $isNew = ($this->item->id == 0);
        $this->documentTitle = $isNew  ? JText::_('COM_ITPMETA_ADD_URL')
                                       : JText::_('COM_ITPMETA_EDIT_URL');

        JToolBarHelper::apply('url.apply');
        JToolBarHelper::save2new('url.save2new');
        JToolBarHelper::save('url.save');
        JToolBarHelper::divider();
        
        if(!$isNew) {
            
            // Add custom buttons
    		$bar = JToolBar::getInstance('toolbar');
    		
    		// Go to script manager
    		$link = JRoute::_('index.php?option=com_itpmeta&view=scripts&layout=edit', false );
    		$bar->appendButton('Link', 'cog', JText::_("COM_ITPMETA_SCRIPTS"), $link);
    		
            JToolBarHelper::divider();
        }
        
        if(!$isNew){
            JToolBarHelper::cancel('url.cancel', 'JTOOLBAR_CANCEL');
            JToolBarHelper::title($this->documentTitle);
        }else{
            JToolBarHelper::cancel('url.cancel', 'JTOOLBAR_CLOSE');
            JToolBarHelper::title($this->documentTitle);
        }
        
    }

    protected function setDocument() {
        
        // Add styles
        $this->document->addStyleSheet('../media/'.$this->option.'/css/style.css');
        
        // Add scripts
        JHTML::_('behavior.framework');
        JHtml::_('behavior.tooltip');
        JHtml::_('behavior.formvalidation');
        
        JHtml::_('bootstrap.framework');
        JHtml::_('formbehavior.chosen', 'select');
        
        $this->document->addScript('../media/'.$this->option.'/js/admin/utilities.js');
        $this->document->addScript('../media/'.$this->option.'/js/admin/helper.js');
        $this->document->addScript('../media/'.$this->option.'/js/admin/'.$this->getName().'.js');
        
    }
    
}