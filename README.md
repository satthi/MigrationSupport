***********************************************************************

���̃v���O�C���̓f�[�^�ڍs�x���v���O�C���ł��B
CSV��Œ蒷�t�@�C����Postgres��COPY/Mysql��LOAD DATA�𗘗p����
�e�[�u���ɒ��ڎ�荞�݂܂��B
��ʃf�[�^�ڍs�̍ۂȂǂŁA��UDB�Ɏ�荞�񂾂̂��ɏ������s�������ꍇ��
�g�p���Ă��������B

***********************************************************************

�g����

�@�ʏ�ʂ�Plugin��ݒu�B

�A���f����
<pre>
public $actsAs = array('MigrationSupport.MigrationSupport');
</pre>
���L�q�B

�B�L�q���@�i�����ł̓��f���ŋL�q�����Ă��܂��B���̑��ŌĂԏꍇ�͂��ꑊ���ɏ��������Ă��������B)

(CSV�t�@�C��)
<pre>
    public function csv() {
        //�A�b�v���[�h����CSV�t�@�C���̃p�X���w��
        $csv_file = TMP . 'iko.csv';
        //�ڍs����e�[�u�������w��(�e�[�u���͎��O�ɍ쐬����K�v�͂Ȃ�)
        $table_name = 'tmp_iko';
        //CSV�t�@�C���̃J���������J�������w��(�ߕs���Ȃ��悤�ɁB�����DB�G���[����������)
        $column_list = array(
            array('name' => 'demo1'),
            array('name' => 'demo2'),
            array('name' => 'demo3'),
        );
        $info = array(
            'csv_file' => $csv_file,
            'table_name' => $table_name,
            'column_list' => $column_list,
            //CSV�t�@�C���̕����R�[�h
            'from_encode' => 'SJIS-win',
            //�f�[�^�x�[�X�̕����R�[�h
            'to_encode' => 'UTF-8',
            //�e�[�u�����쐬���邩�ǂ����B
            'create_flag' => true,
        );
        //behavior�̃��\�b�h�Ăэ���
        $this->import($info);
    }
</pre>

(�Œ蒷�t�@�C��)
<pre>
    public function fixed_length() {
        //�A�b�v���[�h�����Œ蒷�t�@�C���̃p�X���w��
        $csv_file = TMP . 'kote.txt';
        //�ڍs����e�[�u�������w��(�e�[�u���͎��O�ɍ쐬����K�v�͂Ȃ�)
        $table_name = 'tmp_iko_kote';
        //CSV�t�@�C���̃J�������y�уo�C�g�����J�������w��(�ߕs���Ȃ��悤�ɁB�����DB�G���[����������)
        $column_list = array(
            array('name' => 'demo1', 'length' => 4),
            array('name' => 'demo2', 'length' => 6),
            array('name' => 'demo3', 'length' => 8),
        );
        $info = array(
            'csv_file' => $csv_file,
            'table_name' => $table_name,
            'column_list' => $column_list,
            //�Œ蒷�t�@�C���̕����R�[�h
            'from_encode' => 'SJIS-win',
            //�f�[�^�x�[�X�̕����R�[�h
            'to_encode' => 'UTF-8',
            'create_flag' => true,
            //�t�@�C���̎�ނ��w��B
            'file_type' => 'fixed_length'
        );
        //behavior�̃��\�b�h�Ăэ���
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