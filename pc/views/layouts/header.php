<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/9/6
 * Time: 13:47
 */
use \yii\helpers\Url;

?>
<header id="header_area">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="logo">
                    <h2><a href="javascript:void(0);">Companyname</a></h2>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="mainmenu">
                    <div class="navbar navbar-nobg">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>

                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="<?=Url::toRoute(['site/index']);?>" data-no="1">Home</a></li>
                                <li><a href="<?=Url::toRoute(['page/index']);?>" data-no="2">About</a></li>
                                <li><a href="<?=Url::toRoute(['forum/index']);?>" data-no="3">blog</a></li>
                                <li><a href="<?=Url::toRoute(['car/index']);?>" data-no="4">Portfolio</a></li>
                                <li><a href="<?=Url::toRoute(['good/index']);?>" data-no="5">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="header_area_text">
                    <h2 class="wow slideInDown" data-wow-duration="2s">We’re here to create your
                        online presense and style</h2>
                    <p class="wow slideInUp">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mattis orci dapibus risus dignissim, viverra pellentesque arcu ullamcorper. Mauris a tincidunt lectus. Proin nec venenatis quam. </p>
                    <a class="wow slideInUp" data-wow-duration="2s" href="">START  TODAY  with  us</a>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end header top area -->
