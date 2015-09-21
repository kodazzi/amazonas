<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("settings")
*/
Class SettingModelBase
{
	const table = 'as_settings';
	const title = 'title';
	const primary = 'id';
	const hasTimestampable = true;
}