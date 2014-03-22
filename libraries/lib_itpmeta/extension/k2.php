<?php
/**
 * @package      ITPMeta
 * @subpackage   Libraries
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("itpmeta.extension");

/**
 * This helper provides functionality for K2 (com_k2).
 */
class ItpMetaExtensionK2 extends ItpMetaExtension {

    protected $db;
    
    protected $uri;
    protected $view;
    protected $task;
    protected $menuItemId;
    
    protected $data;
    
    public function getData() {
        
        $app        = JFactory::getApplication();
        /** @var $app JSite **/
        
        // Parse the URL
        $router     = $app->getRouter();
        $parsed     = $router->parse($this->uri);
        
        $id         = JArrayHelper::getValue($parsed, "id", 0, "int");
        $task       = JArrayHelper::getValue($parsed, "task");
        
        // I am using $view because I could change it to "tag".
        // So, I don't want to replace the original property.
        $view       = $this->view;
        
        switch($task) {
            
            case "user":
            case "tag":
                $view = $task;
                break;
                
            case "category":
                $view = $task;
                break;
        }
        
        switch($view) {
            
            case "item":
                $data = $this->getItemData($id);
                break;
                
            case "category":
                $data = $this->getCategoryData($id);
                break;

            case "tag":
                $data = $this->prepareTagData($parsed);
                break;
                
            case "user":
                $data = $this->getUserData($parsed);
                break;
                
            default: // Get menu item
                if(!empty($this->menuItemId)) {
                    $data = $this->getDataByMenuItem($this->menuItemId);
                }
                
                break;
                
        } 
        
        return $data;
    }
    
    protected function prepareTagData($parsed) {
        
        $tagName   = JArrayHelper::getValue($parsed, "tag");
        $tagName   = htmlentities($tagName, ENT_QUOTES, 'UTF-8');
        
        // Get menu item data.
        $data      = $this->getDataByMenuItem($this->menuItemId);
        
        // Get menu item.
        $app       = JFactory::getApplication();
        $menu      = $app->getMenu();
        $menuItem  = $menu->getItem($this->menuItemId);
        
        // Get layout of current menu item.
        $layout    = JArrayHelper::getValue($menuItem->query, "layout");
        
        if(strcmp("tag", $layout) == 0) { // If it is a menu item of layout "tag".
            
            $title    = JString::trim(JArrayHelper::getValue($data, "title"));
            $metaDesc = JString::trim(JArrayHelper::getValue($data, "metadesc"));
            
            if(!$title) {
                $title = JText::sprintf("LIB_ITPMETA_DISPLAYING_TAG", $tagName);
            }
            
            if(!$metaDesc) {
                $metaDesc = JText::sprintf("LIB_ITPMETA_DISPLAYING_TAG_DESC", $tagName);
            }
            
        } else { // If it is not a menu item.
            
            $title    = JText::sprintf("LIB_ITPMETA_DISPLAYING_TAG", $tagName);
            $metaDesc = JText::sprintf("LIB_ITPMETA_DISPLAYING_TAG_DESC", $tagName);
            
        }
        
        $data["title"]    = $title;
        $data["metadesc"] = $metaDesc;
        
        return $data;
    }
    
