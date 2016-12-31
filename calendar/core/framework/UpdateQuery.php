<?php
class UpdateQuery extends CommonQuery {

	public function __construct(Model $fpdo, $table) {
		$clauses = array(
			'UPDATE' => array($this, 'getClauseUpdate'),
			'JOIN' => array($this, 'getClauseJoin'),
			'SET' => array($this, 'getClauseSet'),
			'WHERE' => ' AND ',
			'ORDER BY' => ', ',
			'LIMIT' => null,
		);
		parent::__construct($fpdo, $clauses);

		$this->statements['UPDATE'] = $table;

		$tableParts = explode(' ', $table);
		$this->joins[] = end($tableParts);
	}

	/**
	 * @param string|array $fieldOrArray
	 * @param null $value
	 * @return $this
	 * @throws Exception
	 */
	public function set($fieldOrArray, $value = false) {
		if (!$fieldOrArray) {
			return $this;
		}
		if (is_string($fieldOrArray) && $value !== false) {
			$this->statements['SET'][$fieldOrArray] = $value;
		} else {
			if (!is_array($fieldOrArray)) {
				throw new Exception('You must pass a value, or provide the SET list as an associative array. column => value');
			} else {
				foreach ($fieldOrArray as $field => $value) {
					$this->statements['SET'][$field] = $value;
				}
			}
		}

		return $this;
	}

	/** Execute update query
	 * @param boolean $getResultAsPdoStatement true to return the pdo statement instead of row count
	 * @return int|boolean|PDOStatement
	 */
	public function execute($getResultAsPdoStatement = false) {
		$result = parent::execute();
		if ($getResultAsPdoStatement) {
			return $result;
		}
		if ($result) {
			return $result->rowCount();
		}
		return false;
	}

	protected function getClauseUpdate() {
		return 'UPDATE ' . $this->statements['UPDATE'];
	}

	protected function getClauseSet() {
		$setArray = array();
		foreach ($this->statements['SET'] as $field => $value) {
			if ($value instanceof FluentLiteral) {
				$setArray[] = $field . ' = ' . $value;
			} else {
				$setArray[] = $field . ' = ?';
				$this->parameters['SET'][$field] = $value;
			}
		}

		return ' SET ' . implode(', ', $setArray);
	}
}