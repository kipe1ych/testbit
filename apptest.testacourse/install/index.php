<?
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Apptest\Testacourse\Coursetable;

Class apptest_testacourse extends CModule {
    var $MODULE_ID = "apptest.testacourse";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function __construct() {
        $arModuleVersion = array();

        include(__DIR__ . "/version.php");

        if(is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = GetMessage("COURSE_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("COURSE_MODULE_DESCRIPTION");
    }

    function InstallDB() {
        if(Loader::includeModule($this->MODULE_ID)) {
            Coursetable::getEntity()->createDbTable();
            return true;
        }
    }

    function UnInstallDB() {
        if(Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(Coursetable::getTableName());
        }
        return true;
    }

    function InstallEvents() {
        CAgent::AddAgent(
            "Apptest\Testacourse\Agentevents::eventHandler();",
            $this->MODULE_ID,
            "N",
            // 86400,
            10,
            date('d.m.Y H:i:s', (time()+5)),
            "Y",
            date('d.m.Y H:i:s', (time()+10)),
            30
        );
        return true;
    }

    function UnInstallEvents() {
        CAgent::RemoveModuleAgents($this->MODULE_ID);
        return true;
    }

    function InstallFiles() {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/apptest.testacourse/install/components", $_SERVER["DOCUMENT_ROOT"] . "/local/components", true, true);
        return true;
    }

    function UnInstallFiles() {
        DeleteDirFilesEx("/local/components/testcourse");
        return true;
    }

    function DoInstall() {
        global $APPLICATION;
        if(!IsModuleInstalled($this->MODULE_ID)) {
            RegisterModule($this->MODULE_ID);
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
            echo CAdminMessage::ShowNote("Модуль установлен");
        }
    }

    function DoUninstall() {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        echo CAdminMessage::ShowNote("Модуль удален");
    }
}
?>