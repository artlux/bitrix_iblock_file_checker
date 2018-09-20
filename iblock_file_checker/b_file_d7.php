<?
class FilenTable extends \Bitrix\Main\Entity\DataManager
{
	public static function getTableName()
	{
		return 'b_file';
	}
	public static function getMap()
	{
		return array(
			'ID' => new \Bitrix\Main\Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'TIMESTAMP_X' => new \Bitrix\Main\Entity\DatetimeField('TIMESTAMP_X', array(
				'default_value' => new \Bitrix\Main\Type\DateTime
			)),
			'MODULE_ID' => new \Bitrix\Main\Entity\StringField('MODULE_ID', array(
				'validation' => array(__CLASS__, 'validateModuleId'),
			)),
			'HEIGHT' => new \Bitrix\Main\Entity\IntegerField('HEIGHT'),
			'WIDTH' => new \Bitrix\Main\Entity\IntegerField('WIDTH'),
			'FILE_SIZE' => new \Bitrix\Main\Entity\IntegerField('FILE_SIZE'),
			'CONTENT_TYPE' => new \Bitrix\Main\Entity\StringField('CONTENT_TYPE', array(
				'validation' => array(__CLASS__, 'validateContentType'),
			)),
			'SUBDIR' => new \Bitrix\Main\Entity\StringField('SUBDIR', array(
				'validation' => array(__CLASS__, 'validateSubdir'),
			)),
			'FILE_NAME' => new \Bitrix\Main\Entity\StringField('FILE_NAME', array(
				'validation' => array(__CLASS__, 'validateFileName'),
				'required' => true,
			)),
			'ORIGINAL_NAME' => new \Bitrix\Main\Entity\StringField('ORIGINAL_NAME', array(
				'validation' => array(__CLASS__, 'validateOriginalName'),
			)),
			'DESCRIPTION' => new \Bitrix\Main\Entity\StringField('DESCRIPTION', array(
				'validation' => array(__CLASS__, 'validateDescription'),
			)),
			'HANDLER_ID' => new \Bitrix\Main\Entity\StringField('HANDLER_ID', array(
				'validation' => array(__CLASS__, 'validateHandlerId'),
			)),
			'EXTERNAL_ID' => new \Bitrix\Main\Entity\StringField('EXTERNAL_ID', array(
				'validation' => array(__CLASS__, 'validateExternalId'),
			)),
		);
	}

	public static function validateModuleId()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 50),
		);
	}

	public static function validateContentType()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 255),
		);
	}

	public static function validateSubdir()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 255),
		);
	}

	public static function validateFileName()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 255),
		);
	}

	public static function validateOriginalName()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 255),
		);
	}

	public static function validateDescription()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 255),
		);
	}

	public static function validateHandlerId()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 50),
		);
	}

	public static function validateExternalId()
	{
		return array(
			new \Bitrix\Main\Entity\Validator\Length(null, 50),
		);
	}

	public static function add(array $data)
	{
		throw new NotImplementedException("Use CFile class.");
	}

	public static function update($primary, array $data)
	{
		throw new NotImplementedException("Use CFile class.");
	}

	public static function delete($primary)
	{
		throw new NotImplementedException("Use CFile class.");
	}
}