<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("carousels")
*/
Class CarouselModelBase
{
	const table = 'as_carousels';
	const title = 'label';
	const primary = 'id';
	const hasTimestampable = true;
    const modelLanguage = 'Dinnovos\Amazonas\Models\LanguageModel';
    const modelTranslation = 'Dinnovos\Amazonas\Models\CarouselTranslationModel';
}