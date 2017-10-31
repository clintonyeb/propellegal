<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];

?>

<span data-href="attorney_requests"></span>

<section class="section">
    <h2 class="title is-3">
        Ask an Attorney
    </h2>

    <div class="columns">
        <div class="column">
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
                                Send our attorneys a message
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
                                Our Attorneys review and provide you feedback
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
                                We notify you when a response is ready
                            </p>
                        </div>
                    </div>
                </article>
                <hr />
                <p class="label">What is this?</p>
                This page displays a history of all your activities whiles using our services. You can view them anytime to know what you did and when you did it.
            </div>

            <p class="has-text-centered margined-top-down">
	        <a class="button is-primary is-medium" href="/user/attorney_requests">See all requests</a>
            </p>
        </div>
        <div class="column is-8">
            <div class="box has-top-blue">
                <div class="level">
                    <div class="level-left">
                        <h3 class="title is-5">Create a new Request</h3>
                    </div>
                </div>

                <article class="media">
                    <figure class="media-left">
                        <p class="image is-64x64">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg" height="55" width="55">
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="field">
                            <p class="control">
                                <textarea id="request-textbox" data-new=true class="textarea" placeholder="Send a reply to the lawyer..." cols="10"></textarea>
                            </p>
                        </div>
                        <nav class="level">
                            <div class="level-left">
                                <div class="level-item">
                                    <a class="button is-primary is-medium" id="req-submit">Send Message</a>
                                </div>
                            </div>
                            <div class="level-right">
                                <div class="level-item">
                                    <label class="checkbox">
                                        <input type="checkbox" id="enter-send"> Press enter to send message
                                    </label>
                                </div>
                            </div>
                        </nav>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
