<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("posts")
*/
Class PostModelBase
{
	const table = 'as_posts';
	const title = 'sequence';
	const primary = 'id';
	const hasTimestampable = true;
    const modelLanguage = 'Dinnovos\Amazonas\Models\LanguageModel';
    const modelTranslation = 'Dinnovos\Amazonas\Models\PostTranslationModel';

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\CategoryPostModel' => array('field' => 'category' , 'fieldLocal' => 'category_id' ),
		);
	}
}