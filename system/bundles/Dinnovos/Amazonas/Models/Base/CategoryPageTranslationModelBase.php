<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("categories_pages_translation")
*/
Class CategoryPageTranslationModelBase
{
	const table = 'as_categories_pages_translation';
	const title = 'title';
	const primary = 'id';

	public function getFieldsSluggable()
	{
		return array("title");
	}

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\CategoryPageModel' => array('field' => 'translatable' , 'fieldLocal' => 'translatable_id' ),
			'Dinnovos\Amazonas\Models\LanguageModel' => array('field' => 'language' , 'fieldLocal' => 'language_id' ),
		);
	}
}