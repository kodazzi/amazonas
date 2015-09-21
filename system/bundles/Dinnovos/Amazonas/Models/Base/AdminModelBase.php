<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("admins")
*/
Class AdminModelBase
{
	const table = 'as_admins';
	const title = 'first_name';
	const primary = 'id';
	const hasTimestampable = true;
}