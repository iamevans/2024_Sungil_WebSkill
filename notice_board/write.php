<?php
require_once "header.php";
require_once "HwanMysql.php";

$host = "localhost";
$user = "root";
$pw = "";
$db = "sungil";
$connect = new HwanMysql($host, $user, $pw, $db);
$query = "select * from notice order by  idx desc limit 0, 10";
$res = $connect->selectQuery1($query, []);

?>
    <div class="container-fluid">
        <div class="row">
            <div class="card col-md-8">
                <div class="card-header ">
                    <div class="card-title">
                        Write form
                    </div>

                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" onclick="set_write('new', '')">저장</button>
                    </div>

                </div>
                <div class="card-body">
                    <div id="summernote"></div>
                </div>

            </div>
            <div class="col-md-4">

                <div class="card text-center">
                    <div class="alert alert-warning">
                        클릭하시면 왼쪽에 복사 됩니다.
                    </div>
                    <?php
                    foreach ($res as $lt){
                        ?>
                        <div class="card ">
                            <div class="card-body text-wrap hover" onclick="copy_data(this);">
                                <?=$lt['msg']?>
                            </div>
                            <div class="card-footer">
                                <?=$lt['reg_date']?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

            </div>

        </div>

        
    </div>
    <script>
        $('#summernote').summernote({
            height: 600,
            focus: true,
            toolbar: [
                ['style', ['style']],
                ['fontsize', ['fontsize']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['help', ['help']]
            ],
            fontSizes: ['8','9','10','11','12','13','14','15','16','17','18','19','20','24','30','36','44','55','66','82','150'],
        });

        function set_write(mode, idx){
            let msg = $("#summernote").summernote('code');
            let url = "write_ok.php";
            $.ajax({
                url : url,
                data : {
                    'msg' : msg,
                    'mode' : mode
                },
                type : 'post',
                success: function (d){
                    console.log(d);
                    if(d > -1){
                        alert(d);
                    }
                }
            });

        }

        function copy_data(t){
           $("#summernote").summernote('code', $(t).html());
        }
    </script>

<?php
require_once "footer.php";
