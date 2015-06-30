<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("categories_post")
*/
Class CategoryPostModelBase
{
	const table = 'as_categories_post';
	const title = 'title';
	const primary = 'id';
	const hasTimestampable = true;
	public function getFieldsSluggable()
	{
		return array("title");
	}

}