<?php
require_once "header.php";

?>
<!--    <div class="content-wrapper" >-->
        <div class="container-fluid m-0 p-0 " id="content"  >
            <div class="card card-primary m-0 p-0 shadow-none">
                <div class="card-header p-3 text-80 font-weight-bold text-center" id="header" >
                    0:00:00
                </div>
                <div class="card-body" id="main" >
                    <div class="row">
                        <div class="col-8">
                            <div class="card card-row card-outline card-danger">
                                <div class="card-header text-80 text-center" id="notice-title">
                                    <i class="fa-solid fa-person-chalkboard"></i> 공지사항
                                </div>
                                <div class="card-body row-area " id="notice">
                                    
                                    

                                </div>
                            </div>


                        </div>
                        <div class="col-4">
                            <div class="card card-row card-outline card-success">
                                <div class="card-header text-80 text-center">
                                    <i class="fa-solid fa-circle-info"></i> Information
                                </div>
                                <div class="card-body row-area ">


                                    <div class="small-box ">
                                        <div class="inner bg-danger rounded-top text-center">
                                            <h2>오늘의 급식</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fa-solid fa-stroopwafel fa-spin"></i>
                                        </div>
                                        <div class="bg-white h1 small-box-footer p-3 border border-danger" >
                                            <div id="meals" class="align-self-end"></div>
                                        </div>
                                    </div>

                                    <div class="small-box ">
                                        <div class="inner bg-warning text-center">
                                            <h2>오늘의 날씨</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fa-solid fa-sun fa-spin"></i>
                                        </div>
                                        <div class="bg-white h1 small-box-footer p-3 border border-warning">
                                            <div id="weather" class="align-self-end ">
                                                <div class="info-box shadow-none h3">
                                                    <div class="overlay pr-3 pb-3 border-0 text-80" id="tmp">
                                                        <i class="fa-solid fa-question fa-spin"></i>
                                                    </div>
                                                    <div class="info-box-content border-0">
                                                        <span class="info-box-number " id="sky">
                                                            <i class="fa fa-spin fa-progress"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

<!--    </div>-->
    <script>
        let idx = -1;

        $(document).ready(function () {
            resize_window();
            get_meal();
            get_weather();
            setInterval(function (){
                clock();
                idx = get_data(idx);
            }, 1000);
        });
        $(window).resize(function(){
            resize_window();
        });



    </script>

<?php
require_once "footer.php";