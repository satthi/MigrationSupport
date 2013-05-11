<?php

//App::uses('Behavior', 'Model');
App::uses('MigrationSupportBehavior', 'MigrationSupport.Model/Behavior');

/**
 * Test Case
 *
 */
class MigrationSupportBehaviorTest extends CakeTestCase {

    public $fixtures = array('core.article');

    public function setUp() {
        parent::setUp();
        $this->Article = ClassRegistry::init('Article');
        $this->Article->Behaviors->attach('MigrationSupport.MigrationSupport');
        $this->testFileDir = dirname(__FILE__) . '/../../../TestFile/';
        $this->datasource = $this->Article->getDataSource()->config['datasource'];
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->Article);
        ClassRegistry::flush();
    }

    private function __dropTestTable($table_name) {
        $sql = 'DROP TABLE IF EXISTS ' . $table_name . ';';
        $this->Article->query($sql);
    }

    private function __getResult($table_name) {
        $model_name = Inflector::classify($table_name);
        if ($this->datasource == 'Database/Postgres') {
            $sql = 'SELECT demo1 AS "' . $model_name . '__demo1",demo2 AS "' . $model_name . '__demo2",demo3 AS "' . $model_name . '__demo3" FROM ' . $table_name . ';';
        } elseif ($this->datasource == 'Database/Mysql') {
            $sql = 'SELECT `' . $model_name . '`.`demo1`,`' . $model_name . '`.`demo2`,`' . $model_name . '`.`demo3` FROM ' . $table_name . ' AS `' . $model_name . '`;';
        }
        return $this->Article->query($sql);
    }

    public function testCSV基本() {
        $csv_file = $this->testFileDir . 'csv1.csv';
        $table_name = 'test_suite_tmp_csv';
        $model_name = Inflector::classify($table_name);
        $column_list = array(
            array('name' => 'demo1'),
            array('name' => 'demo2'),
            array('name' => 'demo3'),
        );
        $info = array(
            'csv_file' => $csv_file,
            'table_name' => $table_name,
            'column_list' => $column_list,
            'from_encode' => 'SJIS-win',
            'to_encode' => 'UTF-8',
            'create_flag' => true,
        );
        //もしテーブルが残っていた場合は削除
        $this->__dropTestTable($table_name);
        $this->Article->import($info);

        $result = $this->__getResult($table_name);

        $expected = array(
            array($model_name =>
                array('demo1' => 'a', 'demo2' => 'b', 'demo3' => 'c'),
            ),
            array($model_name =>
                array('demo1' => 'd', 'demo2' => 'e', 'demo3' => 'f'),
            ),
            array($model_name =>
                array('demo1' => 'g', 'demo2' => 'a"a', 'demo3' => 'i'),
            ),
            array($model_name =>
                array('demo1' => 'あ', 'demo2' => 'い', 'demo3' => 'う'),
            ),
        );

        $this->assertTrue($result === $expected);
        $this->__dropTestTable($table_name);
    }

    public function test固定長基本() {
        $kote_file = $this->testFileDir . 'fixed_length1.txt';
        $table_name = 'test_suite_tmp_kote';
        $model_name = Inflector::classify($table_name);
        $column_list = array(
            array('name' => 'demo1', 'length' => 4),
            array('name' => 'demo2', 'length' => 6),
            array('name' => 'demo3', 'length' => 8),
        );
        $info = array(
            'csv_file' => $kote_file,
            'table_name' => $table_name,
            'column_list' => $column_list,
            'from_encode' => 'SJIS-win',
            'to_encode' => 'UTF-8',
            'create_flag' => true,
            'file_type' => 'fixed_length'
        );
        $this->__dropTestTable($table_name);
        $this->Article->import($info);
        $result = $this->__getResult($table_name);
        $expected = array(
            array($model_name =>
                array('demo1' => 'aaaa', 'demo2' => 'あああ', 'demo3' => 'ほげ　　'),
            ),
            array($model_name =>
                array('demo1' => 'ｂｂ', 'demo2' => '123456', 'demo3' => '7890　　'),
            ),
            array($model_name =>
                array('demo1' => 'ほげ', 'demo2' => 'asvscs', 'demo3' => 'ほげ　　'),
            ),
            array($model_name =>
                array('demo1' => 'aaaa', 'demo2' => '　"a" ', 'demo3' => 'mikeぇえ'),
            ),
        );
        $this->assertTrue($result === $expected);
        $this->__dropTestTable($table_name);
    }

}