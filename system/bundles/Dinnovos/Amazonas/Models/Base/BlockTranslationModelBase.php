<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("blocks_translation")
*/
Class BlockTranslationModelBase
{
	const table = 'as_blocks_translation';
	const title = 'title';
	const primary = 'id';

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\BlockModel' => array('field' => 'translatable' , 'fieldLocal' => 'translatable_id' ),
			'Dinnovos\Amazonas\Models\LanguageModel' => array('field' => 'language' , 'fieldLocal' => 'language_id' ),
		);
	}
}