<?php
require_once('db.php');
class plugins_homeblock_admin extends plugins_homeblock_db {
	/**
	 * @var backend_model_template $template
	 * @var backend_model_data $data
	 * @var component_core_message $message
	 * @var backend_controller_plugins $plugins
	 * @var backend_model_language $modelLanguage
	 * @var component_collections_language $collectionLanguage
	 */
	protected backend_model_template $template;
	protected backend_model_data $data;
	protected component_core_message $message;
	protected backend_controller_plugins $plugins;
	protected backend_model_language $modelLanguage;
	protected component_collections_language $collectionLanguage;

	/**
	 * @var int $edit
	 * @var int $id
	 */
    public int
		$edit,
		$id;

	/**
	 * @var string $action
	 */
    public string $action;

    /**
     * @var array $content
     */
    public array $content;

    public function __construct() {
        $this->template = new backend_model_template();
		$this->data = new backend_model_data($this);
		$this->message = new component_core_message($this->template);
        $this->plugins = new backend_controller_plugins();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();

        // --- Get
        if(http_request::isGet('edit')) $this->edit = form_inputEscape::numeric($_GET['edit']);
        if(http_request::isRequest('action')) $this->action = form_inputEscape::simpleClean($_REQUEST['action']);

        // POST

        // - Content
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_homeblock') ? form_inputEscape::cleanQuote($v) : form_inputEscape::simpleClean($v);
                }
            }
            $this->content = $array;
        }
    }

    /**
     * Method to override the name of the plugin in the admin menu
     * @return string
     */
    public function getExtensionName(): string {
        return $this->template->getConfigVars('homeblock_plugin');
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param array|int|null $id
	 * @param string|null $context
	 * @param boolean|string $assign
	 * @return mixed
	 */
	private function getItems(string $type, $id = null, string $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	// --- Database actions
    /**
     * Insert data
	 * @param string $type
	 * @param array $params
     */
    private function add(string $type, array $params) {
        switch ($type) {
            case 'content':
                parent::insert(['type' => $type], $params);
                break;
        }
    }

    /**
     * Update data
     * @param string $type
     * @param array $params
     */
    private function upd(string $type, array $params) {
        switch ($type) {
            case 'content':
                parent::update(['type' => $type], $params);
                break;
        }
    }
	// --------------------

    /**
     *
     */
    public function run() {
		if(http_request::isMethod('POST')) {
			if (isset($this->action) && $this->action === 'edit' && !empty($this->content)) {
				//$root = parent::fetchData(['context' => 'one', 'type' => 'root']);
				$root = $this->getItems('root',null,'one',false);
				if (!$root) {
					parent::insert(['type' => 'root']);
					//$root = parent::fetchData(['context' => 'one', 'type' => 'root']);
					$root = $this->getItems('root',null,'one',false);
				}
				$id = $root['id_homeblock'];

				foreach ($this->content as $lang => $content) {
					if (empty($content['id'])) $content['id'] = $id;
					$rootLang = $this->getItems('content', ['id' => $id, 'id_lang' => $lang], 'one', false);
					$content['id_lang'] = $lang;
					$content['published_homeblock'] = (int)!isset($content['published_homeblock']);

					($rootLang) ? $this->upd('content', $content) : $this->add('content', $content);
				}
				$this->message->json_post_response(true, 'update');
			}
		}
		else {
            $this->modelLanguage->getLanguage();
            $last = parent::fetchData(['context' => 'one', 'type' => 'root']);
			$data = $this->getItems('pages',null,'all',false);
			$pages = [];
			if(!empty($data)) {
				foreach ($data as $page) {
					if (!array_key_exists($page['id_homeblock'], $pages)) {
						$pages[$page['id_homeblock']] = array();
						$pages[$page['id_homeblock']]['id_homeblock'] = $page['id_homeblock'];
					}
					$pages[$page['id_homeblock']]['content'][$page['id_lang']] = [
						'id_lang' => $page['id_lang'],
						'name_homeblock' => $page['name_homeblock'],
						'content_homeblock' => $page['content_homeblock'],
						'published_homeblock' => $page['published_homeblock']
					];
				}
			}
            $this->template->assign('pages', empty($pages) ? [] : $pages[$last['id_homeblock']]);
            $this->template->display('index.tpl');
        }
    }
}