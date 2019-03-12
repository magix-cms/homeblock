<?php

/**
 * Class plugins_homeblock_db
 */
class plugins_homeblock_db
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config, $params = false)
    {
        $sql = '';

        if (is_array($config)) {
            if ($config['context'] === 'all') {
                switch ($config['type']) {
                    case 'pages':
                        $sql = 'SELECT h.*,c.*
                    			FROM mc_homeblock AS h
                    			JOIN mc_homeblock_content AS c USING(id_homeblock)
                    			JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)';
                        break;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
            } elseif ($config['context'] === 'one') {
                switch ($config['type']) {
                    case 'root':
                        $sql = 'SELECT * FROM mc_homeblock ORDER BY id_homeblock DESC LIMIT 0,1';
                        break;
                    case 'content':
                        $sql = 'SELECT * FROM mc_homeblock_content WHERE id_homeblock = :id AND id_lang = :id_lang';
                        break;
                    case 'page':
                        $sql = 'SELECT *
								FROM mc_homeblock as g
								JOIN mc_homeblock_content as gc USING(id_homeblock)
								JOIN mc_lang as l USING(id_lang)
								WHERE iso_lang = :lang
								LIMIT 0,1';
                }

                return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
            }
        }
    }

    /**
     * @param $config
     * @param array $params
     * @throws Exception
     */
    public function insert($config, $params = array())
    {
        if (is_array($config)) {
            $sql = '';

            switch ($config['type']) {
                case 'root':
                    $sql = 'INSERT INTO mc_homeblock(date_register) VALUES (NOW())';
                    break;
                case 'content':
                    $sql = 'INSERT INTO mc_homeblock_content(id_homeblock, id_lang, name_homeblock, content_homeblock, published_homeblock) 
				  			VALUES (:id, :id_lang, :name_homeblock, :content_homeblock, :published_homeblock)';
                    break;
            }

            if ($sql !== '') component_routing_db::layer()->insert($sql,$params);
        }
    }

    /**
     * @param $config
     * @param array $params
     * @throws Exception
     */
    public function update($config, $params = array())
    {
        if (is_array($config)) {
            $sql = '';

            switch ($config['type']) {
                case 'content':
                    $sql = 'UPDATE mc_homeblock_content 
							SET 
								name_homeblock = :name_homeblock,
							 	content_homeblock = :content_homeblock,
							  	published_homeblock = :published_homeblock
                			WHERE id_homeblock = :id 
                			AND id_lang = :id_lang';
                    break;
            }

            if ($sql !== '') component_routing_db::layer()->update($sql,$params);
        }
    }
}