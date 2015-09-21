<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("slides_translation")
*/
Class SlideTranslationModelBase
{
	const table = 'as_slides_translation';
	const title = 'title';
	const primary = 'id';

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\SlideModel' => array('field' => 'translatable' , 'fieldLocal' => 'translatable_id' ),
			'Dinnovos\Amazonas\Models\LanguageModel' => array('field' => 'language' , 'fieldLocal' => 'language_id' ),
		);
	}
}