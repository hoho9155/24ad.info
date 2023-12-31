<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

use App\Helpers\DBTool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Check & Drop Indexes
 *
 * @param string $tableName
 * @param string $indexName
 */
function checkAndDropIndex(string $tableName, string $indexName): void
{
	try {
		$isMariaDb = DBTool::isMariaDB();
		if ($isMariaDb) {
			checkAndDropIndexMariaDb($tableName, $indexName);
		} else {
			checkAndDropIndexMySql($tableName, $indexName);
		}
	} catch (\PDOException $e) {
		dd($e);
	}
	
	checkAndDropIndexLaravel($tableName, $indexName);
}

/**
 * Check & Drop Index using MySQL
 *
 * @param string $tableName
 * @param string $indexName
 */
function checkAndDropIndexMySql(string $tableName, string $indexName): void
{
	$tableNameWithPrefix = DB::getTablePrefix() . $tableName;
	$idxDb = DB::connection()->getDatabaseName();
	
	$sql = [
		'Key_name'   => 'SHOW INDEX FROM `' . $tableNameWithPrefix . '` FROM `' . $idxDb . '`;',
		'INDEX_NAME' => 'SELECT DISTINCT INDEX_NAME
						 FROM `INFORMATION_SCHEMA`.`STATISTICS`
						 WHERE `TABLE_SCHEMA` = \'' . $idxDb . '\'
							AND `TABLE_NAME` = \'' . $tableNameWithPrefix . '\'',
	];
	
	// Exception for MySQL 8
	$isMySql8OrGreater = (!DBTool::isMariaDB() && DBTool::isMySqlMinVersion('8.0'));
	$indexColumn = $isMySql8OrGreater ? 'INDEX_NAME' : 'Key_name';
	
	$results = DB::select($sql[$indexColumn]);
	
	if (is_array($results) && count($results) > 0) {
		$results = collect($results)->mapWithKeys(function ($item) use ($indexColumn) {
			$indexNameLocal = $item->{$indexColumn} ?? null;
			
			return [$indexNameLocal => $indexNameLocal];
		})->toArray();
		
		if (in_array($indexName, $results)) {
			$sql = "ALTER TABLE `" . $tableNameWithPrefix . "` DROP INDEX " . $indexName . ";" . "\n";
			DB::unprepared($sql);
		}
	}
}

/**
 * Check & Drop Index using MariaDB
 *
 * @param string $tableName
 * @param string $indexName
 */
function checkAndDropIndexMariaDb(string $tableName, string $indexName): void
{
	$tableNameWithPrefix = DB::getTablePrefix() . $tableName;
	$idxDb = DB::connection()->getDatabaseName();
	
	$sql = 'show indexes from `' . $tableNameWithPrefix . '` in `' . $idxDb . '`;';
	$results = DB::select($sql);
	
	if (is_array($results) && count($results) > 0) {
		$results = collect($results)->mapWithKeys(function ($item) {
			$indexNameLocal = $item->Key_name ?? null;
			
			return [$indexNameLocal => $indexNameLocal];
		})->toArray();
		
		if (in_array($indexName, $results)) {
			$sql = "DROP INDEX `" . $indexName . "` ON `" . $tableNameWithPrefix . "`;" . "\n";
			DB::unprepared($sql);
		}
	}
}

/**
 * Check & Drop Index using Laravel
 *
 * @param string $tableName
 * @param string $indexName
 */
function checkAndDropIndexLaravel(string $tableName, string $indexName): void
{
	Schema::table($tableName, function ($table) use ($tableName, $indexName) {
		$sm = Schema::getConnection()->getDoctrineSchemaManager();
		$indexesFound = $sm->listTableIndexes(DB::getTablePrefix() . $tableName);
		
		$indexRawName = DB::getTablePrefix() . $tableName . '_' . $indexName . '_index';
		if (array_key_exists($indexRawName, $indexesFound)) {
			$table->dropIndex([$indexName]);
		}
	});
}
