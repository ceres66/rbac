<?php
#TODO: test on sqlite

if ($adapter=="pdo_mysql")
{
	try {
		Jf::$Db=new PDO("mysql:host={$host};dbname={$dbname}",$user,$pass);
	}
	catch (PDOException $e)
	{
		if ($e->getCode()==1049) //database not found
			InstallPDOMySQL($host,$user,$pass,$dbname);
		else
			throw $e;
	}
}
elseif ($adapter=="pdo_sqlite")
{
	if (!file_exists($dbname))
		InstallPDOSQLite($host,$user,$pass,$dbname);
	else
		Jf::$Db=new PDO("sqlite:{$dbname}",$user,$pass);
// 		Jf::$Db=new PDO("sqlite::memory:",$user,$pass);
}
else # default to mysqli 
{
	Jf::$Db=new mysqli($host,$user,$pass,$dbname);
	if(Jf::$Db->connect_errno==1049);
		InstallMySQLi($host,$user,$pass,$dbname);
}
function GetSQLs($dbms)
{
	$sql=file_get_contents(__DIR__."/sql/{$dbms}.sql");
	$sql=str_replace("PREFIX_",Jf::tablePrefix(),$sql);
	return explode(";",$sql);
}
function InstallPDOMySQL($host,$user,$pass,$dbname)
{
	$sqls=GetSQLs("mysql");
	$db=new PDO("mysql:host={$host};",$user,$pass);
	$db->query("CREATE DATABASE {$dbname}");
	$db->query("USE {$dbname}");
	if (is_array($sqls))
		foreach ($sqls as $query)
		$db->query($query);
	Jf::$Db=new PDO("mysql:host={$host};dbname={$dbname}",$user,$pass);
	Jf::$RBAC->reset(true);
}
function InstallPDOSQLite($host,$user,$pass,$dbname)
{
	Jf::$Db=new PDO("sqlite:{$dbname}",$user,$pass);
	$sqls=GetSQLs("sqlite");
	if (is_array($sqls))
		foreach ($sqls as $query)
		Jf::$Db->query($query);
	Jf::$RBAC->reset(true);
}
function InstallMySQLi($host,$user,$pass,$dbname)
{
	$sqls=GetSQLs("mysql");
	$db=new mysqli($host,$user,$pass);
	$db->query("CREATE DATABASE {$dbname}");
	$db->select_db($dbname);
	if (is_array($sqls))
		foreach ($sqls as $query)
		$db->query($query);
	Jf::$Db=new mysqli($host,$user,$pass,$dbname);
	Jf::$RBAC->reset(true);
}