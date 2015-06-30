<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("slides")
*/
Class SlideModelBase
{
	const table = 'as_slides';
	const title = 'title';
	const primary = 'id';
	const hasTimestampable = true;

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\Base\Carousel' => array('field' => 'carousel' , 'fieldLocal' => 'carousel_id' ),
		);
	}
}