<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("carousels_translation")
*/
Class CarouselTranslationModelBase
{
	const table = 'as_carousels_translation';
	const title = 'title';
	const primary = 'id';

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\CarouselModel' => array('field' => 'translatable' , 'fieldLocal' => 'translatable_id' ),
			'Dinnovos\Amazonas\Models\LanguageModel' => array('field' => 'language' , 'fieldLocal' => 'language_id' ),
		);
	}
}