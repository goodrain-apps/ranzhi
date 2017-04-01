<?php
/**
 * The model file of doc module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php
class docModel extends model
{
    /**
     * Get library by id.
     * 
     * @param  int    $libID 
     * @access public
     * @return object
     */
    public function getLibById($libID)
    {
        $lib = $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
        /* Check rights. */
        if(!$this->hasRight($lib)) return false;
        return $lib;
    }

    /**
     * Get libraries.
     * 
     * @access public
     * @return array
     */
    public function getLibList()
    {
        return $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->fetchAll();
    }

    /**
     * Get libraries.
     * 
     * @access public
     * @return array
     */
    public function getLibPairs()
    {
        $libs    = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->fetchAll();
        $libList = array();
        /* Check rights. */
        foreach($libs as $lib)
        {
            if(!$this->hasRight($lib)) continue;
            $libList[$lib->id] = $lib->name;
        }
        return $libList;
    }

    /**
     * Get all libs by type.
     * 
     * @param  string $type 
     * @param  int    $pager 
     * @access public
     * @return array
     */
    public function getAllLibsByType($type, $pager = null)
    {
        $key = ($type == 'project') ? $type : 'id';
        $stmt = $this->dao->select("DISTINCT $key")->from(TABLE_DOCLIB)->where('deleted')->eq(0);
        if($type == 'project')
        {
            $stmt = $stmt->andWhere($type)->ne(0);
        }
        else
        {
            $stmt = $stmt->andWhere('project')->eq(0);
        }

        $idList = $stmt->orderBy("{$key}_desc")->page($pager, $key)->fetchPairs($key, $key);

        if($type == 'project')
        {
            $table = TABLE_PROJECT;
            $libs = $this->dao->select('id,name')->from($table)->where('id')->in($idList)->orderBy('id desc')->fetchAll('id');
        }
        else
        {
            $libs = $this->dao->select('id,name')->from(TABLE_DOCLIB)->where('id')->in($idList)->orderBy('order, id desc')->fetchAll('id');
        }

        return $libs;
    }

    /**
     * Get limit libs.
     * 
     * @param  string $type 
     * @param  int    $limit 
     * @access public
     * @return array
     */
    public function getLimitLibs($type, $limit = 4)
    {
        if($type == 'project')
        {
            $stmt = $this->dao->select('t1.*')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.project')->ne(0)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy('order, id desc')
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('project')->eq(0)->orderBy('order, id desc')->query();
        }

        $i    = 1;
        $libs = array();
        while($docLib = $stmt->fetch())
        {
            if($i > $limit) break;
            $key = ($type == 'project') ? 'project' : 'id';
            if($this->hasRight($docLib) and !isset($libs[$docLib->$key]))
            {
                $libs[$docLib->$key] = $docLib->name;
                $i++;
            }
        }

        if($type == 'project') $libs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in(array_keys($libs))->orderBy('id desc')->fetchAll('id');

        return $libs;
    }

    /**
     * Get libs by project.
     * 
     * @param  int    $projectID 
     * @param  string $mode 
     * @access public
     * @return array
     */
    public function getLibsByProject($projectID, $mode = '')
    {
        $projectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('project')->eq($projectID)->orderBy('order, id')->fetchAll('id');

        $libs = array();
        foreach($projectLibs as $lib)
        {
            if($this->hasRight($lib)) $libs[$lib->id] = $lib->name;
        }

        if(strpos($mode, 'onlylib') === false)
        {
            if(commonModel::hasPriv('doc', 'showFiles')) $libs['files'] = $this->lang->doc->files;
        }

        return $libs;
    }

    /**
     * Get lib files.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getLibFiles($projectID, $pager = null)
    {
        $this->loadModel('file');
        $docIdList = $this->dao->select('id')->from(TABLE_DOC)->where('project')->eq($projectID)->get();

        $taskIdList  = $this->dao->select('id')->from(TABLE_TASK)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->get();
        $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
            ->where("(objectType = 'project' and objectID = $projectID)")
            ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
            ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
            ->andWhere('size')->gt('0')
            ->page($pager)
            ->fetchAll('id');

        foreach($files as $fileID => $file)
        {
            $file->realPath = $this->file->savePath . $file->pathname;
            $file->webPath  = $this->file->webPath . $file->pathname;
        }

        return $files;
    }

    
    /**
     * Get left menus.
     * 
     * @param  mix    $libs 
     * @access public
     * @return void
     */
    public function getSubMenus($libs = null)
    {
        if(empty($libs)) $libs = $this->getLibPairs();

        $libMenu = array();

        foreach($libs as $id => $libName)
        {
            $libID = isset($this->lang->doc->systemLibs[$id]) ? $id : 'lib' . $id;
            $libMenu[$libID] = "$libName|doc|browse|libID=$id";
        }

        //$libMenu += (array)$this->lang->doc->menu;

        return (object)$libMenu;
    }

    /**
     * Get left menus order.
     * 
     * @param  mix    $libs 
     * @access public
     * @return void
     */
    public function getSubMenusOrder($libs = null)
    {
        if(empty($libs)) $libs = $this->getLibPairs();

        $libMenuOrder = array();
        foreach($libs as $id => $libName)
        {
            $libID = isset($this->lang->doc->systemLibs[$id]) ? $id : 'lib' . $id;
            $libMenuOrder[] = $libID;
        }

        foreach($this->lang->doc->menuOrder as $menu) $libMenuOrder[] = $menu;

        return $libMenuOrder;
    }

    /**
     * Get project libs groups. 
     * 
     * @param  array  $idList 
     * @access public
     * @return array
     */
    public function getSubLibGroups($idList)
    {
        $libGroups   = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('project')->in($idList)->orderBy('id')->fetchGroup('project', 'id');
        $buildGroups = array();
        foreach($libGroups as $projectID => $libs)
        {
            foreach($libs as $lib)
            {
                if($this->hasRight($lib)) $buildGroups[$projectID][$lib->id] = $lib->name;
            }
            if(commonModel::hasPriv('doc', 'showFiles')) $buildGroups[$projectID]['files'] = $this->lang->doc->fileLib;
        }

        return $buildGroups;
    }

    /**
     * Create a library.
     * 
     * @access public
     * @return void
     */
    public function createLib()
    {
        $lib = fixer::input('post')->stripTags('name')
            ->setForce('project', $this->post->libType == 'project' ? $this->post->project : 0)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('users', ',')
            ->join('groups', ',')
            ->remove('libType')
            ->get();

        $lib->users  = !empty($lib->users) ? ',' . trim($lib->users, ',') . ',' : '';
        $lib->groups = !empty($lib->groups) ? ',' . trim($lib->groups, ',') . ',' : '';

        $this->dao->insert(TABLE_DOCLIB)
            ->data($lib)
            ->autoCheck()
            ->batchCheck('name', 'notempty')
            ->check('name', 'unique')
            ->exec();

        $libID = $this->dao->lastInsertID();

        if(!$this->post->private and $this->post->libType == 'project') $this->setLibUsers($lib->project);

        return $libID;
    }

    /**
     * Update a library.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function updateLib($libID)
    {
        $libID  = (int)$libID;
        $oldLib = $this->getLibById($libID);
        $lib    = fixer::input('post')->stripTags('name')
            ->setIF(empty($oldLib->createdBy), 'createdBy', $this->app->user->account)
            ->setIF(empty($oldLib->createdDate), 'createdDate', helper::now())
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->join('users', ',')
            ->join('groups', ',')
            ->get();

        $lib->private = $this->post->private ? 1 : 0;
        $lib->users   = !empty($lib->users) ? ',' . trim($lib->users, ',') . ',' : '';
        $lib->groups  = !empty($lib->groups) ? ',' . trim($lib->groups, ',') . ',' : '';

        $this->dao->update(TABLE_DOCLIB)
            ->data($lib)
            ->autoCheck()
            ->batchCheck('name', 'notempty')
            ->check('name', 'unique', "id != $libID && project = $oldLib->project")
            ->where('id')->eq($libID)
            ->exec();

        if(!dao::isError()) return commonModel::createChanges($oldLib, $lib);
    }

    /**
     * Delete a lib.
     * 
     * @param  int      $tradeID 
     * @access public
     * @return void
     */
    public function deleteLib($libID)
    {
        $this->dao->delete()->from(TABLE_DOCLIB)->where('id')->eq($libID)->exec();
        return !dao::isError();
    }

    /**
     * Get docs.
     * 
     * @param  int|string   $libID 
     * @param  int          $projectID 
     * @param  int          $module 
     * @param  string       $orderBy 
     * @param  object       $pager 
     * @access public
     * @return void
     */
    public function getDocList($libID, $projectID, $module, $orderBy, $pager)
    {
        $projects = array();
        $projects = $this->loadModel('project', 'proj')->getPairs();

        $keysOfProjects = array_keys($projects);
        $allKeysOfProjects = $keysOfProjects;
        $allKeysOfProjects[] = 0;

        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';

        $docs = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->beginIF(is_numeric($libID))->andWhere('lib')->eq($libID)->fi()
            ->beginIF($libID == 'project')->andWhere('project')->in($keysOfProjects)->fi()
            ->beginIF($projectID > 0)->andWhere('project')->eq($projectID)->fi()
            ->beginIF((string)$projectID == 'int')->andWhere('project')->gt(0)->fi()
            ->beginIF(is_int($module))->andWhere('module')->eq($module)->fi()
            ->beginIF(!is_int($module) and $module)->andWhere('module')->in($module)->fi()
            ->orderBy($orderBy)
            ->fetchAll();

        $docs = $this->process($docs, $orderBy, $pager);
        
        return $docs;
    }

    /**
     * get doc list by search.
     * 
     * @param  string $orderBy 
     * @param  string $pager 
     * @access public
     * @return array
     */
    public function getDocListBySearch($orderBy, $pager)
    {
        if($this->session->docQuery == false) $this->session->set('docQuery', ' 1 = 1');
        $docQuery = $this->loadModel('search', 'sys')->replaceDynamic($this->session->docQuery);

        $docs = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere($docQuery)
            ->orderBy($orderBy)
            ->fetchAll();

        $docs = $this->process($docs, $orderBy, $pager);
        
        return $docs;
    }

    /**
     * Get doc info by id.
     * 
     * @param  int    $docID 
     * @param  int    $version 
     * @param  bool   $setImgSize 
     * @access public
     * @return void
     */
    public function getById($docID, $version = 0, $setImgSize = false)
    {
        $doc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$docID)->fetch();
        if(!$doc) return false;
        if(!$this->hasRight($doc)) return false;
        $version = $version ? $version : $doc->version;
        $docContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($version)->fetch();

        $files = $this->loadModel('file')->getByObject('doc', $docID);
        $docFiles = array();
        foreach($files as $file)
        {
            $file->webPath  = $this->file->webPath . $file->pathname;
            $file->realPath = $this->file->savePath . $file->pathname;
            if(!empty($docContent->files) && strpos(",$docContent->files,", ",$file->id,") !== false) $docFiles[$file->id] = $file;
        }

        /* Check file change. */
        if($version == $doc->version and ((empty($docContent->files) and $docFiles) or (!empty($docContent->files) and count(explode(',', trim($docContent->files, ','))) != count($docFiles))))
        {
            unset($docContent->id);
            $doc->version        += 1;
            $docContent->version  = $doc->version;
            $docContent->files    = join(',', array_keys($docFiles));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($doc->version)->where('id')->eq($doc->id)->exec();
        }

        $doc->title       = isset($docContent->title)   ? $docContent->title  : '';
        $doc->digest      = isset($docContent->digest)  ? $docContent->digest  : '';
        $doc->content     = isset($docContent->content) ? $docContent->content : '';
        $doc->contentType = isset($docContent->type)    ? $docContent->type : '';
        $doc->files = $docFiles;

        $doc->libName     = '';
        $doc->projectName = '';
        $doc->moduleName  = '';
        if($doc->lib)     $doc->libName     = $this->dao->findByID($doc->lib)->from(TABLE_DOCLIB)->fetch('name');
        if($doc->project) $doc->projectName = $this->dao->findByID($doc->project)->from(TABLE_PROJECT)->fetch('name');
        if($doc->module)  $doc->moduleName  = $this->dao->findByID($doc->module)->from(TABLE_CATEGORY)->fetch('name');
        return $doc;
    }

    /**
     * Create a doc.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $now = helper::now();
        $doc = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('version', 1)
            ->setDefault('project, module', 0)
            ->specialChars('title, digest, keywords')
            ->encodeURL('url')
            ->stripTags('content', $this->config->allowedTags)
            ->stripTags($this->config->doc->markdown->create['id'], $this->config->allowedTags)
            ->cleanInt('project, module')
            ->remove('files,labels')
            ->join('users', ',')
            ->join('groups', ',')
            ->get();

        $doc->users  = !empty($doc->users) ? ',' . trim($doc->users, ',') . ',' : '';
        $doc->groups = !empty($doc->groups) ? ',' . trim($doc->groups, ',') . ',' : '';

        $condition = "lib = '$doc->lib' AND module = $doc->module";
        $doc = $this->loadModel('file')->processEditor($doc, $this->config->doc->editor->create['id'], $this->post->uid);

        $lib = $this->getLibByID($doc->lib);
        $doc->project = $lib->project;
        if($doc->type == 'url')
        {
            $doc->content     = $doc->url;
            $doc->contentType = 'html';
        }

        $docContent = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->digest  = $doc->digest;
        $docContent->content = $doc->contentType == 'html' ? $doc->content : $doc->contentMarkdown;
        $docContent->type    = $doc->contentType;
        $docContent->version = 1;
        if($doc->contentType == 'markdown') $docContent->content = str_replace('&gt;', '>', $docContent->content);
        unset($doc->digest);
        unset($doc->content);
        unset($doc->contentMarkdown);
        unset($doc->contentType);
        unset($doc->url);

        $this->dao->insert(TABLE_DOC)
            ->data($doc, 'uid')
            ->autoCheck()
            ->batchCheck($this->config->doc->require->create, 'notempty')
            ->check('title', 'unique', $condition)
            ->exec();

        if(!dao::isError())
        {
            $docID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $docID, 'doc');
            $files = $this->file->saveUpload('doc', $docID);

            $docContent->doc   = $docID;
            $docContent->files = join(',', array_keys($files));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            return $docID;
        }
        return false;
    }

    /**
     * Update a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function update($docID)
    {
        $oldDoc = $this->getById($docID);
        $doc = fixer::input('post')
            ->cleanInt('module')
            ->setDefault('module', 0)
            ->specialChars('title, digest, keywords')
            ->encodeURL('url')
            ->stripTags('content', $this->config->allowedTags)
            ->add('editedBy',   $this->app->user->account)
            ->add('editedDate', helper::now())
            ->join('users', ',')
            ->join('groups', ',')
            ->remove('comment,files,labels')
            ->get();

        $doc->private = $this->post->private ? 1 : 0;
        $doc->users   = !empty($doc->users) ? ',' . trim($doc->users, ',') . ',' : '';
        $doc->groups  = !empty($doc->groups) ? ',' . trim($doc->groups, ',') . ',' : '';

        if($oldDoc->contentType == 'markdown') $doc->content = str_replace('&gt;', '>', $doc->content);

        $uniqueCondition = "lib = '{$oldDoc->lib}' AND module = {$doc->module} AND id != $docID";
        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processEditor($doc, $this->config->doc->editor->edit['id'], $this->post->uid);
        $doc->project = $lib->project;
        if($oldDoc->type == 'url') $doc->content = $doc->url;
        unset($doc->url);

        $files   = $this->file->saveUpload('doc', $docID);
        $changes = commonModel::createChanges($oldDoc, $doc);
        $changed = false;
        if($files) $changed = true;
        foreach($changes as $change)
        {
            if($change['field'] == 'content' or $change['field'] == 'title' or $change['field'] == 'digest') $changed = true;
        }

        if($changed)
        {
            $oldDocContent = $this->dao->select('files,type')->from(TABLE_DOCCONTENT)->where('doc')->eq($docID)->andWhere('version')->eq($oldDoc->version)->fetch();
            $doc->version  = $oldDoc->version + 1;

            $docContent = new stdclass();
            $docContent->doc     = $docID;
            $docContent->title   = $doc->title;
            $docContent->digest  = $doc->digest;
            $docContent->content = $doc->content;
            $docContent->version = $doc->version;
            $docContent->type    = $oldDocContent->type;
            $docContent->files   = $oldDocContent->files;
            if($files) $docContent->files .= ',' . join(',', array_keys($files));
            $docContent->files   = trim($docContent->files, ',');
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        unset($doc->digest);
        unset($doc->content);
        unset($doc->contentType);

        $this->dao->update(TABLE_DOC)->data($doc, 'uid')
            ->autoCheck()
            ->batchCheck($this->config->doc->require->edit, 'notempty')
            ->check('title', 'unique', $uniqueCondition)
            ->where('id')->eq((int)$docID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $docID, 'doc');
            return array('changes' => $changes, 'files' => $files);
        }
    }

    /**
     * Get pairs of project modules.
     * 
     * @access public
     * @return array
     */
    public function getProjectModulePairs()
    {
        return $this->dao->select('t1.id,t1.name')->from(TABLE_CATEGORY)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t1.root = t2.id')
            ->andWhere('t1.type')->eq('doc')
            ->andWhere('t2.project')->ne('0')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get doc menu.
     * 
     * @param  int    $libID 
     * @param  int    $parent 
     * @access public
     * @return array
     */
    public function getDocMenu($libID, $parent, $orderBy = 'name_asc')
    {
        return $this->dao->select('*')->from(TABLE_CATEGORY)->where('root')->eq($libID)
            ->andWhere('type')->eq('doc')
            ->andWhere('parent')->eq($parent)
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * Get doc tree.
     * 
     * @param  int    $libID 
     * @access public
     * @return array
     */
    public function getDocTree($libID)
    {
        $fullTrees = $this->loadModel('tree')->getTreeStructure($libID, 'doc');
        array_unshift($fullTrees, array('id' => 0, 'name' => '/', 'type' => 'doc', 'actions' => false, 'root' => $libID));
        $docTree = "<ul class='tree'>";
        foreach($fullTrees as $i => $tree)
        {
            $tree = (object)$tree;
            $docTree .= "<li><i class='icon category icon-folder-open-alt'> </i>" . $tree->name;
            $docTree .= $this->fillDocsInTree($tree, $libID);
            $docTree .= '</li>';
        }
        $docTree .= '</ul>';
        return $docTree;
    }

    /**
     * Fill docs in tree.
     * 
     * @param  object $node 
     * @param  int    $libID 
     * @access public
     * @return array
     */
    public function fillDocsInTree($node, $libID)
    {
        $tree = '<ul>';
        $node = (object)$node;
        static $docGroups;
        if(empty($docGroups))
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)->where('lib')->eq((int)$libID)->andWhere('deleted')->eq(0)->fetchAll();
            $docGroups = array();
            foreach($docs as $doc) $docGroups[$doc->module][$doc->id] = $doc;
        }

        if(!empty($node->children))
        {
            foreach($node->children as $i => $child)
            {
                $tree .= "<li><i class='icon category icon-folder-open-alt'> </i>" . $child->name;
                $tree .= $this->fillDocsInTree($child, $libID);
                $tree .= '</li>';
            }
        }

        if(!isset($node->id)) $node->id = 0;

        $docs = isset($docGroups[$node->id]) ? $docGroups[$node->id] : array();
        if(!empty($docs))
        {
            foreach($docs as $doc)
            {
                $tree .= '<li>';
                $tree .= html::a(helper::createLink('doc', 'view', "doc=$doc->id"), "<i class='icon icon-file-text-o'></i> " . $doc->title);
                $tree .= "<span class='doc-actions'>" . html::a(helper::createLink('doc', 'edit', "doc=$doc->id"), "<i class='icon icon-pencil'> </i>");
                $tree .= html::a(helper::createLink('doc', 'delete', "doc=$doc->id"), "<i class='icon icon-remove'></i>", "class='deleter'") . '</span>';
                $tree .='</li>';

            }
        }
        $tree .= '</ul>';
        return $tree;
    }

    /**
     * Extract css styles for tables created in kindeditor.
     *
     * Like this: <table class="ke-table1" style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
     * 
     * @param  string    $content 
     * @access public
     * @return void
     */
    public function extractKETableCSS($content)
    {
        $css = '';
        $rule = '/<table class="ke(.*)" .*/';
        if(preg_match_all($rule, $content, $results))
        {
            foreach($results[0] as $tableLine)
            {
                $attributes = explode(' ', str_replace('"', '', $tableLine));
                foreach($attributes as $attribute)
                {
                    if(strpos($attribute, '=') === false) continue;
                    list($attributeName, $attributeValue) = explode('=', $attribute);
                    $$attributeName = trim(str_replace('>', '', $attributeValue));
                }

                if(!isset($class)) continue;
                $className   = $class;
                $borderSize  = isset($border)      ? $border . 'px' : '1px';
                $borderColor = isset($bordercolor) ? $bordercolor : 'gray';
                $borderStyle = "{border:$borderSize $borderColor solid}\n";
                $css .= ".$className$borderStyle";
                $css .= ".$className td$borderStyle";
            }
        }
        return $css;
    }

    /**
     * Process docs and fix pager. 
     * 
     * @param  array  $docs 
     * @param  string $orderBy
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function process($docs = array(), $orderBy = 'id_desc', $pager = null)
    {
        $idList = array();
        foreach($docs as $key => $doc)
        {
            if($this->hasRight($doc)) $idList[] = $doc->id;
        }

        $docIDList = $this->dao->select('id')->from(TABLE_DOC)->where('id')->in($idList)->orderBy($orderBy)->page($pager)->fetchAll('id');
        foreach($docs as $key => $doc)
        {
            if(!isset($docIDList[$doc->id])) unset($docs[$key]);
        }

        return $docs;
    }

    /**
     * Set main menu.
     * 
     * @param  int    $projectID 
     * @param  int    $libID 
     * @param  int    $category 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function setMainMenu()
    {
        if(isset($this->config->customMenu->doc))
        {
            $customMenu = json_decode($this->config->customMenu->doc);
            $menuLibIdList = array();
            foreach($customMenu as $i => $menu)
            {
                if(strpos($menu->name, 'custom') === 0)
                {
                    $menuLibID = (int)substr($menu->name, 6);
                    if($menuLibID) $menuLibIdList[$i] = $menuLibID;
                }
            }

            $projectIdList = array();
            if($menuLibIdList)
            {
                $libs = $this->dao->select('id,name,project')->from(TABLE_DOCLIB)->where('id')->in($menuLibIdList)->fetchAll('id');
                foreach($libs as $lib)
                {
                    if($lib->project) $projectIdList[] = $lib->project;
                }
            }
            $projects = $projectIdList ? $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchPairs('id', 'name') : array();

            foreach($menuLibIdList as $i => $menuLibID)
            {
                $lib = $libs[$menuLibID];
                $libName = ($lib->project and isset($projects[$lib->project])) ? '[' . $projects[$lib->project] . ']' . $lib->name : $lib->name;
                $this->lang->menu->doc->$menuLibID = "$libName|doc|browse|libID=$menuLibID&moduleID=&projectID=$lib->project";
            }
        }
    }

    /**
     * Set module menu.
     * 
     * @param  int    $projectID 
     * @param  int    $libID 
     * @param  int    $category 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function setMenu($projectID = 0, $libID = 0, $category = 0, $extra = '')
    {
        /* Check the privilege. */
        $lib = $this->getLibById($libID);
        $projectID = !empty($lib) ? $lib->project : $projectID;
        $project   = $this->loadModel('project', 'proj')->getById($projectID);
        $category  = $this->loadModel('tree')->getByID($category);

        $moduleMenu = "<nav id='menu'><ul class='nav'>";
        if($project)
        {
            $moduleMenu .= commonModel::printLink('doc', 'projectLibs', "projectID=$project->id", $project->name, '', false, '', 'li');
            if($lib) $moduleMenu .= "<li class='divider angle'></li>" . commonModel::printLink('doc', 'browse', "libID=$lib->id", $lib->name, '', false, '', 'li');
            if($category)
            {
                foreach($category->pathNames as $categoryID => $categoryName)
                {
                    $moduleMenu .= "<li class='divider angle'></li>" . commonModel::printLink('doc', 'browse', "libID=$lib->id&moduleID=$categoryID", $categoryName, '', false, '', 'li');
                }
            }
        }
        else
        {
            if($lib) $moduleMenu .= commonModel::printLink('doc', 'browse', "libID=$lib->id", $lib->name, '', false, '', 'li');
            if($category)
            {
                foreach($category->pathNames as $categoryID => $categoryName)
                {
                    $moduleMenu .= "<li class='divider angle'></li>" . commonModel::printLink('doc', 'browse', "libID=$lib->id&moduleID=$categoryID", $categoryName, '', false, '', 'li');
                }
            }
        }
        if($extra) $moduleMenu .= "<li class='divider angle'></li><li>" . $extra . '</li>';
        $moduleMenu .= '</ul></nav>';
        echo  $moduleMenu;
    }

    /**
     * Set lib users.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function setLibUsers($projectID)
    {
        $libs  = $this->dao->select('*')->from(TABLE_DOCLIB)->where('project')->eq($projectID)->fetchAll();
        $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('id')->eq($projectID)->fetchPairs('account', 'account');

        foreach($libs as $lib)
        {
            foreach(explode(',', $lib->users) as $account) $teams[$account] = $account;
            $this->dao->update(TABLE_DOCLIB)->set('users')->eq(join(',', $teams))->where('id')->eq($lib->id)->exec();
        }
        return true;
    }

    /**
     * Check rights of doc and lib.
     * 
     * @param  object $object 
     * @access public
     * @return bool
     */
    public function hasRight($object = null)
    {
        if(!$object) return false;

        if($this->app->user->admin == 'super' || $this->app->user->account == $object->createdBy) return true;
        
        if(!empty($object->private))
        {
            return $this->app->user->account == $object->createdBy;
        }   

        if(empty($object->users) && empty($object->groups))
        {
            $hasRight = true;
        }
        else
        {
            $hasRight = false;
            if(!empty($object->users))
            {
                $hasRight = strpos($object->users, ',' . $this->app->user->account . ',') !== false;
            }

            if(!$hasRight && !empty($object->groups))
            {
                $groups   = array_intersect($this->app->user->groups, explode(',', $object->groups));
                $hasRight = !empty($groups);
            }
        }

        if($hasRight && !empty($object->lib))
        {
            $object   = $this->getLibById($object->lib);
            $hasRight = $this->hasRight($object);
        }

        return $hasRight;
    }
}
