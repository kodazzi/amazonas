<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("pages")
*/
Class PageModelBase
{
	const table = 'as_pages';
	const title = 'sequence';
	const primary = 'id';
	const hasTimestampable = true;
    const modelLanguage = 'Dinnovos\Amazonas\Models\LanguageModel';
    const modelTranslation = 'Dinnovos\Amazonas\Models\PageTranslationModel';

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\CategoryPageModel' => array('field' => 'category' , 'fieldLocal' => 'category_id' ),
			'Dinnovos\Amazonas\Models\PageModel' => array('field' => 'parent' , 'fieldLocal' => 'parent_id' ),
		);
	}
}