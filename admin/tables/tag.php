<?php
/**
 * @package      ITPMeta
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class ItpmetaTableTag extends JTable
{
    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__itpm_tags', 'id', $db);
    }
}
