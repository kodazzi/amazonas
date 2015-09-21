<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("categories_posts_translation")
*/
Class CategoryPostTranslationModelBase
{
	const table = 'as_categories_posts_translation';
	const title = 'title';
	const primary = 'id';

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\CategoryPostModel' => array('field' => 'translatable' , 'fieldLocal' => 'translatable_id' ),
			'Dinnovos\Amazonas\Models\LanguageModel' => array('field' => 'language' , 'fieldLocal' => 'language_id' ),
		);
	}
}