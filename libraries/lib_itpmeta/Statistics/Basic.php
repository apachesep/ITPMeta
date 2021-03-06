<?php
/**
 * @package      Itpmeta
 * @subpackage   Statistics
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Itpmeta\Statistics;

defined('JPATH_PLATFORM') or die;

/**
 * This class generates basic statistics.
 *
 * @package      Itpmeta
 * @subpackage   Statistics
 */
class Basic
{
    /**
     * Database driver
     *
     * @var \JDatabaseDriver
     */
    protected $db;

    /**
     * Initialize the object.
     *
     * <code>
     * $statistics   = new Itpmeta\Statistics\Basic(\JFactory::getDbo());
     * </code>
     *
     * @param \JDatabaseDriver $db
     */
    public function __construct(\JDatabaseDriver $db)
    {
        $this->db = $db;
    }

    /**
     * This method returns a number of all urls.
     *
     * <code>
     * $statistics   = new Itpmeta\Statistics\Basic(\JFactory::getDbo());
     * echo $statistics->getTotalUrls();
     * </code>
     *
     * @throws \RuntimeException
     * @return int
     */
    public function getTotalUrls()
    {
        $query = $this->db->getQuery(true);

        $query
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__itpm_urls', 'a'));

        $this->db->setQuery($query);

        return (int)$this->db->loadResult();
    }

    /**
     * This method returns a number of all tags.
     *
     * <code>
     * $statistics   = new Itpmeta\Statistics\Basic(\JFactory::getDbo());
     * echo $statistics->getTotalTags();
     * </code>
     *
     * @throws \RuntimeException
     * @return int
     */
    public function getTotalTags()
    {
        $query = $this->db->getQuery(true);

        $query
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__itpm_tags', 'a'));

        $this->db->setQuery($query);

        return (int)$this->db->loadResult();
    }

    /**
     * This method returns a number of all global tags.
     *
     * <code>
     * $statistics   = new Itpmeta\Statistics\Basic(\JFactory::getDbo());
     * echo $statistics->getTotalGlobalTags();
     * </code>
     *
     * @throws \RuntimeException
     * @return int
     */
    public function getTotalGlobalTags()
    {
        $query = $this->db->getQuery(true);

        $query
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__itpm_global_tags', 'a'));

        $this->db->setQuery($query);

        return (int)$this->db->loadResult();
    }
}
