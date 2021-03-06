<?php
/**
 * @package      ITPMeta
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class ItpmetaViewTag extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $state;
    protected $params;

    protected $item;
    protected $form;

    protected $documentTitle;
    protected $option;
    
    public function display($tpl = null)
    {
        $this->option = JFactory::getApplication()->input->get('option');
        
        $this->state = $this->get('State');
        $this->item  = $this->get('Item');
        $this->form  = $this->get('Form');

        $this->params = $this->state->get('params');

        // Prepare actions, behaviors, scritps and document
        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $isNew               = ((int)$this->item->id === 0);
        $this->documentTitle = $isNew ? JText::_('COM_ITPMETA_ADD_TAG') : JText::_('COM_ITPMETA_EDIT_TAG');

        JToolBarHelper::apply('tag.apply');
        JToolBarHelper::save2new('tag.save2new');
        JToolBarHelper::save('tag.save');
        JToolBarHelper::divider();

        if (!$isNew) {
            JToolBarHelper::cancel('tag.cancel', 'JTOOLBAR_CANCEL');
            JToolBarHelper::title($this->documentTitle);
        } else {
            JToolBarHelper::cancel('tag.cancel', 'JTOOLBAR_CLOSE');
            JToolBarHelper::title($this->documentTitle);
        }
    }

    protected function setDocument()
    {
        // Add scripts
        JHtml::_('bootstrap.tooltip');
        JHtml::_('behavior.formvalidation');
        JHtml::_('formbehavior.chosen', 'select');

        $this->document->addScript('../media/' . $this->option . '/js/admin/utilities.js');
        $this->document->addScript('../media/' . $this->option . '/js/admin/tag_form.js');
        $this->document->addScript('../media/' . $this->option . '/js/admin/' . $this->getName() . '.js');
    }
}