    protected function getUserData($parsed) {
        
        // Get menu item data.
        $data      = $this->getDataByMenuItem($this->menuItemId);
        
        // Get menu item.
        $app       = JFactory::getApplication();
        $menu      = $app->getMenu();
        $menuItem  = $menu->getItem($this->menuItemId);
        
        // Get layout of current menu item.
        $layout    = JArrayHelper::getValue($menuItem->query, "layout");
        $userId    = JArrayHelper::getValue($parsed, "id", 0, "int");
        
        $user      = $this->getUser($userId);
        
        // Prepare title and meta description.
        if(strcmp("user", $layout) == 0) { // If it is a menu item of layout "tag".
            $data["title"]    = JString::trim(JArrayHelper::getValue($data, "title"));
            $metaDesc = JString::trim(JArrayHelper::getValue($data, "metadesc"));
        }
        
        if(!$data["title"]) {
            $data["title"] = JText::sprintf("LIB_ITPMETA_VIEW_USER_TITLE", $user["name"]);
        }
        
        if(!$data["metadesc"]) {
        
            if(!empty($user["metadesc"])) {
                $data["metadesc"] = $user["metadesc"];
            } else {
                $data["metadesc"] = JText::sprintf("LIB_ITPMETA_VIEW_USER_METADESC", $user["name"]);
            }
        
        }
        
        $data["image"]  = $user["image"];
        
        unset($user);
        
        return $data;
    }
    
    protected function getUser($userId) {

        $data = array();
        
        $query  = $this->db->getQuery(true);
        
        $query
            ->select("a.userName AS name, a.description , a.image")
            ->from($this->db->quoteName("#__k2_users", "a"))
            ->where("a.userID = ".(int)$userId);
            
        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();
        
        if(!empty($result)) {
            
            $data["created"]  = null;
            $data["modified"] = null;
            
            $data["name"]     = $result["name"];
            
            // Prepare meta description.
            $data["metadesc"] = $this->prepareMetaDesc($result["description"]);
            
            $data["image"] = null;
            if(!empty($result["image"])) {
                $data["image"]    = "media/k2/users/".$result["image"];
            }
            
            unset($result);
        }
        
        return $data;
    }
    
	/**
     * Extract data about category.
     */
    public function getCategoryData($categoryId) {
        
        if(!$categoryId) {
            return null;
        }
        
        $data   = array();
        
        $query  = $this->db->getQuery(true);
        
        $query
            ->select("a.name AS title, a.description, a.params, a.image")
            ->from($this->db->quoteName("#__k2_categories", "a"))
            ->where("a.id=".(int)$categoryId);
            
        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();
        
        if(!empty($result)) {
            
            $data["created"]  = null;
            $data["modified"] = null;
            $data["title"]    = $result["title"];
            
            // Prepare meta description.
            $params           = json_decode($result["params"], true);
            $data["metadesc"] = JArrayHelper::getValue($params, "catMetaDesc");
            
            if(!$data["metadesc"] AND !empty($this->genMetaDesc)) {
                $data["metadesc"] = $this->prepareMetaDesc($result["description"]);
            }
            
            if(!empty($result["image"])) {
                $data["image"]    = "media/k2/categories/".$result["image"];
            }
            
            unset($result);
        }
        
        return $data;
        
    }
    
	/**
     * Extract data about item
     */
    public function getItemData($itemId) {
        
        if(!$itemId) {
            return null;
        }
        
        $data     = array();
        
        $query  = $this->db->getQuery(true);
        
        $query
            ->select("a.title, a.introtext, a.fulltext, a.metadesc, a.created, a.modified")
            ->from($this->db->quoteName("#__k2_items", "a"))
            ->where("a.id=".(int)$itemId);
            
        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();
        
        if(!empty($result)) {

            $data["title"]    = $result["title"];
            $data["created"]  = $result["created"];
            $data["modified"] = $result["modified"];
            $data["metadesc"] = $result["metadesc"];
            
            $data["image"] = "";
            $image         = "media/k2/items/cache/".md5("Image".$itemId)."_M.jpg";
            
            jimport("joomla.filesystem.file");
            if(JFile::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.$image)) {
                $data["image"] = $image;
            }
            
            if(!$data["metadesc"] AND !empty($this->genMetaDesc)) {
                
                $data["metadesc"] = $this->prepareMetaDesc($result["introtext"]);
                
                if(!$data["metadesc"]) {
                    $data["metadesc"] = $this->prepareMetaDesc($result["fulltext"]);
                }
            }
            
            unset($result);
        }
        
        return $data;
    }
    
}