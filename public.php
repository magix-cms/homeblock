<?php
require_once('db.php');
class plugins_homeblock_public extends plugins_homeblock_db {
	/**
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
	 */
	protected frontend_model_template $template;
	protected frontend_model_data $data;

	public string $lang;

    /**
     * @access public
     * Constructor
     */
    public function __construct() {
        $this->template = new frontend_model_template();
        $this->data = new frontend_model_data($this);
        $this->lang = $this->template->lang;
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param boolean|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, ?string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @return array|bool
     */
    public function getContent() {
        return $this->getItems('page',['lang' => $this->lang],'one');
    }
}