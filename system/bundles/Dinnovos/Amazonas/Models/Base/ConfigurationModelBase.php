<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("configurations")
*/
Class ConfigurationModelBase
{
	const table = 'as_configurations';
	const title = 'ds_label';
	const primary = 'id';
	const hasTimestampable = true;

}