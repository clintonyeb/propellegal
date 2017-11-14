<?php
get_template_part('template-parts/header', 'home');
?>

<section class="hero is-fullheight main-hero" id="hero">

        <p id="loader" class="has-text-centered">
            <a class="button is-loading is-large is-dark menu-icon">
                <span class="icon">
                    <i class="fa fa-load"></i>
                </span>
            </a><br>
            Loading video...
            <!-- <a class="button is-loading is-large is-dark" has-text-white>Loading</a> -->
        </p>
        <!-- Hero head: will stick at the top -->
        <div class="hero-head">
            <nav class="navbar is-black">
                <div class="navbar-brand" id="brand-mob">
                    <a  class="navbar-item is-hidden-desktop">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-text.svg" alt="Logo" height="400px">
                    </a>
                    <span class="navbar-burger burger" data-target="main-menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
                <div id="main-menu" class="navbar-menu">
                    <div class="navbar-start">
                        <a class="navbar-item" href="/services">
                            SERVICES
                        </a>
                        <a class="navbar-item" href="/how-it-works">
                            HOW IT WORKS
                        </a>
                        <a class="navbar-item" href="/pricing">
                            PRICING
                        </a>
                    </div>

                    <div class="navbar-center is-hidden-touch">
                        <div id="brand">
                            <a href="/">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-text.svg" alt="Logo">
                            </a>
                        </div>
                    </div>

                    <div class="navbar-end">
                        <span class="navbar-item">
                            <a class="button is-dark menu-icon" href="/search">
                                <span class="icon is-small">
                                    <i class="fa fa-search"></i>
                                </span>
                            </a>
                        </span>
                        <span class="navbar-item">
                            <a class="button is-dark menu-icon" href="/user">
                                <span class="icon is-small">
                                    <i class="fa fa-user"></i>
                                </span>
                            </a>
                        </span>
                    </div>
                </div>
            </nav>
        </div>

        <video autoplay loop preload muted data-src="<?php echo get_stylesheet_directory_uri(); ?>/assets/videos/cover">
        </video>

        <div class="hero-body">
            <div class="content">
                <h2 class="subtitle has-text-centered"><span class="b"> MAKERS</span> &amp; <span class="b">THINKERS</span> WELCOME
                </h2>


                <div class="columns hero-butts is-hidden-touch is-mobile is-multiline">
		    <div class="column">
                        <p class="has-text-centered" data-scroll="example">
                            <a class="button is-white is-outlined is-large">Create a Document</a>
                        </p>
                    </div>
		    <div class="column">
                        <p class="has-text-centered" data-scroll="upload">
                            <a class="button is-white is-outlined is-large">Document Review</a>
                        </p>
                    </div>
                    <div class="column">
                        <p class="has-text-centered" data-scroll="register">
                            <a class="button is-white is-outlined is-large">Business Formation</a>
                        </p>
                    </div>
                    <div class="column">
                        <p class="has-text-centered" data-scroll="ask-attorney">
                            <a class="button is-white is-outlined is-large">Ask an Attorney</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="general">
        <div class="container">
            <div class="columns is-mobile is-multiline">
                <div class="column">
                    <div class="box">
			<a href="/create-document">
                            <h2 class="title is-6 has-text-centered">Create Legal Document</h2>
                            <img class="image is-128x128 svg" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page-blank.svg">

                            <p class="has-text-centered content">
				Individual Packages for every house, every territory, and every requirements
                            </p>
                            <p class="has-text-centered arrow">
				<span class="button is-white">
                                    <span class="icon is-large has-text-darker-blue">
					<i class="fa fa-arrow-right"></i>
                                    </span>
				</span>
                            </p>
			</a>
                    </div>
                </div>
                <div class="column">
                    <div class="box">
			<a href="/upload-document">
                            <h2 class="title is-6 has-text-centered">Have Your Document Reviewed</h2>
                            <img class="image is-128x128 svg" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/upload.svg">

                            <p class="has-text-centered content">
				Individual Packages for every house, every territory, and every requirements
                            </p>
                            <p class="has-text-centered arrow">
				<span class="button is-white">
                                    <span class="icon is-large has-text-darker-blue">
					<i class="fa fa-arrow-right"></i>
                                    </span>
				</span>
                            </p>
			</a>
                    </div>
                </div>
                <div class="column">
                    <div class="box">
			<a href="/register-business">
                            <h2 class="title is-6 has-text-centered">Register Your Business</h2>
                            <img class="image is-128x128 svg" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/portfolio.svg">

                            <p class="has-text-centered content">
				Individual Packages for every house, every territory, and every requirements
                            </p>
                            <p class="has-text-centered arrow">
				<span class="button is-white">
                                    <span class="icon is-large has-text-darker-blue">
					<i class="fa fa-arrow-right"></i>
                                    </span>
				</span>
                            </p>
			</a>
                    </div>
                </div>
                <div class="column">
                    <div class="box">
			<a href="/ask-attorney">
                            <h2 class="title is-6 has-text-centered">Ask An Attorney</h2>
                            <img class="image is-128x128 svg" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ask-lawyer.svg">

                            <p class="has-text-centered content">
				Individual Packages for every house, every territory, and every requirements
                            </p>
                            <p class="has-text-centered arrow">
				<span class="button is-white">
                                    <span class="icon is-large has-text-darker-blue">
					<i class="fa fa-arrow-right"></i>
                                    </span>
				</span>
                            </p>
			</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="customers">
        <div class="container">
            <h1 class="subtitle is-2 has-text-centered has-text-darker-gray">LEGAL DOCUMENTS YOU CAN TRUST</h1>
            <br>
            <div class="columns">
                <div class="column">
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Headline Goes Here</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Headline Goes Here</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Headline Goes Here</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="column is-narrow">
                    <p class="image is-128x128 safe has-text-centered">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/safe.svg">
                    </p>
                </div>
                <div class="column">
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Headline Goes Here</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Headline Goes Here</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Headline Goes Here</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="example">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <div class="box box-cont">
                        <img class="image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/placeholder.svg">
                    </div>
                </div>
                <div class="column">
                    <h2 class="subtitle is-3 has-text-darker-gray has-text-centered" style="margin-top: 4rem">Measure everything with a few clicks</h2>
                    <br>
                    <p class="has-text-light-gray">There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...</p>
                    <br>
                    <p>
                        Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.
                    </p>
                    <br>
                    <p class="section-button">
                        <a class="button is-darker-yellow is-large" href="/create-document">Create Legal Documents</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="upload">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <h2 class="subtitle is-3 has-text-white">Get Your Legal Documents Reviewed</h2>

                    <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.</p>
                </div>
                <div class="column is-3">
		    <p class="field section-button">
                        <a class="button is-whiteo is-large" href="/upload-document">
                            <span>Upload Document</span>
                            <span class="icon is-large">
                                <i class="fa fa-cloud-upload"></i>
                            </span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="register">
        <div class="container">
            <h1 class="subtitle is-3 has-text-darker-gray has-text-centered">Register Your Business</h1>
            <br>
            <h3 class="title is-5 has-text-centered has-text-light-gray">The standard Lorem Ipsum passage, used since the 1500s</h3>
            <br><br>
            <div class="columns">
                <div class="column">
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Easy to Use handles</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Create and manage Schedules</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="column">
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Communicate with your teammates</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Measure everything with a few clicks</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="column">
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Share all kinds of files</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/customers.svg">
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong class="has-text-darker-gray">Customize in few steps</strong>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="cont">
                        <div class="field">
                            <div class="select is-large is-warning">
                                <select name="state" id="business-state">
                                    <option value="" selected>Select your state</option>
                                    <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District Of Columbia</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="HI">Hawaii</option>
                                    <option value="ID">Idaho</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IN">Indiana</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NV">Nevada</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="OH">Ohio</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="OR">Oregon</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="TX">Texas</option>
                                    <option value="UT">Utah</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WA">Washington</option>
                                    <option value="WV">West Virginia</option>
                                    <option value="WI">Wisconsin</option>
                                    <option value="WY">Wyoming</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <div class="select is-large is-warning">
                                <select name="business-type" id="business-type">
                                    <option value="" selected>Type of business</option>
                                    <option value="sole-proprietorship">Sole Proprietorship</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="limited-partnership">Limited Partnership</option>
                                    <option value="corporation">Corporation</option>
                                    <option value="llc">Limited Liability Company</option>
                                    <option value="nonprofit-organization">Nonprofit Organization</option>
                                    <option value="cooperative">Cooperative</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <p class="has-text-centered">
                                <a class="button is-darker-yellow has-text-dark is-large" id="business-ask">Get Started</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="adv"></section>


    <section class="section" id="ask-attorney">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <h2 class="title is-2">Ask An Attorney</h2>

                    <p>
                        Lorem ipsum dolor sit amet, ex sit dicta convenire accusamus. Eu legere quidam pro, an sed adhuc option posidonium. Eu mundi laboramus assentior nec, vim nostro euripidis cu. Ad novum altera ius. At sed electram definitiones.
                    </p>
                    <br>
                    <p>
                        Lorem ipsum dolor sit amet, ex sit dicta convenire accusamus. Eu legere quidam pro, an sed adhuc option posidonium. Eu mundi laboramus assentior nec, vim nostro euripidis cu. Ad novum altera ius. At sed electram definitiones.
                    </p>
                    <br>
                    <p>
                        Lorem ipsum dolor sit amet, ex sit dicta convenire accusamus. Eu legere quidam pro, an sed adhuc option posidonium. Eu mundi laboramus assentior nec, vim nostro euripidis cu. Ad novum altera ius. At sed electram definitiones.
                    </p>
                </div>
                <div class="column">
                    <div class="box box-cont">

                        <textarea class="textarea is-medium" placeholder="e.g. What is the likely outcome in my case?" rows="7" id="attorney-text-el"></textarea>
                        <p class="has-text-centered">
                            <a class="button is-darker-yellow  is-medium" id="ask-attorney-btn">Ask an Attorney</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="testimonial">

        <p class="nav-icon left is-hidden-touch">
            <a class="button is-large is-bordercircle">
                <span class="icon is-large">
                    <i class="fa fa-angle-left"></i>
                </span>
            </a>
        </p>

        <div class="container">
            <h3 class="subtitle has-text-centered is-4"><b>Our Clients</b></h3>
            <h2 class="title has-text-centered is-2">What the Doers are saying</h2>

            <p class="has-text-centered">Commune abhorreant et usu, facer mandamus necessitatibus ut vis. At everti quaeque dissentiet nec, tota prompta ea vel. Pri cu wisi complectitur. No est nonumy quodsi, eam at errem partiendo conclusionemque.</p>
            <br><br>

            <div class="columns cont">
                <div class="column is-4 quote-cont animated fadeIn">
                    <div class="box quote-box red">
                        <p class="has-text-centered">
                            <blockquote>"Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place."</blockquote>
                        </p>
                    </div>
                    <p class="has-text-centered avatar">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg">
                    </p>
                    <p class="has-text-centered"><strong>Stephen Hill 1</strong></p>
                    <p class="has-text-centered">SEO Analyst</p>
                </div>
                <div class="column is-4 quote-cont animated fadeIn">
                    <div class="box quote-box blue">
                        <p class="has-text-centered">
                            <blockquote>"Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place."</blockquote>
                        </p>
                    </div>
                    <p class="has-text-centered avatar">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg">
                    </p>
                    <p class="has-text-centered"><strong>Stephen Hill 2</strong></p>
                    <p class="has-text-centered">SEO Analyst</p>
                </div>
                <div class="column is-4 quote-cont animated fadeIn">
                    <div class="box quote-box pink">
                        <p class="has-text-centered">
                            <blockquote>"Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place."</blockquote>
                        </p>
                    </div>
                    <p class="has-text-centered avatar">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg">
                    </p>
                    <p class="has-text-centered"><strong>Stephen Hill 3</strong></p>
                    <p class="has-text-centered">SEO Analyst</p>
                </div>
                <div class="column is-4 quote-cont animated fadeIn">
                    <div class="box quote-box pink">
                        <p class="has-text-centered">
                            <blockquote>"Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place."</blockquote>
                        </p>
                    </div>
                    <p class="has-text-centered avatar">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg">
                    </p>
                    <p class="has-text-centered"><strong>Stephen Hill 4</strong></p>
                    <p class="has-text-centered">SEO Analyst</p>
                </div>
                <div class="column is-4 quote-cont animated fadeIn">
                    <div class="box quote-box pink">
                        <p class="has-text-centered">
                            <blockquote>"Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place."</blockquote>
                        </p>
                    </div>
                    <p class="has-text-centered avatar">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg">
                    </p>
                    <p class="has-text-centered"><strong>Stephen Hill 5</strong></p>
                    <p class="has-text-centered">SEO Analyst</p>
                </div>
                <div class="column is-4 quote-cont animated fadeIn">
                    <div class="box quote-box pink">
                        <p class="has-text-centered">
                            <blockquote>"Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place."</blockquote>
                        </p>
                    </div>
                    <p class="has-text-centered avatar">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg">
                    </p>
                    <p class="has-text-centered"><strong>Stephen Hill 6</strong></p>
                    <p class="has-text-centered">SEO Analyst</p>
                </div>
                <div class="column is-4 quote-cont animated fadeIn">
                    <div class="box quote-box pink">
                        <p class="has-text-centered">
                            <blockquote>"Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place."</blockquote>
                        </p>
                    </div>
                    <p class="has-text-centered avatar">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg">
                    </p>
                    <p class="has-text-centered"><strong>Stephen Hill 7</strong></p>
                    <p class="has-text-centered">SEO Analyst</p>
                </div>

            </div>



        </div>
        <p class="nav-icon right is-hidden-touch">
            <a class="button is-bordercircle is-large">
                <span class="icon is-large">
                    <i class="fa fa-angle-right"></i>
                </span>
            </a>
        </p>
    </section>

    <p id="floating-button" class="animated fadeIn">
        <span class="tooltiptext animated fadeIn">Send us a message us</span>
        <a class="button is-large is-primary has-text-white is-circular">
            <span class="icon is-large">
                <i class="fa fa-comment"></i>
            </span>
        </a>
    </p>


    <?php

    get_template_part('template-parts/footer');
    ?>
