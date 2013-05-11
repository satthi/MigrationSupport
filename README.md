***********************************************************************

このプラグインはデータ移行支援プラグインです。
CSVや固定長ファイルをPostgresのCOPY/MysqlのLOAD DATAを利用して
テーブルに直接取り込みます。
大量データ移行の際などで、一旦DBに取り込んだのちに処理を行いたい場合に
使用してください。

***********************************************************************

使い方

①通常通りPluginを設置。

②モデルで
<pre>
public $actsAs = array('MigrationSupport.MigrationSupport');
</pre>
を記述。

③記述方法（ここではモデルで記述をしています。その他で呼ぶ場合はそれ相応に書き換えてください。)

(CSVファイル)
<pre>
    public function csv() {
        //アップロードしたCSVファイルのパスを指定
        $csv_file = TMP . 'iko.csv';
        //移行するテーブル名を指定(テーブルは事前に作成する必要はない)
        $table_name = 'tmp_iko';
        //CSVファイルのカラム名をカラム分指定(過不足ないように。あるとDBエラーが発生する)
        $column_list = array(
            array('name' => 'demo1'),
            array('name' => 'demo2'),
            array('name' => 'demo3'),
        );
        $info = array(
            'csv_file' => $csv_file,
            'table_name' => $table_name,
            'column_list' => $column_list,
            //CSVファイルの文字コード
            'from_encode' => 'SJIS-win',
            //データベースの文字コード
            'to_encode' => 'UTF-8',
            //テーブルを作成するかどうか。
            'create_flag' => true,
        );
        //behaviorのメソッド呼び込み
        $this->import($info);
    }
</pre>

(固定長ファイル)
<pre>
    public function fixed_length() {
        //アップロードした固定長ファイルのパスを指定
        $csv_file = TMP . 'kote.txt';
        //移行するテーブル名を指定(テーブルは事前に作成する必要はない)
        $table_name = 'tmp_iko_kote';
        //CSVファイルのカラム名及びバイト長をカラム分指定(過不足ないように。あるとDBエラーが発生する)
        $column_list = array(
            array('name' => 'demo1', 'length' => 4),
            array('name' => 'demo2', 'length' => 6),
            array('name' => 'demo3', 'length' => 8),
        );
        $info = array(
            'csv_file' => $csv_file,
            'table_name' => $table_name,
            'column_list' => $column_list,
            //固定長ファイルの文字コード
            'from_encode' => 'SJIS-win',
            //データベースの文字コード
            'to_encode' => 'UTF-8',
            'create_flag' => true,
            //ファイルの種類を指定。
            'file_type' => 'fixed_length'
        );
        //behaviorのメソッド呼び込み
        $this->import($info);
    }
</pre>


## License ##

The MIT Lisence

Copyright (c) 2013 Fusic Co., Ltd. (http://fusic.co.jp)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Author ##

Satoru Hagiwara