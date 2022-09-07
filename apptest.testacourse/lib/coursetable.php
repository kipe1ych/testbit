<?
namespace Apptest\Testacourse;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\Validator;

class Coursetable extends DataManager {
    public static function getTableName() {
        return 'testcourses';
    }
    
    public static function getMap() {
        return array(
            new IntegerField(
                'ID',
                array(
                    'autocomplete' => true,
                    'primary' => true
                )
            ),
            new StringField(
                'CODE',
                array(
                    'validation' => function () {
                        return array(
                            new Validator\Length(null, 255),
                        );
                    },
                )
            ),
            new DatetimeField(
                'DATE',
                array()
            ),
            new FloatField(
                'COURSE',
                array()
            )
        );
    }
}