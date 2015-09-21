<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("slides")
*/
Class SlideModelBase
{
	const table = 'as_slides';
	const title = 'sequence';
	const primary = 'id';
	const hasTimestampable = true;
    const modelLanguage = 'Dinnovos\Amazonas\Models\LanguageModel';
    const modelTranslation = 'Dinnovos\Amazonas\Models\SlideTranslationModel';

	public function getDefinitionRelations()
	{
		return array(
			'Dinnovos\Amazonas\Models\CarouselModel' => array('field' => 'carousel' , 'fieldLocal' => 'carousel_id' ),
		);
	}
}