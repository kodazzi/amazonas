<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("blocks")
*/
Class BlockModelBase
{
	const table = 'as_blocks';
	const title = 'label';
	const primary = 'id';
	const hasTimestampable = true;
    const modelLanguage = 'Dinnovos\Amazonas\Models\LanguageModel';
    const modelTranslation = 'Dinnovos\Amazonas\Models\BlockTranslationModel';
}