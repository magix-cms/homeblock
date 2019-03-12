<?php
require_once('db.php');
class plugins_homeblock_admin extends plugins_homeblock_db
{
    protected $controller,$data,$template, $message, $plugins,$modelLanguage,$collectionLanguage,$header;
    public $edit, $action, $id;
    /**
     * Page title and content
     * @var array
     */
    public $content;
    /**
     * constructeur
     */
    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();
        $this->message = new component_core_message($this->template);
        $this->data = new backend_model_data($this);
        $this->header = new http_header();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();

        // --- Get
        if(http_request::isGet('controller')) {
            $this->controller = $formClean->simpleClean($_GET['controller']);
        }
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        // POST

        // - Content
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_homeblock') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
    }
    /**
     * Method to override the name of the plugin in the admin menu
     * @return string
     */
    public function getExtensionName()
    {
        return $this->template->getConfigVars('homeblock_plugin');
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }
    /**
     * @param $data
     * @return array
     */
    private function setItemContentData($data)
    {
        $arr = array();
        foreach ($data as $page) {
            if (!array_key_exists($page['id_homeblock'], $arr)) {
                $arr[$page['id_homeblock']] = array();
                $arr[$page['id_homeblock']]['id_homeblock'] = $page['id_homeblock'];
            }
            $arr[$page['id_homeblock']]['content'][$page['id_lang']] = array(
                'id_lang'          => $page['id_lang'],
                'name_homeblock'        => $page['name_homeblock'],
                'content_homeblock'     => $page['content_homeblock'],
                'published_homeblock'   => $page['published_homeblock']
            );
        }
        return $arr;
    }

    /**
     * Insert data
     * @param array $config
     * @throws Exception
     */
    private function add($config)
    {
        switch ($config['type']) {
            case 'content':
                parent::insert(
                    array('type' => $config['type']),
                    $config['data']
                );
                break;
        }
    }

    /**
     * Update data
     * @param array $config
     * @throws Exception
     */
    private function upd($config)
    {
        switch ($config['type']) {
            case 'content':
                parent::update(
                    array('type' => $config['type']),
                    $config['data']
                );
                break;
        }
    }
    /**
     * set Data from database
     * @access private
     */
    private function getBuildItems($data)
    {
        switch($data['type']){
            case 'content':
                $collection = $this->getItems('pages',null,'all',false);
                return $this->setItemContentData($collection);
                break;
        }
    }
    /**
     *
     */
    public function run()
    {

        if (isset($this->action)) {
            switch ($this->action) {
                case 'edit':
                    if (isset($this->content) && !empty($this->content)) {
                        $root = parent::fetchData(array('context' => 'one', 'type' => 'root'));
                        if (!$root) {
                            parent::insert(array('type' => 'root'));
                            $root = parent::fetchData(array('context' => 'one', 'type' => 'root'));
                        }
                        $id = $root['id_homeblock'];

                        foreach ($this->content as $lang => $content) {
                            if (empty($content['id'])) $content['id'] = $id;
                            $rootLang = $this->getItems('content', array('id' => $id, 'id_lang' => $lang), 'one', false);

                            $content['id_lang'] = $lang;
                            $content['published_homeblock'] = (!isset($content['published_homeblock']) ? 0 : 1);

                            $config = array(
                                'type' => 'content',
                                'data' => $content
                            );

                            ($rootLang) ? $this->upd($config) : $this->add($config);
                        }
                        $this->message->json_post_response(true, 'update');
                    }
                    break;
            }
        }else{
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
            $last = parent::fetchData(array('context' => 'one', 'type' => 'root'));
            $pages = $this->getBuildItems(array('type' => 'content'));
            $this->template->assign('pages', $pages[$last['id_homeblock']]);
            $this->template->display('index.tpl');
        }
    }
}
?>