<?php
namespace Dinnovos\Amazonas\Models\Base;
/** 
* @Table("messages")
*/
Class MessageModelBase
{
	const table = 'as_messages';
	const title = 'fullname';
	const primary = 'id';
	const hasTimestampable = true;
}