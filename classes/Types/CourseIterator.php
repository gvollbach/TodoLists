<?php declare(strict_types=1);
/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

namespace ILIAS\Plugin\TodoLists\Types;

/**
 * Class CourseIterator
 * @package ILIAS\Plugin\ContentAuthoringReport\Export\Data
 * @author Michael Jansen <mjansen@databay.de>
 */
class CourseIterator implements \Iterator
{
	/**
	 * @var \ilDBInterface
	 */
	protected $db;

	/**
	 * @var \ilDBStatement
	 */
	protected $res;

	/**
	 * @var array
	 */
	protected $data = array();

	/**
	 * Iterator constructor.
	 * @param \ilDBInterface $db
	 */
	public function __construct(\ilDBInterface $db)
	{
		$this->db = $db;
	}

	/**
	 *
	 */
	public function __destruct()
	{
		if ($this->res) {
			$this->db->free($this->res);
			$this->res = null;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function current()
	{
		return $this->data;
	}

	/**
	 * @inheritdoc
	 */
	public function next()
	{
	}

	/**
	 * @inheritdoc
	 */
	public function key()
	{
		return $this->data['ref_id'];
	}

	/**
	 * @inheritdoc
	 */
	public function valid()
	{
		$this->data = $this->db->fetchAssoc($this->res);

		return is_array($this->data);
	}

	/**
	 * @inheritdoc
	 */
	public function rewind()
	{
		if ($this->res) {
			$this->db->free($this->res);
			$this->res = null;
		}

		$query = "
			SELECT 
				od.obj_id, od.title, od.create_date, od.owner, od.type,
				objr.ref_id,
				odp.title parent_title,
				tree.*
			FROM object_data od
			INNER JOIN object_reference objr
				ON objr.obj_id = od.obj_id
				AND objr.deleted IS NULL 
			INNER JOIN tree
				ON tree.child = objr.ref_id
				AND tree.tree = %s
			INNER JOIN object_reference objrp
				ON objrp.ref_id = tree.parent
				AND objrp.deleted IS NULL
			INNER JOIN object_data odp
				ON odp.obj_id = objrp.obj_id
			WHERE od.type = %s
		";

		$this->res = $this->db->queryF(
			$query,
			['integer', 'text'],
			[1, 'crs']
		);
	}
}
