<?php
function smarty_function_widget_homeblock_data($params, $template){
    $collection = new plugins_homeblock_public();

    $template->assign('homeblock',$collection->getContent());
}