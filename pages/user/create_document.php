<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];

?>

<span data-href="attorney_requests"></span>
<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

<section class="section" id="create_document">
  <h2 class="title is-4"
      Create> Document
    </h2>

    <div class="columns">
        <div class="column is-4">
            <div class="box has-top-yellow">
                <article class="media">
                    <figure class="media-left">
                        <p class="media-icon">
                            <span class="icon has-text-darker-yellow">
                                <i class="fa fa-hand-o-right"></i>
                            </span>
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                Choose document category
                            </p>
                        </div>
                    </div>
                </article>

                <article class="media">
                    <figure class="media-left">
                        <p class="media-icon">
                            <span class="icon has-text-darker-yellow">
                                <i class="fa fa-hand-o-right"></i>
                            </span>
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                Provide details
                            </p>
                        </div>
                    </div>
                </article>

                <article class="media">
                    <figure class="media-left">
                        <p class="media-icon">
                            <span class="icon has-text-darker-yellow">
                                <i class="fa fa-hand-o-right"></i>
                            </span>
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                Download generated document
                            </p>
                        </div>
                    </div>
                </article>
                <hr />
                <p class="label">What is this?</p>
                This page displays a history of all your activities whiles using our services. You can view them anytime to know what you did and when you did it.
            </div>

            <p class="has-text-centered margined-top-down">
	        <a class="button is-primary is-medium" href="/user/attorney_requests">See all documents</a>
            </p>
        </div>

        <div class="column">
            <div class="box has-top-blue">
                <h3 class="title is-5">Create a Legal Document</h3>
                <form name="create-document">

                    <div class="field">
                        <label class="label">Your location:</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="state">
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
                    </div>

                    <h2 class="title is-6 has-margin-top">Pick a category:</h2>

                    <div class="div columns is-mobile is-gapless is-multiline">
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-1">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/box.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 1</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-2">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/cart.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 2</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-3">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/check.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 3</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-4">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/coin.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 4</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-5">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/diagram.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 5</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-6">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/graph.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 6</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-7">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/justice.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 8</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-8">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/justice-scale.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 8</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-9">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/open.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 9</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-10">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/pie-chart.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 10</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-11">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/rewind-time.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 11</p>
                            </div>
                        </div>
                        <div class="column is-2-desktop is-4-tablet is-6-mobile">
                            <div class="box is-scalable" data-value="category-12">
                                <figure class="image is-64by64">
                                    <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/assets/images/icons/store.svg">
                                </figure>
                                <br>
                                <p class="has-text-centered is-one-line">Category 12</p>
                            </div>
                        </div>
                    </div>


                    <hr>
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <button class="button is-warning is-medium is-pulled-left is-focused" type="reset" id="back_btn" disabled>Go Back</button>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button class="button is-primary is-medium is-pulled-right is-focued" type="submit" id="next_btn">Continue</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>
