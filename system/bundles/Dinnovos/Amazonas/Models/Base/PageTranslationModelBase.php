<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("pages_translation")
*/
Class PageTranslationModelBase
{
	const table = 'as_pages_translation';
	const title = 'title';
	const primary = 'id';

	public function getFieldsSluggable()
	{
		return array("title");
	}

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\PageModel' => array('field' => 'translatable' , 'fieldLocal' => 'translatable_id' ),
			'Dinnovos\Amazonas\Models\LanguageModel' => array('field' => 'language' , 'fieldLocal' => 'language_id' ),
		);
	}
}