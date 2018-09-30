<?php
include_once('link.php');
$m_id=isset($_GET['m_id'])&&trim($_GET['m_id'])?trim($_GET['m_id']):0;
$mid=isset($_GET['mid']) && trim($_GET['mid']) ? trim($_GET['mid']):0;

$sys_map_citySql="SELECT * from sys_map_city where smsid=1";
$sys_map_citySqlResult=$link->prepare($sys_map_citySql);
$sys_map_citySqlResult->execute();
$sys_map_citySqlRows=$sys_map_citySqlResult->fetchall();

$sys_map_areaSql="SELECT * from sys_map_area";
$sys_map_areaSqlResult=$link->prepare($sys_map_areaSql);
$sys_map_areaSqlResult->execute();
$sys_map_areaSqlRows=$sys_map_areaSqlResult->fetchall();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <title>迷你足球報名系統</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta property="og:image" content="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:url" content="" />
    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
      <!--vaildate-->
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css" rel="stylesheet">
      <!--日期選擇器css-->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
      <!--日期選擇器上面那個會跟validate衝突,不要開-->
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
      <!--日期選擇器-->
    <style>
     .error{color:red;}
    </style>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/all.css" rel="stylesheet">
    <link href="font/flaticon.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="dist/css/bootstrap-datepicker3.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

    <body>
        <!--表頭-->
        <header class="head_title">
            <div class="title">
                <h2>新增球員資訊</h2>
            </div>
        </header>
        <!--表頭 end-->
        <!--主體 -->
        <form class="" method="post" id="addform" name="addform" action="">
        <section class="main main_bottom">
            <div class="form_row">
                <div class="form-group">
                    <label for="">球員姓名*</label>
                    <input type="text" class="form-control" id="name_player" name="name_player" placeholder="">
                </div>
                <div class="form-group">
                    <label for="">出生年月日*</label>
                <div class="input-group date datepicker">

                        <input type="text" readonly class="form-control" id="birth" name="birth" placeholder="2010/05/06">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="">身分證字號或護照號碼*</label>
                    <input type="text" class="form-control" id="id_card" name="id_card" placeholder="A123456789">
                </div>

                <div class="form-group">
                    <label for="">家長姓名*</label>
                    <input type="text" class="form-control" id="name_parents" name="name_parents" placeholder="">
                </div>

                <div class="form-group">
                    <label for="">聯絡電話(手機)*</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="09xxxxxxxx">
                </div>

                <div class="form-group">
                    <label for="">通訊地址</label>
                    <select class="form-control" id="smcid" name="smcid">
                      <?php
                          echo '<option value="">請選擇縣市</option>';
                          foreach($sys_map_citySqlRows as $smc)
                          {
                            //$factor=$playerSqlRows[0]['smcid']==$smc['smcid']?'selected':'';
                            echo '<option value="'.$smc['smcid'].'">'.$smc['city'].'</option>';
                          }
                      ?>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="smaid" name="smaid">
                      <?php
                          echo '<option value="">請選擇鄉鎮市區</option>';
                          // foreach($sys_map_areaSqlRows as $sma)
                          // {
                          //   echo '<option value="'.$sma['smaid'].'"'.$factor.'>'.$sma['area'].'</option>';
                          // }
                      ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="address" name="address" value="" placeholder="">
                </div>

                <div class="form-group">
                    <label for="">球衣背號</label>
                    <input type="text" class="form-control" id="clothes_back_num" name="clothes_back_num" placeholder="">
                </div>
                <div class="form-group">
                    <label for="">球衣尺寸（身高：公分）</label>
                    <!-- <input type="text" class="form-control"  id="clothes_size" name="clothes_size"> -->
                    <select class="form-control" id="clothes_size" name="clothes_size">
                      <option value="0">請選擇</option>
                      <option value="120">120</option>
                      <option value="125">125</option>
                      <option value="130">130</option>
                      <option value="135">135 (原本的S)</option>
                      <option value="140">140</option>
                      <option value="145">145 (原本的M)</option>
                      <option value="150">150</option>
                      <option value="155">155 (原本的L)</option>
                      <option value="160">160 (原本的XL)</option>
                      <option value="165">165 (原本的2XL)</option>
                      <option value="170">170 (原本的3XL)</option>
                      <option value="175">175 (原本的4XL)</option>
                      <option value="180">180 (原本的5XL)</option>
                    </select>
                </div>
            </div>

        </section>
        <!-- <a class="green_btn" id="add_send" name="add_send" value="確認新增" onclick="AddSend();">確認新增</a> -->
        <input class="green_btn" type="button" id="add_send" name="add_send" value="確認新增" onclick="AddSend();">
        </form>
        <!--主體 end-->

    </body>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
      <!--日期選擇器上面那個會跟validate衝突-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- <script src="js/bootstrap.min.js"></script>
    <script src="js/all.js"></script>
    <script type="text/javascript" src="dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="dist/locales/bootstrap-datepicker.zh-CN.min.js" charset="UTF-8"></script> -->
    <script type="">
      function AddSend()
      {
        // validate.手機號碼驗證
        jQuery.validator.addMethod("isMobile", function(value, element) {
            var length = value.length;
            var mobile = /^09[0-9]{2}[0-9]{6}$/;
            return this.optional(element) || (length == 10 && mobile.test(value));
        }, "請正確填寫您的手機");

        // validate.身分證驗證
        jQuery.validator.addMethod("isIdCardNo", function(value, element) {
            var length = value.length;
            var id_card = /^[A-Z]{1}[1-2]{1}[0-9]{1}[0-9]{7}$/;
            return this.optional(element) || (length == 10 && id_card.test(value));
        }, "請正確填寫您的身分證");
        //vaildate主要程式
        var validform=$("#addform").validate(
          {
            rules:
             {
                name_player: "required",
                birth: {
                  required: true,
                  dateISO: true
                },
                id_card: {
                  required: true,
                  // isIdCardNo:true,
                  // minlength: 10,
                  // maxlength: 10
                },
                name_parents: "required",
                mobile: {
                  required: true,
                  isMobile:true,
                  minlength: 10,
                  maxlength: 10
                },
                // smcid: "required",
                // smaid: "required",
                // address: "required",
                // clothes_back_num: {
                //   required: true,
                //   digits: true,
                //   minlength: 1,
                //   maxlength: 2
                // },
                // clothes_size: "required"
             },
            messages: {
              name_player: "請輸入球員名字",
              birth: {
                required:"请输入球員出生年月日",
                dateISO: "請輸入正確格式YYYY/mm/dd"
              },
              id_card: {
                required: "請輸入身分證護照號碼",
                // isIdCardNo:"例:A123456789(字母大寫)",
                // minlength: "請符合身分證格式",
                // maxlength: "請符合身分證格式"
              },
              name_parents: "請輸入球員家長名字",
              mobile: {
                required: "請輸入手機號碼",
                isMobile: "請輸入09開頭的10碼號碼",
                minlength: "不可小於10碼",
                maxlength: "不可大於10碼"
              },
              // smcid: "請選擇縣市",
              // smaid: "請選擇鄉鎮市區",
              // address: "請輸入地址",
              // clothes_back_num: {
              //   required:"請輸入球員背號",
              //   digits:"請輸入數字",
              //   minlength: "不可小於1碼",
              //   maxlength: "不可大於2碼"
              // },
              // clothes_size: "請輸入球員球衣尺寸"
            }
        });
        var chkResult=validform.form();
        if (chkResult==true)
        {
          const link='player_add_ajax.php';
          const name_player=$('#name_player').val();
          const birth=$('#birth').val();
          const id_card=$('#id_card').val();
          const name_parents=$('#name_parents').val();
          const mobile=$('#mobile').val();
          const smcid=$('#smcid').val()?$('#smcid').val():0;
          const smaid=$('#smaid').val()?$('#smaid').val():0;
          const address=$('#address').val()?$('#address').val():0;
          const clothes_back_num=$('#clothes_back_num').val()?$('#clothes_back_num').val():0;
          const clothes_size=$('#clothes_size').val()?$('#clothes_size').val():0;

          $.ajax(
            {
              url: link,
              type:"post",
              cache: true,
              async:false,
              datatype:"json",
              data:
                {
                  "mid":<?php echo $mid;?>,
                  "name_player":name_player,
                  "birth":birth,
                  "id_card":id_card,
                  "name_parents":name_parents,
                  "mobile":mobile,
                  "smcid":smcid,
                  "smaid":smaid,
                  "address":address,
                  "clothes_back_num":clothes_back_num,
                  "clothes_size":clothes_size
                },
              error:function(data)
                {
                  var dataobj=$.parseJSON($.trim(data));
                  if(dataobj.status="error")
                  {
                    alert("填寫失敗");
                  }
                },
              success:function(data)
                {
                  // console.log(data);// return;
                  var dataobj=$.parseJSON($.trim(data));
                  if(dataobj.status=="success")
                    {
                      alert("填寫成功");
                      window.location='player_index.php?<?php echo 'm_id='.$m_id.'&mid='.$mid;?>';
                    }
                }
           });
        }
      }

      //區域連動
      $(document).ready(function()
      {
            //利用jQuery的ajax把縣市編號(CNo)傳到Town_ajax.php把相對應的區域名稱回傳後印到選擇區域(鄉鎮)下拉選單
            $('#smcid').change(function()
            {
                var CNo= $('#smcid').val();
                $.ajax(
                {
                    type: "POST",
                    url: 'player_update_city_ajax.php',
                    cache: false,
                    data:{'CNo':CNo},
                    error: function()
                    {
                        alert('Ajax request 發生錯誤');
                    },
                    success: function(data)
                    {
                        $('#smaid').html(data);
                    }
                });
            });
        });

      //日期選擇器
      $(document).ready(function()
      {
        var opt=
        {
           dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
           dayNamesMin:["日","一","二","三","四","五","六"],
           monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
           monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
           prevText:"上月",
           nextText:"次月",
           weekHeader:"週",
           showMonthAfterYear:true,
           dateFormat:"yy/mm/dd",
           changeYear: true,
           yearRange: "-50:+0",
           //changeMonth: true,
        };
        $("#birth").datepicker(opt);
      });

/*
      $(function(){
          $('.datepicker').datepicker({
              language: 'zh-CN-2'
          });
      })
*/
    </script>

</html>
