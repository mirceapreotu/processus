<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/13/11
 * Time: 2:26 AM
 * To change this template use File | Settings | File Templates.
 */

abstract class App_GaintS_Lib_Logger_AbstractLogger
{
    public abstract function setLogType($logType = "default");

    public abstract function writeLog();
}
