function resize_window(){
    // var h = $(window).height() - $("#header").height() - 50;
    //
    // $("#main").css("min-height", h);
    var h = $(window).height() - $("#header").height() - 210;
    $(".row-area").css("min-height", h);
}

function number_format(n){
    if(n.toString().length < 2) return '0' + n.toString() ;
    else return n;
}

function clock(){
    var d = new Date();
    var w = ['일', '월', '화', '수', '목', '금', '토']
    var t =
        d.getFullYear() + '년 ' +
        (d.getMonth() + 1 )+ '월 ' +
        d.getDate() + '일' +
        '(' + w[d.getDay()] + ')  ' +
        d.getHours() + ':' +
        number_format(d.getMinutes()) + ':' +
        number_format(d.getSeconds())  ;
    if(d.getMinutes() == 0 && d.getSeconds() == 0) get_weather();

    $("#header").html(t);
}

function get_date(){
    var dt = new Date();
    var y = dt.getFullYear();
    var m = (dt.getUTCMonth() + 1).toString.length == 1 ? "0" +(dt.getUTCMonth() + 1) : (dt.getUTCMonth() + 1);
    var d = dt.getDay().toString.length == 1 ? "0" +dt.getDate() : dt.getDate();
    return y+m+d;
}

function get_meal(){

    var targetDt = get_date();

    var url = "	https://open.neis.go.kr/hub/mealServiceDietInfo?Type=json&pIndex=1&pSize=100&ATPT_OFCDC_SC_CODE=J10&SD_SCHUL_CODE=7530167&MLSV_YMD="+targetDt;

    $.ajax({
        url: url,
        type: "get",
        dataType: "json",
        success:function(d){
            try{

                if(d.mealServiceDietInfo[0].head[1].RESULT.CODE == "INFO-000"){
                    var data = d.mealServiceDietInfo[1].row[0].DDISH_NM.toString().split('<br/>')
                    var meal = '';
                    // console.log(data);
                    for (var i = 0 ; i < data.length ; i++){
                        if (data[i].lastIndexOf(' ') != -1){
                            meal = meal + data[i].substring(0, data[i].lastIndexOf(' ')) + '<br>';
                        }else{
                            meal = meal + '<br/>';
                        }

                    }

                     // console.log(data);
                    $("#meals").html(meal);
                }
            }
            catch(e){
                $("#meals").html(d.RESULT.MESSAGE);
            }


        },
        error:function(e){
            console.log(e);
        }
    });

}

function get_weather(){
    var url = 'http://apis.data.go.kr/1360000/VilageFcstInfoService_2.0/getVilageFcst';
    var rows = 12 * 13;
    var data = {
        'serviceKey' : '+u0nmv8wU91rnJI3K0UjQWG5q5auxYR7JwspQWBYAVt3nytEpRtIx2z/XSf04a55j4KtSiAcBuCYSOs4TOS30A==',
        'dataType' : 'JSON',
        'base_date' : get_date(),
        'base_time' : '0500',
        'nx' : 63,
        'ny' : 124,
        'numOfRows' : rows
    }
    var tmp = []; //기온
    var pop = []; //강수 혹률
    var tmn = []; //최저기온
    var tmx = []; //최고기온
    var sky = []; //맑음(1), 구름많음(3), 흐림(4)
    var pty = []; //없음(0), 비(1), 비/눈(2), 눈(3), 소나기(4)

    $.ajax({
        url: url,
        type: 'get',
        data: data,
        dataType: 'json',
        success:function (d){
            // console.log(d.response.body.items.item);
            var arr = d.response.body.items.item;
            for( var i = 0 ; i < arr.length ; i++){
                if( arr[i].category == 'TMP' ) tmp.push(arr[i].fcstValue);
                else if (arr[i].category == 'POP') pop.push(arr[i].fcstValue);
                else if (arr[i].category == 'SKY') sky.push(arr[i].fcstValue);
                else if (arr[i].category == 'PTY') pty.push(arr[i].fcstValue);
            }
            // console.log(tmp);
            //0600 => [0]   , time- 6
            var dt = new Date();
            var h = dt.getHours() - 6;


            var sky_str = ['','sun','','cloud','cloud','soundcloud fa-brands'];
            var sky_fa = '<i class="fa-solid text-indigo fa-5x fa-'+sky_str[sky[h]]+'"></i>';
            $("#tmp").html(tmp[h] + '<small>°C</small>');
            $("#sky").html(sky_fa);

        }
    });
}

function get_data(idx){
    let url="read.php";
    let n = idx;
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data:{'idx':idx},
        async: false,
        success:function(d){
            console.log(d);
            if(d.result == 1){
                n = d.idx;

                $("#notice").html(d.msg.replace('<img ', '<img class="img-fluid"'));
            }
        },
        error:function (e){
            console.log(e);
        }
    });
    return n;
}