<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("users")
*/
Class UserModelBase
{
	const table = 'as_users';
	const title = 'first_name';
	const primary = 'id';
	const hasTimestampable = true;

}