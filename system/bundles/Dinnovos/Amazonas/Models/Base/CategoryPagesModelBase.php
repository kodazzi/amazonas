<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("categories_pages")
*/
Class CategoryPagesModelBase
{
	const table = 'as_categories_pages';
	const title = 'title';
	const primary = 'id';
	const hasTimestampable = true;
	public function getFieldsSluggable()
	{
		return array("title");
	}

}