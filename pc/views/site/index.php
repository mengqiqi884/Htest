<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use mdm\admin\components\MenuHelper;


pc\assets\AppAsset::register($this);

$this->title = 'H_test';
$url=Yii::$app->params['base_url'].Yii::$app->params['base_file'];
?>
<div class="body-content home" data-page-no="1">
    <section id="features_area" class="section_padding text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Features you’ll love</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="single_feature wow slideInUp" data-wow-duration="1s">
                        <i class="fa fa-user"></i>
                        <h3>Personal touch</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem.
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="single_feature wow slideInUp" data-wow-duration="2s">
                        <i class="fa fa-flag"></i>
                        <h3>Personal touch</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem.
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="single_feature wow slideInUp" data-wow-duration="3s">
                        <i class="fa fa-paint-brush"></i>
                        <h3>Personal touch</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end of story area -->
    <div class="copyrights">Collect from <a href="http://www.cssmoban.com/" >网页模板</a></div>

    <section id="clients_say_area" class="section_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Blog you’ll love</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="clients_say wow slideInUp">
                        <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.</p>
                        <div class="clients_say_list fix">
                            <div class="say floatleft">
                                <h5>Joe Doe</h5>
                                <h6>Creative Deirector at Gmoogle.com</h6>
                            </div>
                            <div class="c_img floatright">
                                <img src="<?=Url::to('@web/images/client.png')?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="clients_say wow slideInUp">
                        <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.</p>
                        <div class="clients_say_list fix">
                            <div class="say floatleft">
                                <h5>Joe Doe</h5>
                                <h6>Creative Deirector at Gmoogle.com</h6>
                            </div>
                            <div class="c_img floatright">
                                <img src="<?=Url::to('@web/images/client.png')?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="clients_say wow slideInUp">
                        <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.</p>
                        <div class="clients_say_list fix">
                            <div class="say floatleft">
                                <h5>Joe Doe</h5>
                                <h6>Creative Deirector at Gmoogle.com</h6>
                            </div>
                            <div class="c_img floatright">
                                <img src="<?=Url::to('@web/images/client.png')?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="clients_say wow slideInUp">
                        <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.</p>
                        <div class="clients_say_list fix">
                            <div class="say floatleft">
                                <h5>Joe Doe</h5>
                                <h6>Creative Deirector at Gmoogle.com</h6>
                            </div>
                            <div class="c_img floatright">
                                <img src="<?=Url::to('@web/images/client.png')?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end clients say area -->

    <section id="Experience_area" class="section_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="experience wow slideInLeft">
                        <h2>Our Experience</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem.</p>
                        <a href="">View case studies</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="skills">
                        <h2>Skills</h2>
                        <div class="skill_right">

                            <p>C++ development</p>
                            <div class="progress wow slideInUp">
                                <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%;">
                                    60%
                                </div>
                            </div>

                            <p>Photoshop</p>
                            <div class="progress wow slideInUp">
                                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                                    60%
                                </div>
                            </div>

                            <p>.Net</p>
                            <div class="progress wow slideInUp">
                                <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%;">
                                    60%
                                </div>
                            </div>

                            <p>Argular js</p>
                            <div class="progress wow slideInUp">
                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                                    60%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end experience area -->

    <section id="blog_area" class="section_padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="single_blog wow slideInUp" data-wow-duration="1s">
                        <img class="img-responsive" src="<?=Url::to('@web/images/1.jpg')?>" alt="">
                        <h2>Cache Invalidation StrategiesWith Varnish Cache</h2>
                        <div class="comment_area fix">
                            <div class="date floatleft">
                                <p><span><i class="fa fa-calendar-o"></i></span> March 4, 2014</p>
                            </div>
                            <div class="comment floatright">
                                <p><span><i class="fa fa-comments"></i></span>2 comments</p>
                            </div>
                        </div>
                        <p>Shortly thereafter, I was working with RetailMeNot, tasked with designing its iOS and Android app tutorial. The product team wanted to make sure hat users were clear about the value</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="single_blog wow slideInUp" data-wow-duration="2s">
                        <img class="img-responsive" src="<?=Url::to('@web/images/2.jpg')?>" alt="">
                        <h2>Cache Invalidation StrategiesWith Varnish Cache</h2>
                        <div class="comment_area fix">
                            <div class="date floatleft">
                                <p><span><i class="fa fa-calendar-o"></i></span> March 4, 2014</p>
                            </div>
                            <div class="comment floatright">
                                <p><span><i class="fa fa-comments"></i></span>2 comments</p>
                            </div>
                        </div>
                        <p>Shortly thereafter, I was working with RetailMeNot, tasked with designing its iOS and Android app tutorial. The product team wanted to make sure hat users were clear about the value</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="single_blog wow slideInUp" data-wow-duration="3s">
                        <img class="img-responsive" src="<?=Url::to('@web/images/3.jpg')?>" alt="">
                        <h2>Cache Invalidation StrategiesWith Varnish Cache</h2>
                        <div class="comment_area fix">
                            <div class="date floatleft">
                                <p><span><i class="fa fa-calendar-o"></i></span> March 4, 2014</p>
                            </div>
                            <div class="comment floatright">
                                <p><span><i class="fa fa-comments"></i></span>2 comments</p>
                            </div>
                        </div>
                        <p>Shortly thereafter, I was working with RetailMeNot, tasked with designing its iOS and Android app tutorial. The product team wanted to make sure hat users were clear about the value</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end blog area -->

    <section id="caal_to_action_area">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="call_to_action_text wow slideInLeft" data-wow-duration="2s">
                        <h2>Are you ready to go?</h2>
                        <p>Here the call to action area. Lorem ipsum dolor sit amet and click to the button</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="call_project text-right wow slideInRight" data-wow-duration="2s">
                        <a href="">Start your project</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end call to action area -->
</div>

<script>
    new WOW().init();

    $(function(){
        activeTab(0); //第一个Tab
    })
</script>