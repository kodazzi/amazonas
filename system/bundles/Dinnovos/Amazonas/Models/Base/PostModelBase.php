<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("posts")
*/
Class PostModelBase
{
	const table = 'as_posts';
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
			'Dinnovos\Amazonas\Models\Base\CategoryPost' => array('field' => 'category' , 'fieldLocal' => 'category_id' ),
		);
	}
}