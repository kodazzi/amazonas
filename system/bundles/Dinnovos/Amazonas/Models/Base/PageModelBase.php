<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("pages")
*/
Class PageModelBase
{
	const table = 'as_pages';
	const title = 'title';
	const primary = 'id';
	const hasTimestampable = true;
	public function getFieldsSluggable()
	{
		return array("title");
	}

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\Base\CategoryPages' => array('field' => 'category' , 'fieldLocal' => 'category_id' ),
			'Dinnovos\Amazonas\Models\Base\Page' => array('field' => 'parent' , 'fieldLocal' => 'parent_id' ),
		);
	}
}