<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("categories_pages")
*/
Class CategoryPageModelBase
{
	const table = 'as_categories_pages';
	const title = 'label';
	const primary = 'id';
	const hasTimestampable = true;
    const modelLanguage = 'Dinnovos\Amazonas\Models\LanguageModel';
    const modelTranslation = 'Dinnovos\Amazonas\Models\CategoryPageTranslationModel';
}