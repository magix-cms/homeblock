<?php
require_once('db.php');
class plugins_homeblock_public extends plugins_homeblock_db
{
    protected $template, $data, $getlang;

    /**
     * paramètre pour la requête JSON
     */
    public $json_multi_data, $marker, $dotless;

    /**
     * @access public
     * Constructor
     */
    public function __construct()
    {
        $this->template = new frontend_model_template();
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true)
    {
        return $this->data->getItems($type, $id, $context, $assign);
    }
    /**
     * @return array
     */
    public function getContent(){
        return $this->getItems('page',array('lang' => $this->getlang),'one');
    }
}