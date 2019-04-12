<?php

namespace ILIAS\Plugin\TodoLists\Types;

class Explorer
{

	protected $iter;
	
	protected $results = array();
	/**
	 * Explorer constructor.
	 */
	public function __construct()
	{
		global $DIC;
		$this->iter = new CourseIterator($DIC->database());
		foreach ($this->iter as $crsData) {
			$crs = \ilObjectFactory::getInstanceByRefId($crsData['ref_id'], false);
			if (!$crs || !($crs instanceof \ilObjCourse)) {
				continue;
			}
			$children = array_filter($DIC->repositoryTree()->getSubTree($crsData, true), function (array $child) use ($crs) {
				return (int)$crs->getRefId() !== (int)$child['child'];
			});

			$this->results[$children->child] = $children;
		}
	}

	/**
	 * @return array
	 */
	public function getResults()
	{
		return $this->results;
	}
}