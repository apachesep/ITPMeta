<?php
/**
 * @package      ITPMeta
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

jimport("itprism.controller.admin");

/**
 * ITPMeta URLs Controller
 *
 * @package     ITPrism Components
 * @subpackage  ITPMeta
 */
class ItpMetaControllerUrls extends ITPrismControllerAdmin
{

    public function __construct($config = array())
    {
        parent::__construct($config);

        // Define task mappings.

        // Value = 0
        $this->registerTask('disableau', 'enableau');

    }

    /**
     * Proxy for getModel.
     * @since   1.6
     */
    public function getModel($name = 'Url', $prefix = 'ItpMetaModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    /**
     * Remove items.
     *
     * @throws  Exception
     * @return  void
     * @since   11.1
     */
    public function delete()
    {
        $redurectOptions = array(
            "view" => "urls"
        );

        $cid       = $this->input->post->get("cid", array(), "array");
        $modelTags = $this->getModel("Tag");

        try {
            $modelTags->deleteByUrlId($cid);
            parent::delete();
        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_ITPMETA_ERROR_SYSTEM'));
        }

        $msg = JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid));
        $this->displayMessage($msg, $redurectOptions);

    }

    /**
     * Enable auto-update.
     *
     * @throws  Exception
     * @return  void
     */
    public function enableau()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $redurectOptions = array(
            "view" => "urls"
        );

        $cid = $this->input->post->get("cid", array(), "array");
        JArrayHelper::toInteger($cid);

        $data = array(
            'enableau'  => 1,
            'disableau' => 0
        );

        $task  = $this->getTask();
        $value = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid)) {
            $this->displayNotice(JText::_($this->text_prefix . '_ERROR_NO_ITEM_SELECTED'), $redurectOptions);

            return;
        }

        try {

            $model = $this->getModel();
            $model->updateAutoupdate($cid, $value);

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_ITPMETA_ERROR_SYSTEM'));
        }

        if ($value == 1) {
            $msg = $this->text_prefix . '_N_ITEMS_AUTOUPDATE_ENABLED';
        } else {
            $msg = $this->text_prefix . '_N_ITEMS_AUTOUPDATE_DISABLED';
        }

        $this->displayMessage(JText::plural($msg, count($cid)), $redurectOptions);
    }
}
