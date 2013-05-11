<?php

class MigrationSupportBehavior extends ModelBehavior {

    public $datasource;
    var $default_settings = array(
        'from_encode' => 'SJIS-win',
        'to_encode' => 'UTF-8',
        'create_flag' => true,
        'file_type' => 'csv',
    );

    /**
     * setup
     *
     * @param &$model
     * @param $settings
     */
    function setup(&$model, $settings = array()) {
        $datasource = $model->getDataSource()->config['datasource'];
        if ($datasource != 'Database/Postgres' && $datasource != 'Database/Mysql') {
            //postgresとmysqlのみ対応
            return false;
        }
        $this->datasource = $datasource;
    }

    /**
     * import
     *
     * @param &$model
     * @param $info
     */
    function import(&$model, $info) {
        $info = Set::merge($this->default_settings, $info);
        $fp = fopen($info['csv_file'], "r");
        $body = fread($fp, filesize($info['csv_file']));
        fclose($fp);
        //csvのとき
        if ($info['file_type'] == 'csv') {
            //ファイルの文字コードを変換する
            $encode_body = mb_convert_encoding($body, $info['to_encode'], $info['from_encode']);
            //改行コードを\nに統一(mysqlに合わせる)
            $encode_body = str_replace("\r\n", "\n", $encode_body);
            $encode_body = str_replace("\r", "\n", $encode_body);
        } elseif ($info['file_type'] == 'fixed_length') {
            //固定長のとき
            //改行コードで分割
            $body_explode = explode("\n", $body);
            $body_info = '';
            foreach ($body_explode as $body_explode_key => $body_explode_val) {
                //何もない行（大体最終行)は無視
                if (strlen($body_explode_val) == 0)
                    continue;
                $fixed_count = 0;
                foreach ($info['column_list'] as $column_list_key => $column_list_val) {
                    if ($fixed_count != 0) {
                        $body_info .= ',';
                    }
                    //「"」をつける。
                    $body_info .= '"' . str_replace('"', '""', substr($body_explode_val, $fixed_count, $column_list_val['length'])) . '"';
                    $fixed_count += $column_list_val['length'];
                }
                $body_info .= "\n";
            }
            $encode_body = mb_convert_encoding($body_info, $info['to_encode'], $info['from_encode']);
        }

        $convert_file = TMP . 'convertfile';
        touch($convert_file);
        $convert_fp = fopen($convert_file, "w");
        fwrite($convert_fp, $encode_body);
        fclose($convert_fp);

        if ($info['create_flag'] == true) {
            //テーブル作成
            $sql = $this->__createSQL($info);
            $model->query($sql);
        }

        //COPYコマンド実行
        $sql = $this->__copySQL($info, $convert_file);
        $model->query($sql);

        unlink($convert_file);
    }

    /**
     * __createSQL
     *
     * @param $info
     */
    private function __createSQL($info) {
        $sql = 'CREATE TABLE ' . $info['table_name'] . '(';
        $column_info = '';
        foreach ($info['column_list'] as $column_list) {
            if ($column_info != '') {
                $column_info .= ',';
            }
            $column_info .= $column_list['name'] . ' text';
        }
        $sql .= $column_info;
        $sql .= ');';

        return $sql;
    }

    /**
     * __copySQL
     *
     * @param $info
     * @param $convert_file
     */
    private function __copySQL($info, $convert_file) {
        if ($this->datasource == 'Database/Postgres') {
            $sql = 'COPY ' . $info['table_name'] . ' FROM \'' . $convert_file . '\' WITH CSV;';
        } elseif ($this->datasource == 'Database/Mysql') {
            $sql = 'LOAD DATA LOCAL INFILE "' . $convert_file . '" INTO TABLE ' . $info['table_name'] . ' FIELDS TERMINATED BY \',\' ENCLOSED BY \'"\';';
        } else {
            //エラー処理
        }
        return $sql;
    }

}

