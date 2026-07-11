@php
    if (!Session::has('locale')) {
        Session::put('locale', 'en');
        App::setLocale('en');
    }
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title>Monexity - Professional Investment Platform</title>
<link rel="icon" href="https://azim.hostlin.com/Counsolve/assets/images/favicon-2.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/font-awesome-all.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/flaticon.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/owl.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/bootstrap.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/jquery.fancybox.min.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/animate.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/nice-select.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/color.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/style.css" rel="stylesheet">
<link href="https://azim.hostlin.com/Counsolve/assets/css/responsive.css" rel="stylesheet">
<style>
#live-activity-tooltip {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    pointer-events: auto;
    max-width: 320px;
}
.live-activity-card {
    background: rgba(10, 36, 73, 0.92);
    color: #ffffff;
    border-radius: 18px;
    box-shadow: 0 22px 42px rgba(0, 0, 0, 0.24);
    padding: 1rem 1.1rem;
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(18px);
    transform: translateX(40px);
    opacity: 0;
    animation: live-activity-in 0.45s ease forwards;
    position: relative;
    pointer-events: auto;
}
.live-activity-card.hide {
    opacity: 0;
    transform: translateX(40px);
}
.live-activity-close {
    position: absolute;
    top: 6px;
    right: 8px;
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
    padding: 0;
}
.live-activity-title {
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 0.4rem;
}
.live-activity-subtitle {
    font-size: 0.8rem;
    opacity: 0.76;
    line-height: 1.5;
}
.live-activity-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    background: rgba(255,255,255,0.12);
    color: #fdfdfd;
    margin-top: 0.55rem;
}
@keyframes live-activity-in {
    0% {
        opacity: 0;
        transform: translateX(40px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}
.nav-language-box {
    display: flex;
    align-items: center;
}
.nav-language-box .language-box select {
    border-radius: 4px;
    padding: 8px 10px;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.08);
    color: #fff;
    min-width: 130px;
}
.nav-language-box .language-box .flag-icon {
    display: inline-flex;
    align-items: center;
}
.mobile-language-box select {
    width: 100%;
    border-radius: 4px;
    padding: 8px 10px;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.08);
    color: #fff;
}
</style>
</head>
<body>
<div class="boxed_wrapper home_2">
<div id="live-activity-tooltip"></div>

    <!-- preloader -->
    <div class="loader-wrap">
        <div class="preloader">
            <div class="preloader-close">x</div>
            <div id="handle-preloader" class="handle-preloader">
                <div class="animation-preloader">
                    <div class="spinner"></div>
                    <div class="txt-loading">
                        <span data-text-preloader="M" class="letters-loading">M</span>
                        <span data-text-preloader="O" class="letters-loading">O</span>
                        <span data-text-preloader="N" class="letters-loading">N</span>
                        <span data-text-preloader="E" class="letters-loading">E</span>
                        <span data-text-preloader="X" class="letters-loading">X</span>
                        <span data-text-preloader="I" class="letters-loading">I</span>
                        <span data-text-preloader="T" class="letters-loading">T</span>
                        <span data-text-preloader="Y" class="letters-loading">Y</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- preloader end -->

    <!-- main header -->
    <header class="main-header header-style-two">
        <div class="header-top-two">
            <div class="outer-container">
                <ul class="info-list clearfix">
                    <!--<li>-->
                    <!--    <div class="icon-box"><img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-9.png" alt=""></div>-->
                    <!--    {{ __('messages.talk_to_us') }} <a href="mailto:support@Monexity.online"><span>support@Monexity.online</span></a>-->
                    <!--</li>-->
                    <!--<li>-->
                    <!--    <div class="icon-box"><img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-10.png" alt=""></div>-->
                    <!--    {{ __('messages.available') }} <span>{{ __('messages.mon_sat') }}</span>-->
                    <!--</li>-->
                    <li>
                        <div class="icon-box">
                            <img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-9.png" alt="">
                        </div>
                        {{ __('messages.talk_to_us') }}
                        <a href="mailto:support@Monexity.online">
                            <span>support@Monexity.online</span>
                        </a>
                    </li>
                    
                    <li>
                        <div class="icon-box">
                            <img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-10.png" alt="">
                        </div>
                        {{ __('messages.available') }}
                        <span>{{ __('messages.mon_sat') }}</span>
                    </li>
                    
                    <li class="language-switcher">
                        <div class="icon-box">
                            <i class="fas fa-language"></i>
                        </div>
                    
                        <a href="javascript:void(0)" onclick="switchLanguage('en')" title="English">
                            <img src="https://flagcdn.com/w20/gb.png"
                                 alt="English"
                                 style="width:20px;height:15px;margin-right:8px;">
                        </a>
                    
                        <a href="javascript:void(0)" onclick="switchLanguage('pt')" title="Português">
                            <img src="https://flagcdn.com/w20/br.png"
                                 alt="Português"
                                 style="width:20px;height:15px;">
                        </a>
                        
                        <a href="javascript:void(0)" onclick="switchLanguage('es')" title="Spanish">
                            <img src="https://flagcdn.com/w20/es.png"
                                 alt="Spanish"
                                 style="width:20px;height:15px;">
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="header-lower">
            <div class="outer-container">
                <div class="outer-box">
                    <figure class="logo-box">
                        <a href="{{ url('/') }}">
                            <img src="https://Monexity.online/public/img/logo-2.png"
                                 alt="Monexity"
                                 style="width: 120px; height: auto;">
                        </a>
                    </figure>
                    <div class="menu-area">
                        <div class="mobile-nav-toggler">
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                        </div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    <li class="current"><a href="{{ url('/') }}">{{ __('messages.home') }}</a></li>
                                    <li><a href="{{ url('/about') }}">{{ __('messages.about_us') }}</a></li>
                                    <li><a href="{{ url('/services') }}">{{ __('messages.services') }}</a></li>
                                    <li><a href="{{ url('/plans') }}">{{ __('messages.investment_plans') }}</a></li>
                                    <li><a href="{{ url('auth/login') }}">{{ __('messages.login') }}</a></li>
                                </ul>
                            </div>
                        </nav>
                        <div class="menu-right-content">
                            <div class="support-box nav-language-box" style="margin-right:10px;">
                                <div class="language-box" style="display:flex;align-items:center;gap:8px;">
                                    <span class="flag-icon" style="font-size:1.1rem;">🇵🇹</span>
                                    <select class="selectmenu language-switcher" onchange="switchLanguage(this.value)" style="min-width:120px;">
                                        <option value="pt" {{ app()->getLocale() == 'pt' ? 'selected' : '' }}>Português</option>
                                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                            </div>
                            <div class="support-box">
                                <a href="{{ url('auth/register') }}" class="theme-btn btn-two" style="padding:10px 24px;text-decoration:none">
                                    {{ __('messages.register_now') }}
                                </a>
                            </div>
                            <div class="support-box" style="margin-left:10px">
                                <a href="{{ url('auth/login') }}" style="color:#fff;text-decoration:none;font-weight:600">
                                    {{ __('messages.login') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- sticky Header -->
        <div class="sticky-header">
            <div class="outer-container">
                <div class="outer-box">
                    <figure class="logo-box"><a href="{{ url('/') }}"><img src="https://Monexity.online/public/img/logo-2.png"
                                 alt="Monexity"
                                 style="width: 120px; height: auto;"></a></figure>
                    <div class="menu-area clearfix">
                        <nav class="main-menu clearfix"></nav>
                        <div class="menu-right-content">
                            <div class="support-box nav-language-box" style="margin-right:10px;">
                                <div class="language-box" style="display:flex;align-items:center;gap:8px;">
                                    <span class="flag-icon" style="font-size:1.1rem;">🇵🇹</span>
                                    <select class="selectmenu language-switcher" onchange="switchLanguage(this.value)" style="min-width:120px;">
                                        <option value="pt" {{ app()->getLocale() == 'pt' ? 'selected' : '' }}>Português</option>
                                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                            </div>
                            <div class="support-box">
                                <a href="{{ url('auth/register') }}" class="theme-btn btn-two" style="padding:8px 20px;text-decoration:none">{{ __('messages.register') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- main-header end -->

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><i class="fas fa-times"></i></div>
        <nav class="menu-box">
            <div class="nav-logo"><a href="{{ url('/') }}"><img src="https://Monexity.online/public/img/logo-2.png"
                                 alt="Monexity"
                                 style="width: 120px; height: auto;"></a></div>
            <div class="menu-outer"></div>
            <div class="mobile-language-box" style="padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.08);">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                    <span style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:0.95rem;">🇵🇹 {{ __('messages.global') }}</span>
                    <select class="selectmenu language-switcher" onchange="switchLanguage(this.value)" style="min-width:120px;">
                        <option value="pt" {{ app()->getLocale() == 'pt' ? 'selected' : '' }}>Português</option>
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
            </div>
            <div class="contact-info">
                <h4>{{ __('messages.contact_info') }}</h4>
                <ul>
                    <li><a href="mailto:support@Monexity.online">support@Monexity.online</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- End Mobile Menu -->

    <!-- banner-section -->
    <section class="banner-style-two">
        <div class="banner-carousel owl-theme owl-carousel">
            <div class="slide-item style-one">
                <div class="image-layer" style="background-image:url(https://azim.hostlin.com/Counsolve/assets/images/banner/banner-4.jpg)"></div>
                <div class="auto-container">
                    <div class="content-box">
                        <h6>{{ __('messages.invest_under_expertise') }}</h6>
                        <h2>{{ __('messages.professional_advisers') }}</h2>
                        <p>{{ __('messages.grow_wealth') }}</p>
                        <div class="btn-box">
                            <a href="{{ url('/plans') }}" class="theme-btn btn-two">{{ __('messages.view_plans') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-item">
                <div class="image-layer" style="background-image:url(https://azim.hostlin.com/Counsolve/assets/images/banner/banner-5.jpg)"></div>
                <div class="auto-container">
                    <div class="content-box">
                        <h6>{{ __('messages.plans_never_fail') }}</h6>
                        <h2>{{ __('messages.thinking_planning') }}</h2>
                        <p>{{ __('messages.join_thousands') }}</p>
                        <div class="btn-box">
                            <a href="{{ url('auth/register') }}" class="theme-btn btn-two">{{ __('messages.start_investing_today') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-item">
                <div class="image-layer" style="background-image:url(https://azim.hostlin.com/Counsolve/assets/images/banner/banner-6.jpg)"></div>
                <div class="auto-container">
                    <div class="content-box">
                        <h6>{{ __('messages.smart_investor') }}</h6>
                        <h2>{{ __('messages.smart_direction') }}</h2>
                        <p>{{ __('messages.diversified_maximum') }}</p>
                        <div class="btn-box">
                            <a href="{{ url('/services') }}" class="theme-btn btn-two">{{ __('messages.our_services') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner-section end -->

    <!-- about-style-two -->
    <section class="about-style-two sec-pad">
        <div class="pattern-layer" style="background-image: url(https://azim.hostlin.com/Counsolve/assets/images/shape/shape-13.png);"></div>
        <div class="auto-container">
            <div class="sec-title">
                <span class="sub-title">{{ __('messages.about_us_title') }}</span>
                <h2>{{ __('messages.expert_guidance') }}</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 inner-column">
                    <div class="inner-box">
                        <div class="experience-box">
                            <h2>8+</h2>
                            <h6>{{ __('messages.years_successful') }}</h6>
                        </div>
                        <ul class="list-item clearfix">
                            <li>{{ __('messages.forex_crypto') }}</li>
                            <li>{{ __('messages.real_estate_commodities') }}</li>
                            <li>{{ __('messages.risk_managed') }}</li>
                        </ul>
                        <div class="btn-box">
                            <a href="{{ url('/about') }}" class="theme-btn btn-two">{{ __('messages.learn_more') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div class="content-box">
                        <h3>{{ __('messages.global_platform') }}</h3>
                        <div class="text-box">
                            <p><span>I</span>{{ __('messages.about_text_1') }}</p>
                            <p>{{ __('messages.about_text_2') }}</p>
                        </div>
                        <h5>{{ __('messages.explore_legacy') }}</h5>
                        <div class="quote-box">
                            <div class="icon-box"><i class="flaticon-quote"></i></div>
                            <h4>{{ __('messages.earning_magic') }}</h4>
                            <h6>Monexity</h6>
                            <span class="designation">{{ __('messages.trusted_partner') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about-style-two end -->

    <!-- funfact-section -->
    <section class="funfact-section">
        <div class="auto-container">
            <div class="inner-container">
                <div class="bg-layer" style="background-image: url(https://azim.hostlin.com/Counsolve/assets/images/background/funfact-bg.jpg);"></div>
                <div class="row clearfix">
                    <div class="col-lg-5 col-md-12 col-sm-12 content-column">
                        <div class="content-box">
                            <div class="icon-box"><img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-15.png" alt=""></div>
                            <h2>{{ __('messages.handle_challenge') }}</h2>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12 inner-column">
                        <div class="inner-box">
                            <div class="funfact-inner">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 funfact-block">
                                        <div class="funfact-block-one">
                                            <div class="inner-box">
                                                <div class="count-outer count-box">
                                                    <span class="count-text" data-speed="1500" data-stop="50">0</span><span class="text">Million+</span>
                                                </div>
                                                <p>{{ __('messages.total_assets') }}</p>
                                                <div class="link"><a href="{{ url('/plans') }}"><i class="flaticon-diagonal-arrow"></i></a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 funfact-block">
                                        <div class="funfact-block-one">
                                            <div class="inner-box">
                                                <div class="count-outer count-box">
                                                    <span class="count-text" data-speed="1500" data-stop="12000">0</span><span class="text">+</span>
                                                </div>
                                                <p>{{ __('messages.active_investors') }}</p>
                                                <div class="link"><a href="{{ url('auth/register') }}"><i class="flaticon-diagonal-arrow"></i></a></div>
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
    </section>
    <!-- funfact-section end -->

    <!-- service-style-two -->
    <section class="service-style-two sec-pad">
        <div class="pattern-layer" style="background-image: url(https://azim.hostlin.com/Counsolve/assets/images/shape/shape-14.png);"></div>
        <div class="auto-container">
            <div class="sec-title centred light">
                <span class="sub-title">{{ __('messages.services') }}</span>
                <h2>{{ __('messages.our_sectors') }}</h2>
            </div>
            <div class="inner-container">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-6 col-sm-12 service-block">
                        <div class="service-block-one">
                            <div class="inner-box">
                                <div class="icon-box">
                                    <div class="icon"><i class="flaticon-analytics"></i></div>
                                    <span class="count-text">01</span>
                                </div>
                                <h3><a href="{{ url('/services') }}">{{ __('messages.forex_trading') }}</a></h3>
                                <div class="link"><a href="{{ url('/services') }}"><span>{{ __('messages.explore_service') }}</span></a></div>
                                <p>{{ __('messages.forex_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 service-block">
                        <div class="service-block-one">
                            <div class="inner-box">
                                <div class="icon-box">
                                    <div class="icon"><i class="flaticon-office-building"></i></div>
                                    <span class="count-text">02</span>
                                </div>
                                <h3><a href="{{ url('/services') }}">{{ __('messages.bitcoin_mining') }}</a></h3>
                                <div class="link"><a href="{{ url('/services') }}"><span>{{ __('messages.explore_service') }}</span></a></div>
                                <p>{{ __('messages.bitcoin_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 service-block">
                        <div class="service-block-one">
                            <div class="inner-box">
                                <div class="icon-box">
                                    <div class="icon"><i class="flaticon-retirement"></i></div>
                                    <span class="count-text">03</span>
                                </div>
                                <h3><a href="{{ url('/services') }}">{{ __('messages.real_estate_gold') }}</a></h3>
                                <div class="link"><a href="{{ url('/services') }}"><span>{{ __('messages.explore_service') }}</span></a></div>
                                <p>{{ __('messages.real_estate_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- service-style-two end -->

    <!-- pricing-section -->
    <section class="pricing-section sec-pad">
        <div class="auto-container">
            <div class="sec-title centred">
                <span class="sub-title">{{ __('messages.plan_pricing') }}</span>
                <h2>{{ __('messages.effective_plans') }}</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6 col-sm-12 pricing-block">
                    <div class="pricing-block-one">
                        <div class="pricing-table">
                            <div class="table-header">
                                <div class="icon-box"><i class="flaticon-idea"></i></div>
                                <h3>{{ __('messages.bronze_plan') }}</h3>
                                <p>{{ __('messages.perfect_beginners') }}</p>
                            </div>
                            <div class="table-content">
                                <ul class="feature-list clearfix">
                                    <li>{{ __('messages.min_deposit') }}: $100</li>
                                    <li>{{ __('messages.max_deposit') }}: $1,000</li>
                                    <li>{{ __('messages.min_withdrawal') }}: $5</li>
                                    <li>{{ __('messages.profit') }}: 5% {{ __('messages.every_24_hours') }}</li>
                                    <li>24/7 {{ __('messages.support') }}</li>
                                    <li class="light">{{ __('messages.reinvestment_auto') }}</li>
                                </ul>
                                <h2>5<span class="symble">%</span><span class="text">{{ __('messages.every_24_hours') }}</span></h2>
                                <a href="{{ url('auth/register') }}" class="theme-btn btn-two">{{ __('messages.get_started') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pricing-block">
                    <div class="pricing-block-one active-block">
                        <div class="pricing-table">
                            <span class="discount-text">{{ __('messages.most_popular') }}</span>
                            <div class="table-header">
                                <div class="icon-box"><i class="flaticon-star"></i></div>
                                <h3>{{ __('messages.silver_plan') }}</h3>
                                <p>{{ __('messages.serious_investors') }}</p>
                            </div>
                            <div class="table-content">
                                <ul class="feature-list clearfix">
                                    <li>{{ __('messages.min_deposit') }}: $1,000</li>
                                    <li>{{ __('messages.max_deposit') }}: $5,000</li>
                                    <li>{{ __('messages.min_withdrawal') }}: $50</li>
                                    <li>{{ __('messages.profit') }}: 12% {{ __('messages.every_48_hours') }}</li>
                                    <li>{{ __('messages.priority_support') }}</li>
                                    <li>{{ __('messages.reinvestment_auto') }}</li>
                                </ul>
                                <h2>12<span class="symble">%</span><span class="text">{{ __('messages.every_48_hours') }}</span></h2>
                                <a href="{{ url('auth/register') }}" class="theme-btn btn-two">{{ __('messages.get_started') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pricing-block">
                    <div class="pricing-block-one">
                        <div class="pricing-table">
                            <div class="table-header">
                                <div class="icon-box"><i class="flaticon-diamond"></i></div>
                                <h3>{{ __('messages.gold_plan') }}</h3>
                                <p>{{ __('messages.high_yield') }}</p>
                            </div>
                            <div class="table-content">
                                <ul class="feature-list clearfix">
                                    <li>{{ __('messages.min_deposit') }}: $5,000</li>
                                    <li>{{ __('messages.max_deposit') }}: $20,000</li>
                                    <li>{{ __('messages.min_withdrawal') }}: $100</li>
                                    <li>{{ __('messages.profit') }}: 15% {{ __('messages.every_48_hours') }}</li>
                                    <li>{{ __('messages.vip_support') }}</li>
                                    <li>{{ __('messages.reinvestment_auto') }}</li>
                                </ul>
                                <h2>15<span class="symble">%</span><span class="text">{{ __('messages.every_48_hours') }}</span></h2>
                                <a href="{{ url('auth/register') }}" class="theme-btn btn-two">{{ __('messages.get_started') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pricing-block">
                    <div class="pricing-block-one">
                        <div class="pricing-table">
                            <div class="table-header">
                                <div class="icon-box"><i class="flaticon-analytics"></i></div>
                                <h3>{{ __('messages.diamond_plan') }}</h3>
                                <p>{{ __('messages.elite_investors') }}</p>
                            </div>
                            <div class="table-content">
                                <ul class="feature-list clearfix">
                                    <li>{{ __('messages.min_deposit') }}: $20,000</li>
                                    <li>{{ __('messages.max_deposit') }}: {{ __('messages.max_deposit') }}: Unlimited</li>
                                    <li>{{ __('messages.min_withdrawal') }}: $500</li>
                                    <li>{{ __('messages.profit') }}: 20% {{ __('messages.every_48_hours') }}</li>
                                    <li>{{ __('messages.dedicated_manager') }}</li>
                                    <li>{{ __('messages.priority_support') }}</li>
                                </ul>
                                <h2>20<span class="symble">%</span><span class="text">{{ __('messages.every_48_hours') }}</span></h2>
                                <a href="{{ url('auth/register') }}" class="theme-btn btn-two">{{ __('messages.get_started') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- pricing-section end -->

    <!-- chooseus-style-two -->
    <section class="chooseus-style-two sec-pad">
        <span class="big-text">{{ __('messages.benefits') }}</span>
        <div class="auto-container">
            <div class="sec-title">
                <span class="sub-title">{{ __('messages.why_choose') }}</span>
                <h2>{{ __('messages.reasons_choose') }}</h2>
            </div>
            <div class="inner-container">
                <div class="bg-layer" style="background-image: url(https://azim.hostlin.com/Counsolve/assets/images/background/chooseus-bg.jpg);"></div>
                <div class="inner-content clearfix">
                    <div class="chooseus-block-two">
                        <div class="inner-box">
                            <div class="icon-box"><i class="flaticon-downloads"></i></div>
                            <div class="text">Reason<span>01</span></div>
                            <h3>{{ __('messages.secure_platform') }}</h3>
                            <div class="link"><a href="{{ url('auth/register') }}"><span>{{ __('messages.start_now') }}</span></a></div>
                            <p>{{ __('messages.secure_desc') }}</p>
                        </div>
                    </div>
                    <div class="chooseus-block-two">
                        <div class="inner-box">
                            <div class="icon-box"><i class="flaticon-downloads"></i></div>
                            <div class="text">Reason<span>02</span></div>
                            <h3>{{ __('messages.daily_returns') }}</h3>
                            <div class="link"><a href="{{ url('/plans') }}"><span>{{ __('messages.view_plans') }}</span></a></div>
                            <p>{{ __('messages.daily_returns_desc') }}</p>
                        </div>
                    </div>
                    <div class="chooseus-block-two">
                        <div class="inner-box">
                            <div class="icon-box"><i class="flaticon-downloads"></i></div>
                            <div class="text">Reason<span>03</span></div>
                            <h3>{{ __('messages.expert_team') }}</h3>
                            <div class="link"><a href="{{ url('/about') }}"><span>{{ __('messages.learn_more') }}</span></a></div>
                            <p>{{ __('messages.expert_team_desc') }}</p>
                        </div>
                    </div>
                    <div class="chooseus-block-two"></div>
                    <div class="chooseus-block-two">
                        <div class="inner-box">
                            <div class="icon-box"><i class="flaticon-downloads"></i></div>
                            <div class="text">Reason<span>04</span></div>
                            <h3>{{ __('messages.24_7_support') }}</h3>
                            <div class="link"><a href="{{ url('/contact') }}"><span>{{ __('messages.contact_us') }}</span></a></div>
                            <p>{{ __('messages.support_desc') }}</p>
                        </div>
                    </div>
                    <div class="chooseus-block-two">
                        <div class="inner-box">
                            <div class="icon-box"><i class="flaticon-downloads"></i></div>
                            <div class="text">Reason<span>05</span></div>
                            <h3>{{ __('messages.fast_withdrawals') }}</h3>
                            <div class="link"><a href="{{ url('auth/register') }}"><span>{{ __('messages.join_now') }}</span></a></div>
                            <p>{{ __('messages.fast_withdrawals_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- chooseus-style-two end -->

    <!-- working-style-two -->
    <section class="working-style-two sec-pad">
        <div class="auto-container">
            <div class="sec-title centred">
                <span class="sub-title">{{ __('messages.how_it_works') }}</span>
                <h2>{{ __('messages.start_earning') }}</h2>
            </div>
            <div class="inner-container">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-6 col-sm-12 working-block">
                        <div class="working-block-two">
                            <div class="inner-box">
                                <div class="upper-box centred">
                                    <span class="count-text">01</span>
                                    <div class="icon-box"><i class="flaticon-meeting"></i></div>
                                    <h6>{{ __('messages.step') }} 1</h6>
                                    <p>{{ __('messages.register_desc') }}</p>
                                </div>
                                <div class="lower-box">
                                    <h3>{{ __('messages.register_account') }}</h3>
                                    <a href="{{ url('auth/register') }}"><span>{{ __('messages.sign_up_free') }}</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 working-block">
                        <div class="working-block-two">
                            <div class="inner-box">
                                <div class="upper-box centred">
                                    <span class="count-text">02</span>
                                    <div class="icon-box"><i class="flaticon-paper"></i></div>
                                    <h6>{{ __('messages.step') }} 2</h6>
                                    <p>{{ __('messages.choose_plan_desc') }}</p>
                                </div>
                                <div class="lower-box">
                                    <h3>{{ __('messages.choose_plan') }}</h3>
                                    <a href="{{ url('/plans') }}"><span>{{ __('messages.view_plans') }}</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 working-block">
                        <div class="working-block-two">
                            <div class="inner-box">
                                <div class="upper-box centred">
                                    <span class="count-text">03</span>
                                    <div class="icon-box"><i class="flaticon-analysis"></i></div>
                                    <h6>{{ __('messages.step') }} 3</h6>
                                    <p>{{ __('messages.make_deposit_desc') }}</p>
                                </div>
                                <div class="lower-box">
                                    <h3>{{ __('messages.make_deposit') }}</h3>
                                    <a href="{{ url('auth/register') }}"><span>{{ __('messages.deposit_now') }}</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 working-block">
                        <div class="working-block-two">
                            <div class="inner-box">
                                <div class="upper-box centred">
                                    <span class="count-text">04</span>
                                    <div class="icon-box"><i class="flaticon-submit"></i></div>
                                    <h6>{{ __('messages.step') }} 4</h6>
                                    <p>{{ __('messages.earn_withdraw_desc') }}</p>
                                </div>
                                <div class="lower-box">
                                    <h3>{{ __('messages.earn_withdraw') }}</h3>
                                    <a href="{{ url('auth/register') }}"><span>{{ __('messages.get_started') }}</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- working-style-two end -->

    <!-- testimonial-style-two -->
    <section class="testimonial-style-two">
        <div class="outer-container">
            <div class="pattern-layer" style="background-image: url(https://azim.hostlin.com/Counsolve/assets/images/shape/shape-16.png);"></div>
            <span class="big-text">{{ __('messages.praise') }}</span>
            <div class="auto-container">
                <div class="single-item-carousel owl-carousel owl-theme">
                    <div class="testimonial-content">
                        <div class="author-box">
                            <div class="thumb-box">
                                <figure class="thumb"><img src="https://azim.hostlin.com/Counsolve/assets/images/resource/testimonial-1.jpg" alt=""></figure>
                                <div class="icon-box"><img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-16.png" alt=""></div>
                            </div>
                            <h3>Paul Felix</h3>
                            <h5>{{ __('messages.investor_since') }} 2022</h5>
                        </div>
                        <div class="quote-box"><i class="flaticon-quote"></i></div>
                        <p>{{ __('messages.testimonial_1') }}</p>
                    </div>
                    <div class="testimonial-content">
                        <div class="author-box">
                            <div class="thumb-box">
                                <figure class="thumb"><img src="https://azim.hostlin.com/Counsolve/assets/images/resource/testimonial-1.jpg" alt=""></figure>
                                <div class="icon-box"><img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-16.png" alt=""></div>
                            </div>
                            <h3>Maria Gonzalez</h3>
                            <h5>{{ __('messages.gold_plan') }} {{ __('messages.investor_since') }}</h5>
                        </div>
                        <div class="quote-box"><i class="flaticon-quote"></i></div>
                        <p>{{ __('messages.testimonial_2') }}</p>
                    </div>
                    <div class="testimonial-content">
                        <div class="author-box">
                            <div class="thumb-box">
                                <figure class="thumb"><img src="https://azim.hostlin.com/Counsolve/assets/images/resource/testimonial-1.jpg" alt=""></figure>
                                <div class="icon-box"><img src="https://azim.hostlin.com/Counsolve/assets/images/icons/icon-16.png" alt=""></div>
                            </div>
                            <h3>Ismael Carlos</h3>
                            <h5>{{ __('messages.diamond_plan') }} {{ __('messages.investor_since') }}</h5>
                        </div>
                        <div class="quote-box"><i class="flaticon-quote"></i></div>
                        <p>{{ __('messages.testimonial_3') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="image-thumb">
            <ul class="thumb-list clearfix">
                <li><div class="single-item"><div class="icon-box"><i class="flaticon-feedback"></i></div><h5>{{ __('messages.investors_count') }}</h5></div></li>
                <li><div class="single-item"><figure class="image"><img src="https://azim.hostlin.com/Counsolve/assets/images/resource/thumb-2.jpg" alt=""></figure></div></li>
                <li><div class="single-item"><div class="icon-box"><i class="flaticon-cartoon"></i></div><h5>{{ __('messages.avg_rating') }}</h5></div></li>
                <li><div class="single-item"><figure class="image"><img src="https://azim.hostlin.com/Counsolve/assets/images/resource/thumb-3.jpg" alt=""></figure></div></li>
            </ul>
        </div>
    </section>
    <!-- testimonial-style-two end -->

    <!-- subscribe-section -->
    <section class="subscribe-section">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-lg-8 col-md-12 col-sm-12 offset-lg-4 content-column">
                    <div class="content-box">
                        <span class="big-text">{{ __('messages.newsletter') }}</span>
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-6 col-sm-12 text-column">
                                <div class="text-box">
                                    <div class="shape" style="background-image: url(https://azim.hostlin.com/Counsolve/assets/images/shape/shape-17.png);"></div>
                                    <h2>{{ __('messages.stay_updated') }}</h2>
                                    <p>{{ __('messages.subscribe_text') }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 form-column">
                                <div class="form-inner">
                                    <form method="post" action="{{ url('/subscribe') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" name="email" placeholder="{{ __('messages.email_placeholder') }}" required="">
                                            <button type="submit"><i class="flaticon-send"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- subscribe-section end -->

    <!-- footer-style-two -->
    <section class="footer-style-two">
        <div class="auto-container">
            <div class="widget-section">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-6 col-sm-12 footer-column">
                        <div class="footer-widget logo-widget">
                            <div class="widget-content" style="background-image: url(https://azim.hostlin.com/Counsolve/assets/images/resource/footer-1.jpg);">
                                <div class="award-image"><img src="https://azim.hostlin.com/Counsolve/assets/images/icons/award-2.png" alt=""></div>
                                <figure class="footer-logo"><a href="{{ url('/') }}"><img src="https://Monexity.online/public/img/logo-2.png"
                                 alt="Monexity"
                                 style="width: 120px; height: auto;"></a></figure>
                                <h3>{{ __('messages.thinking_future') }}</h3>
                                <div class="btn-box">
                                    <a href="{{ url('auth/register') }}" class="theme-btn btn-two">{{ __('messages.start_investing_today') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 footer-column">
                        <div class="footer-widget contact-widget">
                            <div class="widget-title"><h3>{{ __('messages.get_in_touch') }}</h3></div>
                            <div class="widget-content">
                                <div class="single-item">
                                    <h4>{{ __('messages.support') }}</h4>
                                    <p>{{ __('messages.mon_sat') }}</p>
                                </div>
                                <div class="single-item">
                                    <h4>{{ __('messages.quick_contact') }}</h4>
                                    <p><span>E :</span> <a href="mailto:support@Monexity.online">support@Monexity.online</a></p>
                                    <p><span>W :</span> <a href="{{ url('/') }}">Monexity.online</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12 footer-column">
                        <div class="footer-widget links-widget">
                            <div class="widget-title"><h3>{{ __('messages.company') }}</h3></div>
                            <div class="widget-content">
                                <ul class="links-list clearfix">
                                    <li><a href="{{ url('/') }}">{{ __('messages.home') }}</a></li>
                                    <li><a href="{{ url('/about') }}">{{ __('messages.about_us') }}</a></li>
                                    <li><a href="{{ url('/services') }}">{{ __('messages.services') }}</a></li>
                                    <li><a href="{{ url('/plans') }}">{{ __('messages.investment_plans') }}</a></li>
                                    <li><a href="{{ url('/contact') }}">{{ __('messages.contact') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12 footer-column">
                        <div class="footer-widget links-widget">
                            <div class="widget-title"><h3>{{ __('messages.invest') }}</h3></div>
                            <div class="widget-content">
                                <ul class="links-list clearfix">
                                    <li><a href="{{ url('auth/register') }}">{{ __('messages.register') }}</a></li>
                                    <li><a href="{{ url('auth/login') }}">{{ __('messages.login') }}</a></li>
                                    <li><a href="{{ url('/plans') }}">{{ __('messages.bronze_plan') }}</a></li>
                                    <li><a href="{{ url('/plans') }}">{{ __('messages.silver_plan') }}</a></li>
                                    <li><a href="{{ url('/plans') }}">{{ __('messages.gold_plan') }}</a></li>
                                    <li><a href="{{ url('/plans') }}">{{ __('messages.diamond_plan') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="bottom-inner">
                    <div class="copyright">
                        <p>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">Monexity.</a> {{ __('messages.all_rights') }}</p>
                    </div>
                    <ul class="footer-nav clearfix">
                        <li><a href="{{ url('/terms') }}">{{ __('messages.terms_conditions') }}</a></li>
                        <li><a href="{{ url('/privacy') }}">{{ __('messages.privacy_policy') }}</a></li>
                        <li><a href="{{ url('/contact') }}">{{ __('messages.contact_us') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- footer-style-two end -->

    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="flaticon-up-arrow"></i>
    </button>

</div>

<script src="//code.jivosite.com/widget/FPUADi6LoA" async></script>

<script src="https://azim.hostlin.com/Counsolve/assets/js/jquery.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/popper.min.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/bootstrap.min.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/owl.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/wow.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/validation.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/jquery.fancybox.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/appear.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/scrollbar.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/jquery.nice-select.min.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/nav-tool.js"></script>
<script src="https://azim.hostlin.com/Counsolve/assets/js/script.js"></script>

<script>
function switchLanguage(locale) {
    if (['pt', 'en'].includes(locale)) {
        localStorage.setItem('app_locale', locale);
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("language.switch") }}';
    form.style.display = 'none';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    const localeInput = document.createElement('input');
    localeInput.type = 'hidden';
    localeInput.name = 'locale';
    localeInput.value = locale;
    form.appendChild(localeInput);
    
    document.body.appendChild(form);
    form.submit();
}

(function() {
    const usernames = [
        'AllenWoodrow','LarissaMelo','ThiagoNobrega','CamilaSantos','BrunoAlves',
        'FernandaCosta','RafaelMoura','JulianaPereira','VictorLima','IsabelaRocha',
        'GabrielSilva','MarianaMendes','LucasFernandes','AmandaAraujo','RicardoRibeiro',
        'BiancaFreitas','DanielCardoso','PatriciaCastro','RodrigoNascimento','CarolinaBarbosa',
        'EduardoSousa','MarinaOliveira','FernandoVaz','SofiaMoura','BrunoPinto',
        'LarissaAlmeida','GustavoFernandes','NataliaSantos','DiegoMoreira','IsabelaTeixeira',
        'VitorRocha','LeticiaMendes','MarceloGuimaraes','RenataLopes','AndreCarlos',
        'MicheleFaria','CesarDias','TatianaSilva','MateusLima','PriscilaAzevedo',
        'ThiagoCunha','SandraMartins','PauloRamos','ElaineCosta','HenriqueNascimento',
        'AlinePereira','FabioOliveira','CarlaSouza','RicardoMota','VanessaCavalcante'
    ];

    const actions = [
        { verb: 'acabou de sacar', badge: 'Saque' },
        { verb: 'acaba de investir', badge: 'Investimento' },
        { verb: 'acabou de depositar', badge: 'Depósito' }
    ];

    const container = document.getElementById('live-activity-tooltip');
    if (!container) return;

    let tooltipDisabled = false;
    let timeoutId = null;

    const formatAmount = (value) => {
        return value.toLocaleString('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0, maximumFractionDigits: 0 });
    };

    const renderCard = () => {
        if (tooltipDisabled) return;
        const username = usernames[Math.floor(Math.random() * usernames.length)];
        const action = actions[Math.floor(Math.random() * actions.length)];
        const amount = Math.floor(Math.random() * (1000000 - 500 + 1)) + 500;
        const card = document.createElement('div');
        card.className = 'live-activity-card';
        card.innerHTML = `
            <button class="live-activity-close" aria-label="Close">&times;</button>
            <div class="live-activity-title">${username} ${action.verb} ${formatAmount(amount)}</div>
            <div class="live-activity-subtitle">Notificação em tempo real</div>
            <span class="live-activity-badge">${action.badge}</span>
        `;

        container.innerHTML = '';
        container.appendChild(card);

        const closeBtn = card.querySelector('.live-activity-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                tooltipDisabled = true;
                container.innerHTML = '';
                if (timeoutId) clearTimeout(timeoutId);
            });
        }
    };

    const nextUpdate = () => {
        if (tooltipDisabled) return;
        renderCard();
        const interval = Math.floor(Math.random() * 1000) + 3000;
        timeoutId = setTimeout(nextUpdate, interval);
    };

    document.addEventListener('DOMContentLoaded', nextUpdate);
})();
</script>
</body>
</html>