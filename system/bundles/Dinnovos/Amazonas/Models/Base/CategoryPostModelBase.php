<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("categories_posts")
*/
Class CategoryPostModelBase
{
	const table = 'as_categories_posts';
	const title = 'label';
	const primary = 'id';
	const hasTimestampable = true;
    const modelLanguage = 'Dinnovos\Amazonas\Models\LanguageModel';
    const modelTranslation = 'Dinnovos\Amazonas\Models\CategoryPostTranslationModel';
}