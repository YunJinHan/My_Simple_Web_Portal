<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="jinhan Yun">
    <link rel="icon" href="img/favicon.png" type="image/x-icon">

    <title>My Portal</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/freelancer.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<?php
    require_once("database/database.php");
    $database = new Database("localhost");
?>

<body id="page-top" class="index">
    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#page-top">MY PORTAL</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#about">around me?</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#contact">it info</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <img class="img-responsive" src="img/profile.png" alt="">
                    <div class="intro-text">
                        <span class="name">JinHan's Simple Web Portal</span>
                        <hr class="star-origin">
                        <span class="skills">Web Developer</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>around me?</h2>
                    <hr class="star-light">
                </div>
            </div>
            <button type="button" class="refreshButton" onclick="requestPosition()">Refresh</button>
            <div>
                <div id="category">
                    <button id="BK9" data-order="0" type="button" class="category_bg bank">은행</button>
                    <button id="FD6" data-order="1" type="button" class="category_bg mart">식당</button>
                    <button id="PM9" data-order="2" type="button" class="category_bg pharmacy">약국</button>
                    <button id="CE7" data-order="4" type="button" class="category_bg cafe">카페</button>
                    <button id="CS2" data-order="5" type="button" class="category_bg store">편의점</button>
                </div>
                <div id="total"></div>
                <div id="map"></div>
                <div id="List"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="success2" id="contact">
        <?php
            $board_list = $database->get_community();
            $board1_cnt = $database->get_community_cnt($board_list[0]);
            $board2_cnt = $database->get_community_cnt($board_list[1]);

            $category_list = $database->get_category($board_list[1]);
            $category_cnt1 = $database->get_category_cnt($board_list[1],$category_list[0]);
            $category_cnt2 = $database->get_category_cnt($board_list[1],$category_list[1]);
        ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>it info</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <div class="board1">
                        <h3><?= $board_list[0] ?>: <span id="number1"><?= $board1_cnt[0][cnt] ?></span>개</h3>
                        <?php
                            $database->get_news_board($board_list[0]);
                        ?>
                    </div>
                    <div class="board2">
                        <h3><?= $board_list[1] ?>: <span id="number2"><?= $board2_cnt[0][cnt] ?></span>개</h3>
                        <div class="category_button_group">
                            <button type="button" name="button" class="category_button" onclick="showAll(<?= $board2_cnt[0][cnt] ?>,'number2')">All</button>
                            <button type="button" name="button" class="category_button" onclick="showOne('<?= $category_list[0] ?>','number2',<?= $category_cnt1[0][cnt] ?>)"><?= $category_list[0] ?></button>
                            <button type="button" name="button" class="category_button" onclick="showTwo('<?= $category_list[1] ?>','number2',<?= $category_cnt2[0][cnt] ?>)"><?= $category_list[1] ?></button>
                        </div>
                        <?php
                            $database->get_news_board($board_list[1]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h3>About Me</h3>
                        <p>Jinhan Yun<br>Hanyang University<br>Computer Science</p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>Contact Me</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="mailto:yjh9234@gmail.com" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="mailto:yjh9234@naver.com" class="btn-social btn-outline"><i class="fa fa-fw fa-dribbble"></i></a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/in/jinhan-yun-232639136" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>About this page</h3>
                        <p>It was just built for my convenience<br>to search for what surrounds me and <br>display the latest posting on several sites.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; Jinhan Yun 2016
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll hidden-sm hidden-xs hidden-lg hidden-md">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/freelancer.min.js"></script>

    <script src="http://code.jquery.com/jquery-1.11.0.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js" type="text/javascript"></script>
    <script src="//apis.daum.net/maps/maps3.js?apikey=XXXXX&libraries=services" type="text/javascript"></script>
    <script src="https://openapi.map.naver.com/openapi/v3/maps.js?clientId=XXXXX&submodules=geocoder" type="text/javascript"></script>

    <script src="js/infoMap.js" type="text/javascript"></script>
</body>

</html>
