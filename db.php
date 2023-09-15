<?php
class plugins_homeblock_db
{
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config, array $params = []) {
		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'pages':
					$query = 'SELECT h.*,c.*
							FROM mc_homeblock AS h
							JOIN mc_homeblock_content AS c USING(id_homeblock)
							JOIN mc_lang AS lang ON(c.id_lang = lang.id_lang)';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		} 
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'root':
					$query = 'SELECT * FROM mc_homeblock ORDER BY id_homeblock DESC LIMIT 0,1';
					break;
				case 'content':
					$query = 'SELECT * FROM mc_homeblock_content WHERE id_homeblock = :id AND id_lang = :id_lang';
					break;
				case 'page':
					$query = 'SELECT *
							FROM mc_homeblock as g
							JOIN mc_homeblock_content as gc USING(id_homeblock)
							JOIN mc_lang as l USING(id_lang)
							WHERE iso_lang = :lang
							LIMIT 0,1';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert(array $config, array $params = []) {
        switch ($config['type']) {
			case 'root':
				$query = 'INSERT INTO mc_homeblock(date_register) VALUES (NOW())';
				break;
			case 'content':
				$query = 'INSERT INTO mc_homeblock_content(id_homeblock, id_lang, name_homeblock, content_homeblock, published_homeblock) 
						VALUES (:id, :id_lang, :name_homeblock, :content_homeblock, :published_homeblock)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'content':
				$query = 'UPDATE mc_homeblock_content 
						SET 
							name_homeblock = :name_homeblock,
							content_homeblock = :content_homeblock,
							published_homeblock = :published_homeblock
						WHERE id_homeblock = :id 
						AND id_lang = :id_lang';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception : '.$e->getMessage();
		}
	}
}