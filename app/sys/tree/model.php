<?php
/**
 * The model file of tree module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: model.php 4169 2016-10-19 08:57:15Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<?php
class treeModel extends model
{
    /**
     * Get category info by id.
     * 
     * @param  int|string  $categoryID 
     * @param  string      $type 
     * @access public
     * @return bool|object
     */
    public function getByID($categoryID, $type = 'article')
    {
        $category = $this->dao->select('*')->from(TABLE_CATEGORY)->where('id')->eq($categoryID)->fetch();
        if(!$category) $category = $this->dao->select('*')->from(TABLE_CATEGORY)->where('alias')->eq($categoryID)->beginIF($type)->andWhere('type')->eq($type)->fi()->fetch();
        if(!$category) return false;

        if($category->type == 'forum') 
        {
            $speakers = array();
            $category->moderators = explode(',', trim($category->moderators, ','));
            foreach($category->moderators as $moderators) $speakers[] = $moderators;
            $speakers = $this->loadModel('user')->getRealNamePairs($speakers);
            foreach($category->moderators as $key => $moderators) 
            {
                unset($category->moderators[$key]);
                $category->moderators[$moderators] = isset($speakers[$moderators]) ? $speakers[$moderators] : '';
            }
        }

        $category->pathNames = $this->dao->select('id, name')->from(TABLE_CATEGORY)->where('id')->in($category->path)->orderBy('grade')->fetchPairs();
        return $category;
    }

    /**
     * Get the first category.
     * 
     * @param  string $type 
     * @param  int    $root 
     * @access public
     * @return object|bool
     */
    public function getFirst($type = 'article', $root = 0)
    {
        return $this->dao->select('*')->from(TABLE_CATEGORY)
            ->where('type')->eq($type)
            ->beginIF($root)->andWhere('root')->eq((int)$root)->fi()
            ->orderBy('id')->limit(1)->fetch();
    }

    /**
     * Get the id => name pairs of some categories.
     * 
     * @param  string $categories   the category lists
     * @param  string $type         the type
     * @access public
     * @return array
     */
    public function getPairs($categories = '', $type = 'article')
    {
        $categories = $this->dao->select('*')->from(TABLE_CATEGORY)
            ->where(1)
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->fetchAll('id');

        $categoryPairs = array();
        $categories    = $this->process($categories, $type);
        foreach($categories as $id => $category)
        {
            $categoryPairs[$id] = $category->name; 
        }

        return $categoryPairs;
    }

    /**
     * Get list of one type.
     * 
     * @param  string $type 
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getListByType($type = 'article', $orderBy = 'id_asc')
    {
        return $this->dao->select('*')->from(TABLE_CATEGORY)->where('type')->eq($type)->orderBy($orderBy)->fetchAll('id');
    }

    /**
     * Get origin of a category.
     * 
     * @param  int     $categoryID 
     * @access public
     * @return array
     */
    public function getOrigin($categoryID)
    {
        if($categoryID == 0) return array();

        $path = $this->dao->select('path')->from(TABLE_CATEGORY)->where('id')->eq((int)$categoryID)->fetch('path');
        $path = trim($path, ',');
        if(!$path) return array();

        return $this->dao->select('*')->from(TABLE_CATEGORY)->where('id')->in($path)->orderBy('grade')->fetchAll('id');
    }

    /**
     * Get id list of a family.
     * 
     * @param  int      $categoryID 
     * @param  string   $type 
     * @param  int      $root 
     * @access public
     * @return array
     */
    public function getFamily($categoryID, $type = '', $root = 0)
    {
        if($categoryID == 0 and empty($type)) return array();
        $category = $this->getById($categoryID);

        if($category)  return $this->dao->select('id')->from(TABLE_CATEGORY)->where('path')->like($category->path . '%')->fetchPairs();
        if(!$category)
        {
            return $this->dao->select('id')->from(TABLE_CATEGORY)
                ->where('type')->eq($type)
                ->beginIF($root)->andWhere('root')->eq((int)$root)->fi()
                ->fetchPairs();
        }
    }

    /**
     * Get children categories of one category.
     * 
     * @param  int      $categoryID 
     * @param  string   $type 
     * @param  int      $root 
     * @access public
     * @return array
     */
    public function getChildren($categoryID, $type = 'article', $root = 0)
    {
        $categories = $this->dao->select('*')->from(TABLE_CATEGORY)
            ->where('parent')->eq((int)$categoryID)
            ->andWhere('type')->eq($type)
            ->beginIF($root)->andWhere('root')->eq((int)$root)->fi()
            ->orderBy('`order`')
            ->fetchAll('id');

        return $this->process($categories, $type);
    }

    /**
     * Get id list of a module's childs.
     * 
     * @param  int     $moduleID 
     * @access public
     * @return array
     */
    public function getAllChildId($moduleID)
    {
        if($moduleID == 0) return array();

        $module = $this->getById((int)$moduleID);
        if(empty($module)) return array();

        return $this->dao->select('id')->from(TABLE_CATEGORY)->where('path')->like($module->path . '%')->fetchPairs();
    }

    /**
     * Get departments managed by me.
     * 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function getDeptManagedByMe($account)
    {
        return $this->dao->select('*')->from(TABLE_CATEGORY)
            ->where('moderators')->eq(",$account,")
            ->andWhere('type')->eq('dept')
            ->fetchAll('id');
    }

    /**
     * Build the sql to execute.
     * 
     * @param string $type              the tree type, for example, article|forum
     * @param int    $startCategory     the start category id
     * @param int    $root
     * @access public
     * @return string
     */
    public function buildQuery($type, $startCategory = 0, $root = 0)
    {
        /* Get the start category path according the $startCategory. */
        $startPath = '';
        if($startCategory > 0)
        {
            $startCategory = $this->getById($startCategory);
            if($startCategory) $startPath = $startCategory->path . '%';
        }

        return $this->dao->select('*')->from(TABLE_CATEGORY)
            ->where('type')->eq($type)
            ->beginIF($root)->andWhere('root')->eq((int)$root)->fi()
            ->beginIF($startPath)->andWhere('path')->like($startPath)->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Create a tree menu in <select> tag.
     * 
     * @param  string $type 
     * @param  int    $startCategory 
     * @param  bool   $removeRoot 
     * @param  int    $root 
     * @access public
     * @return string
     */
    public function getOptionMenu($type = 'article', $startCategory = 0, $removeRoot = false, $root = 0)
    {
        /* First, get all categories. */
        $treeMenu   = array();
        $lastMenu   = array();
        $stmt       = $this->dbh->query($this->buildQuery($type, $startCategory, $root));
        $categories = array();
        while($category = $stmt->fetch())
        {
            $categories[$category->id] = $category;
        }
        $categories = $this->process($categories, $type);

        /* Cycle them, build the select control.  */
        foreach($categories as $category)
        {
            $origins = explode(',', $category->path);
            $categoryName = '/';
            foreach($origins as $origin)
            {
                if(empty($origin)) continue;
                $categoryName .= $categories[$origin]->name . '/';
            }
            $categoryName = rtrim($categoryName, '/');
            $categoryName .= "|$category->id\n";

            if(isset($treeMenu[$category->id]) and !empty($treeMenu[$category->id]))
            {
                if(isset($treeMenu[$category->parent]))
                {
                    $treeMenu[$category->parent] .= $categoryName;
                }
                else
                {
                    $treeMenu[$category->parent] = $categoryName;;
                }
                $treeMenu[$category->parent] .= $treeMenu[$category->id];
            }
            else
            {
                if(isset($treeMenu[$category->parent]) and !empty($treeMenu[$category->parent]))
                {
                    $treeMenu[$category->parent] .= $categoryName;
                }
                else
                {
                    $treeMenu[$category->parent] = $categoryName;
                }    
            }
        }

        $topMenu = @array_pop($treeMenu);
        $topMenu = explode("\n", trim($topMenu));
        if(!$removeRoot) $lastMenu[] = '/';

        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;

            $menu       = explode('|', $menu);
            $label      = array_shift($menu);
            $categoryID = array_pop($menu);
           
            $lastMenu[$categoryID] = $label;
        }

        return $lastMenu;
    }

    /**
     * Get the tree menu in <ul><ol> type.
     * 
     * @param  string   $type           the tree type
     * @param  int      $startCategoryID  the start category
     * @param  string   $userFunc       which function to be called to create the link
     * @param  int      $root
     * @access public
     * @return string   the html code of the tree menu.
     */
    public function getTreeMenu($type = 'article', $startCategoryID = 0, $userFunc, $root = 0)
    {
        $treeMenu   = array();
        $categories = array();
        $stmt = $this->dbh->query($this->buildQuery($type, $startCategoryID, $root));
        while($category = $stmt->fetch())
        {
            $categories[$category->id] = $category;
        }
        $categories = $this->process($categories, $type);
        foreach($categories as $category)
        {
            $linkHtml = call_user_func($userFunc, $category);

            if(isset($treeMenu[$category->id]) and !empty($treeMenu[$category->id]))
            {
                if(!isset($treeMenu[$category->parent])) $treeMenu[$category->parent] = '';
                $treeMenu[$category->parent] .= "<li>$linkHtml";  
                $treeMenu[$category->parent] .= "<ul>".$treeMenu[$category->id]."</ul>\n";
            }
            else
            {
                if(isset($treeMenu[$category->parent]) and !empty($treeMenu[$category->parent]))
                {
                    $treeMenu[$category->parent] .= "<li>$linkHtml\n";  
                }
                else
                {
                    $treeMenu[$category->parent] = "<li>$linkHtml\n";  
                }    
            }
            $treeMenu[$category->parent] .= "</li>\n"; 
        }
        $lastMenu = "<ul class='tree'>" . @array_pop($treeMenu) . "</ul>\n";
        return $lastMenu; 
    }

    /**
     * Get productDoc tree menu.
     * 
     * @access public
     * @return string
     */
    public function getProductDocTreeMenu()
    {
        $menu = "<ul class='tree'>";
        $products = $this->loadModel('product')->getPairs();
        $modules  = $this->dao->findByType('productdoc')->from(TABLE_CATEGORY)->orderBy('`order`')->fetchAll();
        $projectModules = $this->dao->findByType('projectdoc')->from(TABLE_CATEGORY)->orderBy('`order`')->fetchAll();

        foreach($products as $productID =>$productName)
        {
            $menu .= '<li>';
            $menu .= html::a(helper::createLink('doc', 'browse', "libID=product&module=0&productID=$productID"), $productName);
            if($modules)
            {
                $menu .= '<ul>';
                foreach($modules as $module)
                {
                    $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=product&module=$module->id&productID=$productID"), $module->name) . '</li>';    
                }
                
                /* If $projectModules not emtpy, append the project modules. */
                if($projectModules)
                {   
                    $menu .= '<li>';
                    $menu .= html::a(helper::createLink('doc', 'browse', "libID=product&module=0&productID=$productID&projectID=int"), $this->lang->tree->projectDoc);       
                    $menu .= '<ul>';

                    foreach($projectModules as $module)
                    {
                        $menu .= '<li>' . html::a(helper::createLink('doc', 'browse', "libID=product&module=$module->id&productID=$productID"), $module->name) . '</li>';
                    } 

                    $menu .= '</ul></li>';
                } 

                $menu .= '</ul>';
            } 

            $menu .='</li>';
        }

        $menu .= '</ul>';
        return $menu;
    }

    /**
     * Get projectDoc tree menu.
     * 
     * @access public
     * @return string
     */
    public function getProjectDocTreeMenu()
    {
        $menu  = "<ul class='tree'>";
        $menu .= "</ul>";
        return $menu;
    }

    /**
     * Get tree structure.
     * 
     * @param  int    $rootID 
     * @param  string $type 
     * @access public
     * @return array
     */
    public function getTreeStructure($rootID, $type)
    {
        $stmt = $this->dbh->query($this->buildMenuQuery($rootID, $type, $startCategory = 0));
        return $this->getDataStructure($stmt, $type);
    }

    /**
     * Build the sql query.
     * 
     * @param  int    $rootID 
     * @param  string $type 
     * @param  int    $startCategory 
     * @access public
     * @return void
     */
    public function buildMenuQuery($rootID, $type, $startCategory)
    {
        /* Set the start module. */
        $startCategoryPath = '';
        if($startCategory > 0)
        {
            $startCategory = $this->getById($startCategory);
            if($startCategory) $startCategoryPath = $startCategory->path . '%';
        }

        return $this->dao->select('*')->from(TABLE_CATEGORY)
            ->where('root')->eq((int)$rootID)
            ->andWhere('type')->eq($type)
            ->beginIF($startCategoryPath)->andWhere('path')->like($startCategoryPath)->fi()
            ->orderBy('grade desc, `order`')
            ->get(); 
    }

    /**
     * Get full category tree.
     *
     * @param  object  $stmt
     * @param  string  $viewType
     * @access public
     * @return array
     */
    public function getDataStructure($stmt, $viewType) 
    {
        $parent = array();
        while($category = $stmt->fetch())
        {
            if(isset($parent[$category->id]))
            {
                $category->children = $parent[$category->id]->children;
                unset($parent[$category->id]);
            }
            if(!isset($parent[$category->parent])) $parent[$category->parent] = new stdclass();
            $parent[$category->parent]->children[] = $category;
        }

        $tree = array();
        foreach($parent as $category)
        {
            foreach($category->children as $children)
            {
                if($children->parent != 0) continue; //Filter project children categories.
                $tree[] = $children;
            }
        }
        return $tree;
    }

    /**
     * Create the forum board link.
     * 
     * @param  object      $board 
     * @access public
     * @return string
     */
    public static function createForumBoardLink($board)
    {
        if($board->parent)
        {
            $linkHtml = html::a(helper::createLink('forum', 'board', "id={$board->id}"), $board->name, "id='board{$board->id}'");
        }
        else
        {
            $linkHtml = $board->name;
        }

        return $linkHtml;
    }

    /**
     * Create doc link.
     * 
     * @param  object    $category 
     * @static
     * @access public
     * @return string
     */
    public static function createDocLink($category)
    {
        $linkHtml = html::a(helper::createLink('doc', 'browse', "libID={$category->root}&moduleID={$category->id}"), $category->name, "id='doc{$category->id}'");
        return $linkHtml;
    }

    /**
     * Create the product browse link.
     * 
     * @param  object      $category 
     * @access public
     * @return string
     */
    public static function createProductBrowseLink($category)
    {
        $linkHtml = html::a(helper::createLink('product', 'browse', "categoryID={$category->id}"), $category->name, "id='category{$category->id}'");
        return $linkHtml;
    }

    /**
     * Create the blog browse link.
     * 
     * @param  object      $category 
     * @access public
     * @return string
     */
    public static function createBlogBrowseLink($category)
    {
        $linkHtml = html::a(helper::createLink('blog', 'index', "category={$category->id}"), $category->name, "id='category{$category->id}'");
        return $linkHtml;
    }

    /**
     * Create dept colleague link.
     * 
     * @param  object    $category 
     * @static
     * @access public
     * @return string
     */
    public static function createDeptColleagueLink($category)
    {
        return html::a(helper::createLink('user', 'colleague', "deptID={$category->id}"), $category->name, "id='category{$category->id}'");
    }

    /**
     * Create dept admin link 
     * 
     * @param  object    $category 
     * @static
     * @access public
     * @return string
     */
    public static function createDeptAdminLink($category)
    {
        return html::a(helper::createLink('user', 'admin', "deptID={$category->id}"), $category->name, "id='category{$category->id}'");
    }

    /**
     * Create entry admin link 
     * 
     * @param  object    $category 
     * @static
     * @access public
     * @return string
     */
    public static function createEntryAdminLink($category)
    {
        return html::a(helper::createLink('entry', 'admin', "category={$category->id}"), $category->name, "id='category{$category->id}'");
    }

    /**
     * Create provider browse link.
     * 
     * @param  int    $category 
     * @static
     * @access public
     * @return string 
     */
    public static function createProviderBrowseLink($category)
    {
        return html::a(helper::createLink('provider', 'browse', "mode=query&param=category={$category->id}"), $category->name, "id='category{$category->id}'");
    }

    /**
     * Create the manage link.
     * 
     * @param  object         $category 
     * @access public
     * @return string
     */
    public static function createManageLink($category)
    {
        global $lang;

        /* Set the class of children link. */
        $childrenLinkClass = '';
        if($category->type == 'forum' and $category->grade == 2) $childrenLinkClass = 'hidden';

        $linkHtml  = $category->name;
        $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit', "category={$category->id}"), $lang->tree->edit, "class='ajax'");
        $linkHtml .= ' ' . html::a(helper::createLink('tree', 'children', "type={$category->type}&category={$category->id}&root=$category->root"), $lang->category->children, "class='$childrenLinkClass ajax'");
        $linkHtml .= ' ' . ($category->major ? html::a('#', $lang->delete, "disabled='disabled'") : html::a(helper::createLink('tree', 'delete',   "category={$category->id}"), $lang->delete, "class='deleter'"));

        return $linkHtml;
    }

    /**
     * Update a category.
     * 
     * @param  int     $categoryID 
     * @access public
     * @return void
     */
    public function update($categoryID)
    {
        $oldCategory = $this->getByID($categoryID);
        $category = fixer::input('post')
            ->stripTags('desc', $this->config->allowedTags)
            ->join('moderators', ',')
            ->join('rights', ',')
            ->join('users', ',')
            ->setDefault('readonly', 0)
            ->get();

        $category->rights = !empty($category->rights) ? ',' . trim($category->rights, ',') . ',' : '';
        $category->users  = !empty($category->users) ? ',' . trim($category->users, ',') . ',' : '';

        /* Set moderators. */
        if(!isset($category->moderators))
        {
            $category->moderators = '';
        }
        else
        {
            $category->moderators = trim($category->moderators, ',');
            $category->moderators = empty($category->moderators) ? '' : ',' . $category->moderators . ',';
        }

        $parent = $this->getById($this->post->parent);
        $category->grade = $parent ? $parent->grade + 1 : 1;

        $this->dao->update(TABLE_CATEGORY)
            ->data($category, $skip = 'uid')
            ->autoCheck()
            ->check('name', 'notempty')
            ->where('id')->eq($categoryID)
            ->exec();

        $this->fixPath($category->type);

        return !dao::isError();
    }
        
    /**
     * Delete a category.
     * 
     * @param  int     $categoryID 
     * @access public
     * @return void
     */
    public function delete($categoryID, $null = null)
    {
        $category = $this->getById($categoryID);
        $family   = $this->getFamily($categoryID);

        $this->dao->update(TABLE_CATEGORY)->set('grade = grade - 1')->where('id')->in($family)->exec();                      // Update family's grade.
        $this->dao->update(TABLE_CATEGORY)->set('parent')->eq($category->parent)->where('parent')->eq($categoryID)->exec();  // Update children's parent to their grandpa.
        $this->dao->delete()->from(TABLE_CATEGORY)->where('id')->eq($categoryID)->exec();                                    // Delete my self.
        $this->fixPath($category->type);
        if($category->type == 'entry') $this->dao->update(TABLE_ENTRY)->set('category')->eq('0')->where('category')->eq($categoryID)->exec(); // Update entry's category.

        return !dao::isError();
    }

    /**
     * Merge category for trade.
     * 
     * @access public
     * @return void
     */
    public function merge()
    {
        if(!$this->post->originCategories) return false;
        $targetCategoryID = $this->post->targetCategory;
        foreach($this->post->originCategories as $originCategoryID)
        {
            if($originCategoryID == $targetCategoryID) continue;

            $originCategory = $this->getByID($originCategoryID);
            $children       = $this->getAllChildID($originCategoryID);
            unset($children[$originCategoryID]);
            if(!empty($children)) return array('result' => 'fail', 'message' => sprintf($this->lang->tree->asParent, $originCategory->name));

            $this->dao->update(TABLE_TRADE)->set('category')->eq($targetCategoryID)->where('category')->eq($originCategoryID)->exec();
            if(!dao::isError()) $this->dao->delete()->from(TABLE_CATEGORY)->where('id')->eq($originCategoryID)->exec();
        }

        return !dao::isError();
    }

    /**
     * Manage children of one category.
     * 
     * @param string $type 
     * @param string $children 
     * @param int    $root 
     * @access public
     * @return void
     */
    public function manageChildren($type, $parent, $children, $root = 0)
    {
        /* Get parent. */
        $parent = $this->getByID($parent);

        /* Init the category object. */
        $category = new stdclass();
        $category->parent     = $parent ? $parent->id : 0;
        $category->grade      = $parent ? $parent->grade + 1 : 1;
        $category->type       = $type;
        $category->root       = (int)$root;
        $category->postedDate = helper::now();

        $i = 1;
        foreach($children as $key => $categoryName)
        {
            if(empty($categoryName)) continue;
            $order = $i * 10;

            /* First, save the child without path field. */
            $category->name  = $categoryName;
            $category->order = $order;
            $mode = $this->post->mode[$key];

            if($mode == 'new')
            {
                unset($category->id);
                $this->dao->insert(TABLE_CATEGORY)->data($category)->exec();

                /* After saving, update it's path. */
                $categoryID   = $this->dao->lastInsertID();
                $categoryPath = $parent ? $parent->path . $categoryID . ',' : ",$categoryID,";
                $this->dao->update(TABLE_CATEGORY)
                    ->set('path')->eq($categoryPath)
                    ->where('id')->eq($categoryID)
                    ->exec();
            }
            else
            {
                $categoryID = $key;
                $this->dao->update(TABLE_CATEGORY)
                    ->set('name')->eq($categoryName)
                    ->set('order')->eq($order)
                    ->where('id')->eq($categoryID)
                    ->exec();
            }
            $i ++;
        }

        return !dao::isError();
    }

    /**
     * Fix the path, grade fields according to the id and parent fields.
     *
     * @param  string    $type 
     * @access public
     * @return void
     */
    public function fixPath($type)
    {
        /* Get all categories grouped by parent. */
        $groupCategories = $this->dao->select('id, parent')->from(TABLE_CATEGORY)
            ->where('type')->eq($type)
            ->fetchGroup('parent', 'id');
        $categories = array();

        /* Cycle the groupCategories until it has no item any more. */
        while(count($groupCategories) > 0)
        { 
            /* Record the counts before processing. */
            $oldCounts = count($groupCategories);

            foreach($groupCategories as $parentCategoryID => $childCategories)
            {
                /** 
                 * If the parentCategory doesn't exsit in the categories, skip it. 
                 * If exists, compute it's child categories. 
                 */
                if(!isset($categories[$parentCategoryID]) and $parentCategoryID != 0) continue;

                if($parentCategoryID == 0)
                {
                    $parentCategory = new stdclass();
                    $parentCategory->grade = 0;
                    $parentCategory->path  = ',';
                }
                else
                {
                    $parentCategory = $categories[$parentCategoryID];
                }

                /* Compute it's child categories. */
                foreach($childCategories as $childCategoryID => $childCategory)
                {
                    $childCategory->grade = $parentCategory->grade + 1;
                    $childCategory->path  = $parentCategory->path . $childCategory->id . ',';

                    /**
                     * Save child category to categories, 
                     * thus the child of child can compute it's grade and path.
                     */
                    $categories[$childCategoryID] = $childCategory;
                }

                /* Remove it from the groupCategories.*/
                unset($groupCategories[$parentCategoryID]);
            }

            /* If after processing, no category processed, break the cycle. */
            if(count($groupCategories) == $oldCounts) break;
        }

        /* Save categories to database. */
        foreach($categories as $category)
        {
            $this->dao->update(TABLE_CATEGORY)->data($category)
                ->where('id')->eq($category->id)
                ->exec();
        }
    }

    /**
     * Process categories. 
     * 
     * @param  array  $categories 
     * @param  string $type 
     * @access public
     * @return array 
     */
    public function process($categories = array(), $type = '')
    {
        foreach($categories as $key => $category)
        {
            $tmpParent = $category->parent;
            if(isset($categories[$category->parent])) $category->parent = $categories[$category->parent];
            if(!$this->hasRight($category, $type)) 
            {
                unset($categories[$key]);
                continue;
            }
            $category->parent = $tmpParent;
        }

        return $categories;
    }

    /**
     * Check current user has Privilege for this category. 
     *
     * @param  mixed  $category 
     * @param  string $type
     * @access public
     * @return bool
     */
    public function hasRight($category = null, $type = '')
    {
        if($this->app->user->admin == 'super') return true;

        if(!is_object($category)) $category = $this->getByID($category, $type);
        if(!$category) return true;

        if(empty($category->users) && empty($category->rights))
        {
            $hasRight = true;
        }
        else
        {
            $hasRight = false;
            if(!empty($category->users))
            {
                $hasRight = strpos($category->users, ',' . $this->app->user->account . ',') !== false;
            }

            if(!$hasRight && !empty($category->rights))
            {
                $groups   = array_intersect($this->app->user->groups, explode(',', $category->rights));
                $hasRight = !empty($groups);
            }

            if(!$hasRight && !empty($category->moderators))
            {
                $hasRight = in_array($this->app->user->account, $category->moderators);
            }
        }

        if($hasRight && !empty($category->parent))
        {
            $hasRight = $this->hasRight($category->parent, $type);
        }

        return $hasRight;
    }

    /**
     * Check privilege for expense category.
     *
     * @param  int    $category 
     * @access public
     * @return void
     */
    public function checkRight($categoryID = 0)
    {
        if(!$this->hasRight($categoryID))
        {
            $locate = helper::createLink('cash.index');
            $errorLink = helper::createLink('sys.error', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($errorLink));
        }
    }
}
