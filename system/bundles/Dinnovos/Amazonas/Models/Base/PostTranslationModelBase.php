<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("posts_translation")
*/
Class PostTranslationModelBase
{
	const table = 'as_posts_translation';
	const title = 'title';
	const primary = 'id';

	public function getFieldsSluggable()
	{
		return array("title");
	}

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\PostModel' => array('field' => 'translatable' , 'fieldLocal' => 'translatable_id' ),
			'Dinnovos\Amazonas\Models\LanguageModel' => array('field' => 'language' , 'fieldLocal' => 'language_id' ),
		);
	}
}