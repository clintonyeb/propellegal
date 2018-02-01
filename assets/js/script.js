document.addEventListener('DOMContentLoaded', function () {
    "use strict";

    var $ = jQuery;
    var isMobile = detectMobile();
    // Generic Variables
    var loading = false;
    var messageCont = document.querySelector('.message');
    var messageBody = document.querySelector('.message-body');
    var snackBar = document.getElementById('snackbar');
    var authenticated = $wp_data.authenticated;

    // Registration Functions

    var $regButton = document.getElementById('reg-submit');

    if ($regButton) {
        $regButton.addEventListener('click', submitRegistration);
    }

    function submitRegistration($event) {
        $event.preventDefault();
        if (loading) return;

        var form = document.forms[0];
        var email = form.email;
        var password = form.password;
        var cpassword = form.cpassword;
        var name = form.name;
        var terms = form.terms;
        var captcha = form['g-recaptcha-response'];

        removeErrorField(email);
        removeErrorField(name);
        removeErrorField(password);
        removeErrorField(terms);
        removeErrorField(cpassword);

        removeError('is-danger');

        // validate form

        // validate email field
        if (!rules.required(email.value, true)) {
            showError(email, 'Email', rules.required.reason);
            return false;
        }

        if (!rules.email(email.value, true)) {
            showError(email, 'Email', rules.email.reason);
            return false;
        }

        // validate name field
        if (!rules.required(name.value, true)) {
            showError(name, 'Name', rules.required.reason);
            return false;
        }

        if (!rules.min(name.value, 5)) {
            showError(name, 'Name', rules.min.reason);
            return false;
        }

        // validate password field
        if (!rules.required(password.value, true)) {
            showError(password, 'Password', rules.required.reason);
            return false;
        }

        if (!rules.min(password.value, 6)) {
            showError(password, 'Password', rules.min.reason);
            return false;
        }

        // validate confirm password field
        if (!rules.match(password.value, cpassword.value)) {
            cpassword.value = '';
            showError(cpassword, 'Passwords', rules.match.reason);
            return false;
        }

        if (!terms.checked) {
            showError(terms, 'You must', 'agree to the terms and condition');
            return false;
        }

        // captcha validate
        if (!rules.required(captcha.value, true)) {
            showError(name, 'Capture', rules.required.reason);
            return false;
        }

        showLoadingButton($regButton, true);
        loading = true;

        postData({
            action: "register_form",
            email: email.value,
            full_name: name.value,
            password: password.value,
            client_key: $wp_data.client_auth,
            captcha: captcha.value
        }, function (data) {
            if (data.status) {
                showLoadingButton($regButton, false);
                displayMessage(data.message, 'is-info');
                // clearField(email);
                clearField(name);
                clearField(password);
                clearField(cpassword);
                terms.selected = false;
                loading = false;
                console.log('here');
                createResendConfirmationLink(email.value.trim(), $regButton)
            } else {
                showLoadingButton($regButton, false);
                displayMessage(data.message, 'is-danger');
                loading = false;
            }
        }, function (errorThrown) {
            showLoadingButton($regButton, false);
            displayMessage('Error submitting data', 'is-warning');
            console.log(errorThrown);
            loading = false;
        });
    }

    // End of Registration Functions

    // Log-in Functions

    var $loginButton = document.getElementById('login-submit');

    if ($loginButton) {
        $loginButton.addEventListener('click', submitLogin);
    }

    function submitLogin($event) {
        $event.preventDefault();

        if (loading) return false;

        var form = document.forms[0];
        var email = form.email;
        var password = form.password;

        removeErrorField(email);
        removeErrorField(password);

        // validate form

        // validate email field
        if (!rules.required(email.value, true)) {
            showError(email, 'Email', rules.required.reason);
            return false;
        }

        // validate password field
        if (!rules.required(password.value, true)) {
            showError(password, 'Password', rules.required.reason);
            return false;
        }

        showLoadingButton($loginButton, true);
        loading = true;

        jQuery.ajax({
            type: "POST",
            url: $wp_data.ajaxUrl,
            data: {
                action: 'login',
                email: email.value.trim(),
                password: password.value.trim(),
                client_key: $wp_data.client_auth
            },
            success: function (data) {
                if (data.status) {
                    localStorage.setItem('token', data.token);
                    displayMessage('Login success', 'is-info');

                    if (!sendUnsentUserPayload()) {
                        window.location = data.url;
                    }
                } else {
                    showLoadingButton($loginButton, false);
                    displayMessage(data.message, 'is-danger');
                    if (data.message.indexOf('activated') !== -1) {
                        createResendConfirmationLink(email.value.time(), $loginButton)
                    }
                }

                loading = false;
                console.log(data);
            },
            error: function (errorThrown) {
                showLoadingButton($loginButton, false);
                showError(email, 'Unknown error', 'has occurred');
                console.log(errorThrown);
                loading = false;
            }
        });
        return true;
    }

    // End of Log-in functions

    function createResendConfirmationLink(email, button) {
        const a = document.createElement('a');
        a.addEventListener('click', function () {
            if (loading) return false;

            showLoadingButton($button, true);
            loading = true;

            postData({
                action: 'resend_confirm',
                email: email
            }, function (data) {
                if (data.status) {
                    displayMessage(data.message, 'is-info');
                } else {
                    displayMessage(data.message, 'is-danger');
                }
            }, function (err) {
                displayMessage('Unknown error occured', 'is-danger');

            }, function () {
                showLoadingButton(button, false);
                loading = false;
            })
        })
        a.textContent = 'Resend confirmation email'
        messageBody.appendChild(a);
    }
    // Recover password functions

    var $recoverButton = document.getElementById('recover-submit');

    if ($recoverButton) {
        $recoverButton.addEventListener('click', function ($event) {
            $event.preventDefault();

            if (loading) return;

            var form = document.forms[0];
            var email = form.email;

            removeErrorField(email);

            // validate form

            // validate email field
            if (!rules.required(email.value, true)) {
                showError(email, 'Email field', rules.required.reason);
                return false;
            }

            showLoadingButton($recoverButton, true);
            loading = true;

            jQuery.ajax({
                type: "POST",
                url: $wp_data.ajaxUrl,
                data: {
                    action: "recover_pass",
                    email: email.value.trim(),
                    client_key: $wp_data.client_auth
                },
                success: function (data) {
                    if (data.status) {
                        showLoadingButton($recoverButton, false);
                        displayMessage(data.message, 'is-info');
                        clearField(email);
                    } else {
                        showLoadingButton($recoverButton, false);
                        displayMessage(data.message, 'is-danger');
                        console.log(data);
                    }
                    loading = false;
                },
                error: function (errorThrown) {
                    showLoadingButton($recoverButton, false);
                    showError(email, 'Unknown error', 'has occurred');
                    console.log(errorThrown);
                    loading = false;
                }
            });
        });
    }

    // Change Password Functions
    var $changePassBtn = document.getElementById('change-pass');
    if ($changePassBtn) {
        $changePassBtn.addEventListener('click', function ($event) {
            $event.preventDefault();

            if (loading) return false;

            var form = document.forms[0];
            var email = form.email;
            var password = form.password;
            var cpassword = form.cpassword;

            removeErrorField(email);
            removeErrorField(password);
            removeErrorField(cpassword);

            removeError('is-danger');

            // validate form

            // validate email field
            if (!rules.required(email.value, true)) {
                showError(email, 'Email', rules.required.reason);
                return false;
            }

            if (!rules.email(email.value, true)) {
                showError(email, 'Email', rules.email.reason);
                return false;
            }

            // validate password field
            if (!rules.required(password.value, true)) {
                showError(password, 'Password', rules.required.reason);
                return false;
            }

            if (!rules.min(password.value, 6)) {
                showError(password, 'Password', rules.min.reason);
                return false;
            }

            // validate confirm password field
            if (!rules.match(password.value, cpassword.value)) {
                cpassword.value = '';
                showError(cpassword, 'Passwords', rules.match.reason);
                return false;
            }

            showLoadingButton($changePassBtn, true);
            loading = true;

            jQuery.ajax({
                type: "POST",
                url: $wp_data.ajaxUrl,
                data: {
                    action: "do_pass_recovery",
                    email: email.value.trim(),
                    password: password.value.trim(),
                    client_key: $wp_data.client_auth
                },
                success: function (data) {
                    if (data.status) {
                        showLoadingButton($changePassBtn, false);
                        displayMessage(data.message, 'is-info');
                        clearField(email);
                        clearField(password);
                        clearField(cpassword);
                    } else {
                        showLoadingButton($changePassBtn, false);
                        displayMessage(data.message, 'is-danger');
                    }
                    loading = false;
                },
                error: function (errorThrown) {
                    showLoadingButton($changePassBtn, false);
                    showError(email, 'Unknown Error', 'has occurred');
                    console.log(errorThrown);
                    loading = false;
                }
            });
        });
    }

    // Create Document functions
    var $submitBtn = document.getElementById('next_btn');
    var $backBtn = document.getElementById('back_btn');
    var subsMess = document.getElementById('subs-mess');

    if ($submitBtn) {
        var docForm = document.forms['create-document'];
        var selectForm = document.forms['select-document'];
        var form = document.getElementById('document-form');
        var docDown = document.forms['doc-down'];

        // categories section
        if (docForm) {
            var category = document.querySelectorAll('#create_document .is-scalable');
            var categoryName = '';

            docForm.addEventListener('submit', function ($ev) {
                $ev.preventDefault();

                if (loading) return;

                var selEl = docForm['state'];

                var state = selEl.value;
                var cat = categoryName;

                if (state == '' || categoryName == '') return;
                loading = true;

                var data = {
                    action: "get_files",
                    state: state,
                    category: cat
                };

                showLoadingButton($submitBtn, true);

                if (!makeSureUserAuthenticated(data, '/user/list-documents'))
                    return false;

                postData(data, function (data) {
                    if (data.status) {
                        localStorage.setItem('files', data.data);
                        localStorage.setItem('state', JSON.stringify(state));
                        localStorage.setItem('category', JSON.stringify(cat));
                        location.href = '/user/list-documents';
                    } else {
                        showSnackBar("Error fetching documents");
                    }
                    console.log('da', data);
                    loading = false;
                    showLoadingButton($submitBtn, false);
                }, function (errorThrown) {
                    nnnn
                    showSnackBar("Error fetching documents");
                    showLoadingButton($submitBtn, false);
                    loading = false;
                    console.log('err', errorThrown);
                });
            });

            docForm.addEventListener('reset', function ($ev) {
                $ev.preventDefault();
                window.history.back();
            });

            for (var i = 0; i < category.length; i++) {
                category[i].addEventListener('click', function ($event) {
                    removeSelected();
                    var c = $event.currentTarget;
                    c.classList.add('selected');
                    categoryName = c.getAttribute('data-value');
                });
            }

            function removeSelected() {
                for (var i = 0; i < category.length; i++) {
                    category[i].classList.remove('selected');
                }
            }
        }

        // documents list selection
        if (selectForm) {
            // create elements for data
            var docs = JSON.parse(localStorage.getItem('files')) || [];
            var state = JSON.parse(localStorage.getItem('state'));
            var cat = JSON.parse(localStorage.getItem('category'));
            var cont = document.getElementById('res-cont');
            var res = '';


            var docEls = document.querySelectorAll('.tile.is-child.box');
            var docName = '';

            res = createNodes(docs, state, cat);

            selectForm.insertAdjacentHTML('afterbegin', res);

            // create events
            selectForm.addEventListener('submit', function ($ev) {
                $ev.preventDefault();

                if (loading) return;

                if (docName == '') return;

                loading = true;
                showLoadingButton($submitBtn, true);

                postData({
                    action: "doc_name",
                    client_key: $wp_data.client_auth

                }, function (data) {
                    if (data.status) {
                        showLoadingButton($regButton, false);
                        displayMessage(data.message, 'is-info');
                        clearField(email);
                        clearField(name);
                        clearField(password);
                        clearField(cpassword);
                        terms.selected = false;
                        loading = false;
                    } else {
                        showLoadingButton($regButton, false);
                        displayMessage(data.message, 'is-danger');
                        loading = false;
                    }
                }, function (errorThrown) {
                    showLoadingButton($regButton, false);
                    displayMessage('Error submitting data', 'is-warning');
                    console.log(errorThrown);
                    loading = false;
                });
            });

            selectForm.addEventListener('reset', function ($ev) {
                $ev.preventDefault();
                window.history.back();
            });

            for (var i = 0; i < docEls.length; i++) {

                docEls[i].addEventListener('click', function ($event) {
                    removeSelected();
                    var c = $event.currentTarget;
                    c.classList.add('selected');
                    docName = c.getAttribute('data-value');
                });
            }
        }

        // form section
        if (form) {
            var firstname = document.getElementById('firstname'),
                lastname = document.getElementById('lastname'),
                address = document.getElementById('address'),
                city = document.getElementById('city'),
                country = document.getElementById('country'),
                doc_state = document.getElementById('state');
            var state = JSON.parse(localStorage.getItem('state'));
            var category = JSON.parse(localStorage.getItem('category'));
            var docName = JSON.parse(localStorage.getItem('doc_name'));
            var buttons = document.querySelectorAll('[data-doc]'),
                step = 0;

            for (var i = 0; i < buttons.length; i++) {
                buttons[i].addEventListener('click', function ($ev) {
                    var b = $ev.currentTarget;
                    var s = b.getAttribute('data-doc');

                    s = Number(s);
                    //removeError('is-danger');
                    var errMess = document.getElementById('err');
                    var errMessB = document.querySelector('#err .message-body');
                    errMessB.textContent = '';
                    errMess.classList.remove('is-error');
                    errMess.classList.add('is-hidden');

                    if (!isNaN(s)) {
                        switch (s) {
                            case 0:
                                hideBoxes();
                                step = 0;
                                showBox(step);
                                updateProgress(10);
                                break;
                            case 1:
                                if (loading) return;

                                loading = true;
                                showLoadingButton(b, true);

                                var data = {
                                    action: 'generate_document',
                                    firstname: firstname.value,
                                    lastname: lastname.value,
                                    address: address.value,
                                    city: city.value,
                                    country: country.value,
                                    doc_state: doc_state.value,
                                    state: state,
                                    category: category,
                                    docName: docName
                                };

                                postData(data,
                                    function (data) {
                                        if (data.status) {
                                            localStorage.setItem('output', JSON.stringify(data.data));
                                            hideBoxes();
                                            step = 1;
                                            showBox(step);
                                            updateProgress(90);
                                            setUpDownload();
                                            return true;
                                        } else {
                                            showSnackBar("Error submitting data");
                                            showLoadingButton(b, false);
                                            loading = false;
                                            console.log('data', data);
                                            return false;
                                        }
                                    },
                                    function (err) {
                                        showLoadingButton(b, false);
                                        loading = false;
                                        showSnackBar("Error submitting data");
                                        return false;
                                    });

                                break;
                            case 2:
                                break;
                            case 3:
                                break;
                        }
                    }
                });
            }
        }

        function setUpDownload() {
            var docPrev = document.querySelector('#doc_prev .image');
            var file = JSON.parse(localStorage.getItem('output'));

            var path = '/wp-content/themes/clinton-child/assets/generated_documents/' + file;
            docPrev.setAttribute('src', path + '.jpg');

            if (!subsMess) {
                var downBtn = document.querySelectorAll('.down-doc');

                for (var i = 0; i < downBtn.length; i++) {
                    var btn = downBtn[i];
                    btn.setAttribute('download', "propellegal-document");
                    btn.setAttribute('href', path + '.pdf');
                }
            } else {
                subsMess.classList.remove('is-hidden');
            }
        }
    }

    function createNodes(array, state, cat) {
        var res = [];
        submitDocSelected.state = state;
        submitDocSelected.category = cat;

        res.push('<div class="div columns is-mobile is-gapless is-multiline">');

        for (var i = 0; i < array.length; i++) {
            res.push(createDocumentNode(array[i], state, cat));
        }

        res.push('</div>');
        return res.join('\n');
    }


    function createDocumentNode(data, state, category) {
        var name = data.split('.')[0];

        var thumbs = '/wp-content/themes/clinton-child/assets/thumbs/document.jpeg';

        var res = [
            '<div class="column is-3">',
            '<div class="box doc-box is-scalable" data-value="' + name + '">',
            '<p class="has-text-centered bordered"><b>' + name.toUpperCase() + '</b></p>',
            '<p class="has-text-centered">A little Description</p>',
            '<figure class="image is-3by2">',
            '<img src="' + thumbs + '">',
            '</figure>',
            '<div class="has-margin-top"><button class="button is-primary is-fullwidth customize-btn" data-value="' + name + '">Customize Document</button></div>',
            '<div class="has-margin-top"><button class="button is-primary is-fullwidth ask-btn" data-value="' + name + '">Request Lawyer</button></div>',
            '</div>',
            '</div>'
        ].join('\n');

        return res;
    }

    var customizeBtn = document.querySelectorAll('.customize-btn');
    var askBtn = document.querySelectorAll('.ask-btn');
    var heroHead = document.querySelector('.hero-head');

    for (var i = 0; i < customizeBtn.length; i++) {
        customizeBtn[i].addEventListener('click', function ($ev) {
            submitDocSelected('customize', $ev.currentTarget.getAttribute('data-value'));
        });
    }

    for (var i = 0; i < askBtn.length; i++) {
        askBtn[i].addEventListener('click', function ($ev) {
            submitDocSelected('asklawyer', $ev.currentTarget.getAttribute('data-value'));
        });
    }


    function submitDocSelected(option, docName) {
        var state = submitDocSelected.state;
        var category = submitDocSelected.category;

        // submit doc name, state, category to server
        // server responds with an object container data fields we need
        // create a form and ask those data fields

        // for now let's assume a single template is needed

        localStorage.setItem('option', JSON.stringify(option));
        localStorage.setItem('doc_name', JSON.stringify(docName));
        location.href = '/user/document_forms';
    }

    // End of create document functions

    var floatingIcon = document.querySelector('#floating-button');
    var tooltip = document.querySelector('#floating-button .tooltiptext');

    if (floatingIcon) {
        var contactCard = document.getElementById('contact-card');
        var contactForm = document.getElementById('contact-form')
        var subBtn = document.getElementById('contact-btn')
        var cancelBtn = document.getElementById('contact-cancel')

        window.addEventListener('scroll', showFloatingIcon);

        function showFloatingIcon($ev) {
            var floatShown = isScrolledIntoView('#customers');
            if (floatShown) {
                floatingIcon.style.display = "block";
                tooltip.style.display = "block";
                window.removeEventListener('scroll', showFloatingIcon);
                setTimeout(function () {
                    tooltip.classList.add('fadeOut');
                }, 5000);
            }
        }

        floatingIcon.addEventListener('click', function ($event) {
            floatingIcon.classList.add('is-hidden');
            contactCard.classList.remove('is-hidden');
        })

        cancelBtn.addEventListener('click', function ($event) {
            contactCard.classList.add('is-hidden');
            floatingIcon.classList.remove('is-hidden');
        })

        var name = contactForm.name
        var email = contactForm.email
        var phone = contactForm.phone
        var message = contactForm.message
        var contactLoading = false

        subBtn.addEventListener('click', function ($event) {
            if (contactLoading) return false

            removeErrorField(name);
            removeErrorField(email);
            removeErrorField(phone);
            removeErrorField(message);

            // validate email field
            if (!rules.required(name.value, true)) {
                showInputError(name)
                return false;
            }

            if (!rules.required(email.value, true)) {
                showInputError(email)
                return false;
            }

            if (!rules.email(email.value, true)) {
                showInputError(email)
                return false;
            }

            if (!rules.required(message.value, true)) {
                showInputError(message)
                return false;
            }

            showLoadingButton(subBtn, true)
            contactLoading = true

            postData({
                action: 'contact',
                name: name.value.trim(),
                email: email.value.trim(),
                phone: phone.value ? phone.value.trim() : '',
                message: message.value.trim()
            }, function (data) {
                if (data.status) {
                    showSnackBar(data.message)
                    contactCard.classList.add('is-hidden');
                    floatingIcon.classList.remove('is-hidden');
                } else {
                    showSnackBar(data.message)
                }

            }, function (err) {
                console.log('err', err);
                showSnackBar('Check internet connection')
            }, function () {
                showLoadingButton(subBtn, false)
                contactLoading = false
            })
        })
    }

    var askAttorneyBtn = document.getElementById('ask-attorney-btn');
    var askLoad = false;

    if (askAttorneyBtn) {
        askAttorneyBtn.addEventListener('click', function ($ev) {
            if (askLoad) return false;
            var contentEl = document.querySelector('#attorney-text-el');

            removeErrorField(contentEl);

            if (!rules.required(contentEl.value, true)) {
                contentEl.classList.add('is-danger');
                contentEl.focus();
                return false;
            }

            showLoadingButton(askAttorneyBtn, true);
            askLoad = true;
            console.log('ASKING', $wp_data);

            var data = {
                action: 'ask_attorney',
                content: contentEl.value,
                client_key: $wp_data.client_auth
            };

            if (!makeSureUserAuthenticated(data, '/user/attorney_requests'))
                return false;

            if (!$wp_data.active) {
                saveDataBeforeRedirecting(data, '/user/business_registrations', false)
                return location.href = '/user/pricing'
            }
            postData(data, function (data) {
                if (data.status) {
                    showSnackBar('Request submitted...');
                    clearField(contentEl);
                } else {
                    showSnackBar('An error occurred sending request...');
                }
                askLoad = false;
                showLoadingButton(askAttorneyBtn, false);
            }, function (err) {
                showSnackBar('An error occurred sending request...');
                askLoad = false;
                showLoadingButton(askAttorneyBtn, false);
            });
        });
    }

    var businessAskBtn = document.getElementById('business-ask');
    var businessLoad = false;

    if (businessAskBtn) {
        businessAskBtn.addEventListener('click', function ($event) {
            if (businessLoad) return false;

            var state = document.getElementById('business-state');
            var type = document.getElementById('business-type');

            if (!rules.required(state.value, true)) {
                state.classList.add('is-danger');
                state.focus();
                return false;
            }

            if (!rules.required(type.value, true)) {
                type.classList.add('is-danger');
                type.focus();
                return false;
            }

            var data = {
                action: 'ask_business',
                state: state.value,
                business: type.value,
                client_key: $wp_data.client_auth
            };

            if (!makeSureUserAuthenticated(data, '/user/register_business'))
                return false;

            showLoadingButton(businessAskBtn, true);
            businessLoad = true;

            // postData({

            //     }, function(data){
            //         if(data.status){
            //             showSnackBar('Request submitted...');
            //         }
            //         else {
            //             showSnackBar('An error occurred sending request...');
            //         }
            //         businessLoad = false;
            //         showLoadingButton(businessAskBtn, false);
            //     }, function(err){
            //         showSnackBar('An error occurred sending request...');
            //         businessLoad = false;
            //         showLoadingButton(businessAskBtn, false);
            //     });

            localStorage.setItem('data', JSON.stringify(data));
            location.href = "/user/register_business";

            return true;
        });
    }


    var mainbottom = $('#hero').offset().top + $('#hero').height();


    if (heroHead) {
        window.addEventListener('scroll', showStickyMenu);
    }

    function showStickyMenu() {
        var stop = Math.round($(window).scrollTop());
        var navbar = $('.hero-head .navbar');
        var mainMenu = $('#main-menu');

        if (stop > mainbottom) {
            navbar.addClass('is-fixed-top');
            $('html').addClass('has-navbar-fixed-top');
            mainMenu.addClass('made-fixed');
        } else {
            navbar.removeClass('is-fixed-top');
            $('html').removeClass('has-navbar-fixed-top');
            mainMenu.removeClass('made-fixed');
        }
    }

    var sliderContainer = document.getElementById('slider-container');
    if (sliderContainer) {
        $('.sliders').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            prevArrow: $('.slick-left'),
            nextArrow: $('.slick-right')
        });
    }

    // loading button

    var loader = document.getElementById('loader');

    if (loader && !isMobile) {

        var video = document.querySelector('video');

        setUpVideo();

        video.addEventListener('loadstart', show);
        // video.addEventListener('progress', show);
        // video.addEventListener('playing', show);
        video.addEventListener('playing', hide);
        video.addEventListener('play', hide);
        video.addEventListener('canplaythrough', hide);
        video.addEventListener('canplay', hide);

        function setUpVideo() {
            var src = video.getAttribute('data-src');

            var mp4 = src + '.mp4';
            var ogg = src + '.ogv';
            var webm = src + '.webm';

            var text = "Your browser does not support the video tag.";

            var mp4Src = document.createElement('source');
            var oggSrc = document.createElement('source');
            var webmSrc = document.createElement('source');
            var textSrc = document.createTextNode(text);

            mp4Src.setAttribute('src', mp4);
            mp4Src.setAttribute('type', 'video/mp4');
            oggSrc.setAttribute('src', ogg);
            oggSrc.setAttribute('type', 'video/ogg');
            webmSrc.setAttribute('src', webm);
            webmSrc.setAttribute('type', 'video/webm');


            video.appendChild(mp4Src);
            video.appendChild(oggSrc);
            video.appendChild(webmSrc);
            video.appendChild(textSrc);
        }

        function hide() {
            loader.style.display = "none";
        }

        function show() {
            loader.style.display = "inline-block";
        }
    }

    // File Upload

    var uploadDoc = document.getElementById('upload-doc'),
        fileEl = document.getElementById('file-upload'),
        file_num = document.querySelector('span#doc-count'),
        filesToUpload = [],
        tags = document.getElementById('docs'),
        step = 0,
        hiddens = document.querySelectorAll('.upload-box-cont'),
        buttons = document.querySelectorAll('[data-step]'),
        progressBar = document.querySelector('.progress'),
        name, content,
        fileBox = document.getElementById('file-box'),
        fileCont = document.getElementById('display-files');

    if (uploadDoc) {
        var has = localStorage.getItem('redirect');

        if (has) {
            var prevData = null;
            try {
                prevData = JSON.parse(localStorage.getItem('redirect-data'))
            } catch (error) {
                // do nothing
                prevData = null;
            }

            if (prevData) {
                var detailsForm = document.getElementById('details');
                var n = detailsForm['user_name'];
                var c = detailsForm['content'];
                n.value = prevData.name
                c.value = prevData.content
            }
        }
        localStorage.removeItem('redirect');

        fileEl.addEventListener('change', function ($event) {
            var f = fileEl.files;

            for (var i = 0; i < f.length; i++) {
                filesToUpload.push(f[i]);
            }

            removeError('is-danger');
            updateFileText(filesToUpload.length, file_num);
            updateFiles(filesToUpload);
            updateProgress(15);
        });

        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function ($ev) {
                var b = $ev.currentTarget;
                var s = b.getAttribute('data-step');

                s = Number(s);
                removeError('is-danger');

                if (!isNaN(s)) {
                    switch (s) {
                        case 0:
                            hideBoxes();
                            step = 0;
                            showBox(step);
                            updateProgress(10);
                            break;
                        case 2:
                            if (loading) return;

                            if (filesToUpload.length > 0) {

                                var data = {
                                    name: name,
                                    content: content
                                };

                                var url = 'upload_doc';
                                loading = true;
                                showLoadingButton(b, true);

                                uploadFilesToServer(filesToUpload, data, url, function (err, res) {
                                    if (err) {
                                        displayMessage('There was an error uploading your files', 'is-danger');
                                    } else {
                                        if (res.status) {
                                            hideBoxes();
                                            updateProgress(98);
                                            location.href = "/user/document_reviews";
                                        } else {
                                            displayMessage('There was an error uploading your files', 'is-danger');
                                        }
                                    }
                                    showLoadingButton(b, false);
                                    loading = false;
                                });
                            } else {
                                displayMessage('Please upload at least one file', 'is-danger');
                            }
                            break;
                        case 1:
                            var detailsForm = document.getElementById('details');

                            var n = detailsForm['user_name'];
                            var c = detailsForm['content'];

                            removeErrorField(c);
                            removeErrorField(n);

                            if (!rules.required(n.value, true)) {
                                showError(n, 'Username', rules.required.reason);
                                return false;
                            }
                            if (!rules.required(c.value, true)) {
                                showError(c, 'Please', 'write us a message');
                                return false;
                            }

                            name = n.value;
                            content = c.value;

                            var data = {
                                action: '',
                                name: name,
                                content: content
                            };

                            if (!makeSureUserAuthenticated(data, '/user/review_document', false))
                                return false;

                            if (!$wp_data.active) {
                                saveDataBeforeRedirecting(data, '/user/review_document', false)
                                return location.href = '/user/pricing'
                            }

                            hideBoxes();
                            step = 1;
                            showBox(step);
                            updateProgress(40);

                            break;
                        case 3:
                            location.href = '/';
                            break;
                    }
                }
            });
        }

        showBox(step);

    }

    function updateProgress(v) {
        progressBar.setAttribute('value', v);
    }


    function hideBoxes() {
        for (var i = 0; i < hiddens.length; i++) {
            var h = hiddens[i];
            h.classList.add('is-hidden');
        }
    }

    function showBox(i) {
        console.log(hiddens[i]);
        hiddens[i].classList.remove('is-hidden');
    }

    this.dropEvent = function ($event) {
        $event.preventDefault();
        fileBox.classList.remove('dragged');

        var f = $event.dataTransfer.files;

        for (var i = 0; i < f.length; i++) {
            filesToUpload.push(f[i]);
        }

        removeError('is-danger');
        updateFileText(filesToUpload.length, file_num);
        updateFiles(filesToUpload);
        updateProgress(15);
    };

    this.dragOverEvent = function ($event) {
        $event.preventDefault();
        fileBox.classList.add('dragged');
    };


    this.dragLeave = function ($event) {
        fileBox.classList.remove('dragged');
    };

    function updateFileText(num, el) {
        el.textContent = num;
    }

    function updateFiles(files) {
        clearResults(tags);

        if (files.length) {
            for (var i = 0; i < files.length; i++) {
                var f = files[i];
                createChip(f.name, i);
            }
            fileCont.classList.remove('is-hidden');
        } else {
            fileCont.classList.add('is-hidden');
        }

    }

    function createChip(text, index) {
        var tag = document.createElement('span');
        tag.classList.add('tag', 'is-primary', 'is-medium');

        var textEl = document.createTextNode(text);
        tag.appendChild(textEl);

        var butt = document.createElement('button');
        butt.classList.add('delete', 'is-small');

        tag.appendChild(butt);

        tags.appendChild(tag);

        butt.addEventListener('click', function ($event) {
            removeFile(index);
            updateFileText(filesToUpload.length, file_num);
            updateFiles(filesToUpload);
        });
    }

    function removeFile(i) {
        filesToUpload.splice(i, 1);
    }

    function uploadFilesToServer(files, data, path, cb) {
        var fd = new FormData();

        for (var i = 0; i < files.length; i++) {
            fd.append('file[' + i + ']', files[i]);
        }

        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                var value = data[key];
                fd.append(key, value);
            }
        }

        fd.append('client_key', $wp_data.client_auth);
        fd.append('action', path);

        jQuery.ajax({
            type: "POST",
            url: $wp_data.ajaxUrl,
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function (d) { },
            success: function (data) {
                cb(null, data);
            },
            error: function (errorThrown) {
                cb(errorThrown);
            }
        });
    }

    // End of File Upload


    // Register Business

    var regBuss = document.getElementById('register-business');

    if (regBuss) {
        step = 0;
        hiddens = document.querySelectorAll('.upload-box-cont');
        buttons = document.querySelectorAll('[data-step]');
        progressBar = document.querySelector('.progress');
        var firstname = document.getElementById('firstname'),
            lastname = document.getElementById('lastname'),
            phone = document.getElementById('phone'),
            address = document.getElementById('address'),
            city = document.getElementById('city'),
            state = document.getElementById('state'),
            zip = document.getElementById('zip'),
            busType = document.getElementById('bus-type'),
            comName = document.getElementById('com-name'),
            comDesc = document.getElementById('com-desc'),
            mess = document.getElementById('mess');

        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function ($ev) {
                var b = $ev.currentTarget;
                var s = b.getAttribute('data-step');

                s = Number(s);
                removeError('is-danger');

                if (!isNaN(s)) {
                    switch (s) {
                        case 0:
                            hideBoxes();
                            step = 0;
                            showBox(step);
                            updateProgress(10);
                            break;
                        case 1:
                            removeErrorField(firstname);
                            removeErrorField(lastname);
                            removeErrorField(phone);

                            if (!rules.required(firstname.value, true)) {
                                showError(firstname, 'Name', rules.required.reason);
                                return false;
                            }

                            if (!rules.required(lastname.value, true)) {
                                showError(lastname, 'Name', rules.required.reason);
                                return false;
                            }

                            if (!rules.required(phone.value, true)) {
                                showError(phone, 'Phone number', rules.required.reason);
                                return false;
                            }

                            hideBoxes();
                            step = 1;
                            showBox(step);
                            updateProgress(40);
                            break;
                        case 2:
                            if (loading) return false;

                            removeErrorField(mess);

                            // removeErrorField(city);
                            // removeErrorField(state);
                            // removeErrorField(zip);
                            // removeErrorField(busType);
                            // removeErrorField(comName);
                            // removeErrorField(comDesc);

                            if (!rules.required(mess.value, true)) {
                                showError(mess, 'Please provide us', 'additional message');
                                return false;
                            }

                            loading = true;
                            showLoadingButton(b, true);

                            var data = {
                                action: 'ask_business',
                                client_key: $wp_data.client_auth,
                                firstname: firstname.value,
                                lastname: lastname.value,
                                phone: phone.value,
                                address: address.value,
                                city: city.value,
                                state: state.value,
                                zip: zip.value,
                                busType: busType.value,
                                comName: comName.value,
                                comDesc: comDesc.value,
                                mess: mess.value
                            };

                            if (!makeSureUserAuthenticated(data, '/user/business_registrations'))
                                return false;

                            if (!$wp_data.active) {
                                saveDataBeforeRedirecting(data, '/user/business_registrations', false)
                                return location.href = '/user/pricing'
                            }
                            postData(data, function (data) {
                                if (data.status) {
                                    location.href = "/user/business_registrations";
                                } else {
                                    showSnackBar('Error submitting request');
                                    showLoadingButton(b, false);
                                }
                                loading = false;
                            }, function (err) {
                                showSnackBar("Error submitting data");
                                showLoadingButton(b, false);
                                loading = false;
                            });

                            break;
                    }
                }
                return true;
            });
        }

        function updateProgress(v) {
            progressBar.setAttribute('value', v);
        }


        function hideBoxes() {
            for (var i = 0; i < hiddens.length; i++) {
                var h = hiddens[i];
                h.classList.add('is-hidden');
            }
        }

        function showBox(i) {
            hiddens[i].classList.remove('is-hidden');
        }
    }

    // End of Register Business

    var requestSearch = document.getElementById('request-search');
    if (requestSearch) {
        var btn = document.getElementById('req-search-btn');
        var path = btn.getAttribute('data-url');
        btn.addEventListener('click', function ($event) {
            var query = requestSearch.value.trim();

            if (!rules.required(query, true)) {
                requestSearch.focus();
                return false;
            }

            console.log(path + "/?page=0&query=" + query);
            location.href = path + "/?page=0&query=" + query;

            return true;
        });
    }

    // Request Messages

    var replyTextbox = document.getElementById('request-textbox'),
        uploadFile = document.getElementById('req-file'),
        enterToSend, requestBtn, replyFocus;

    if (replyTextbox) {
        enterToSend = document.getElementById('enter-send');

        enterToSend.addEventListener('change', function ($event) {
            if (enterToSend.checked) makeEnterToSend(replyTextbox);
            else removeEnterToSend(replyTextbox);
        });

        requestBtn = document.getElementById('req-submit');
        requestBtn.addEventListener('click', sendRequestMessage);

        replyFocus = document.getElementById('reply-focus');

        if (replyFocus)
            replyFocus.addEventListener('click', function ($event) {
                replyTextbox.focus();
            });
    }

    if (uploadFile) {
        uploadFile.addEventListener('change', function ($event) {
            var f = uploadFile.files;
            for (var i = 0; i < f.length; i++) {
                filesToUpload.push(f[i]);
            }

            updateFileText(filesToUpload.length, file_num);
            updateFiles(filesToUpload);
        });
    }

    function sendRequestMessage() {
        if (loading) return false;

        var v = replyTextbox.value.trim();
        if (!rules.required(v, true)) {
            replyTextbox.value = "";
            replyTextbox.classList.add('is-danger');
            replyTextbox.focus();
            return false;
        }

        showLoadingButton(requestBtn, true);
        loading = true;

        var data;
        var path = "";

        var is_new = replyTextbox.getAttribute('data-new');
        if (is_new) {
            data = {
                action: 'ask_attorney',
                content: v,
                client_key: $wp_data.client_auth
            };
            path = 'ask_attorney';
        } else {
            var req_id = document.getElementById('req_id').value;

            data = {
                action: 'req_mess',
                content: v,
                req_id: req_id,
                client_key: $wp_data.client_auth
            };
            path = 'req_mess';
        }

        if (!$wp_data.active) {
            saveDataBeforeRedirecting(data, '/user/business_registrations', false)
            return location.href = '/user/pricing'
        }

        if (filesToUpload.length) {
            var fd = new FormData();
            var files = filesToUpload;

            for (var i = 0; i < files.length; i++) {
                fd.append('file[' + i + ']', files[i]);
            }

            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    var value = data[key];
                    fd.append(key, value);
                }
            }

            fd.append('action', path);

            jQuery.ajax({
                type: "POST",
                url: $wp_data.ajaxUrl,
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function (d) { },
                success: function (data) {
                    showSnackBar("Request submitted...");
                    if (is_new)
                        location.href = "/user/attorney_requests";
                    else
                        location.reload();
                },
                error: function (errorThrown) {
                    showLoadingButton(requestBtn, false);
                    loading = false;
                    showSnackBar(data.message);
                }
            });

        } else {
            postData(data, function (data) {
                if (data.status) {
                    showSnackBar("Request submitted...");
                    if (is_new)
                        location.href = "/user/attorney_requests";
                    else
                        location.reload();
                } else {
                    showLoadingButton(requestBtn, false);
                    loading = false;
                    showSnackBar(data.message);
                }
            }, function (err) {
                showLoadingButton(requestBtn, false);
                loading = false;
                showSnackBar(data.message);
            });
        }

        return true;
    }

    function makeEnterToSend(el) {
        el.addEventListener('keypress', sendOnEnter);
    }

    function sendOnEnter($event) {
        if ($event.keyCode === 13) {
            sendRequestMessage();
        }
    }

    function removeEnterToSend(el) {
        el.removeEventListener('keypress', sendOnEnter);
    }

    var reviewTextbox = document.getElementById('review-textbox');

    if (reviewTextbox) {
        enterToSend = document.getElementById('enter-send');
        uploadFile = document.getElementById('req-file');

        enterToSend.addEventListener('change', function ($event) {
            if (enterToSend.checked) rmakeEnterToSend(reviewTextbox);
            else rremoveEnterToSend(reviewTextbox);
        });

        requestBtn = document.getElementById('req-submit');
        requestBtn.addEventListener('click', sendReviewMessage);

        replyFocus = document.getElementById('reply-focus');

        if (replyFocus)
            replyFocus.addEventListener('click', function ($event) {
                reviewTextbox.focus();
            });

        if (uploadFile) {
            uploadFile.addEventListener('change', function ($event) {
                var f = uploadFile.files;
                for (var i = 0; i < f.length; i++) {
                    filesToUpload.push(f[i]);
                }

                updateFileText(filesToUpload.length, file_num);
                updateFiles(filesToUpload);
            });
        }
    }

    function sendReviewMessage() {
        if (loading) return false;

        // save message here
        //redirect to pricing if not active

        var v = reviewTextbox.value.trim();
        if (!rules.required(v, true)) {
            reviewTextbox.value = "";
            reviewTextbox.classList.add('is-danger');
            reviewTextbox.focus();
            return false;
        }

        showLoadingButton(requestBtn, true);
        loading = true;

        var data;

        var req_id = document.getElementById('req_id').value;
        var url = requestBtn.getAttribute('data-url');

        data = {
            action: url,
            content: v,
            req_id: req_id,
            client_key: $wp_data.client_auth
        };

        if (!$wp_data.active) {
            saveDataBeforeRedirecting(data, '/user/business_registrations', false)
            return location.href = '/user/pricing'
        }

        if (filesToUpload.length) {
            var fd = new FormData();
            var files = filesToUpload;

            for (var i = 0; i < files.length; i++) {
                fd.append('file[' + i + ']', files[i]);
            }

            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    var value = data[key];
                    fd.append(key, value);
                }
            }

            fd.append('action', url);

            jQuery.ajax({
                type: "POST",
                url: $wp_data.ajaxUrl,
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function (d) { },
                success: function (data) {
                    showSnackBar("Request submitted...");
                    location.reload();
                },
                error: function (errorThrown) {
                    showLoadingButton(requestBtn, false);
                    loading = false;
                    showSnackBar(data.message);
                }
            });

        } else {
            postData(data, function (data) {
                if (data.status) {
                    showSnackBar("Request submitted...");
                    location.reload();
                } else {
                    showLoadingButton(requestBtn, false);
                    loading = false;
                    showSnackBar(data.message);
                }

            }, function (err) {
                showLoadingButton(requestBtn, false);
                loading = false;
                showSnackBar(data.message);
                console.log(err);
            });
        }
        return true;
    }

    function rmakeEnterToSend(el) {
        el.addEventListener('keypress', rsendOnEnter);
    }

    function rsendOnEnter($event) {
        if ($event.keyCode === 13) {
            sendReviewMessage();
        }
    }

    function rremoveEnterToSend(el) {
        el.removeEventListener('keypress', rsendOnEnter);
    }

    var avatarUpload = document.getElementById('avatar-upload');

    if (avatarUpload) {
        avatarUpload.addEventListener('change', function ($event) {
            var file = avatarUpload.files[0];

            if (loading) return false;

            if (!rules.fileSize(file, true)) {
                displayMessage('File size is too big', 'is-danger');
                return false;
            }

            if (!rules.isFileImage(file)) {
                displayMessage('Please upload an image', 'is-danger');
                return false;
            }

            loading = true;
            showSnackBar('Uploading image');
            // upload file to server

            uploadFilesToServer(avatarUpload.files, {},
                'upload_avatar',
                function (err, data) {
                    if (err) {
                        displayMessage(err, 'is-danger');
                    } else {
                        if (data.status) {
                            showSnackBar("Upload success");
                            location.reload();
                        } else
                            displayMessage(data.message, 'is-danger');
                    }
                    loading = false;
                });
            return true;
        });
    }

    var edited = false;
    var commitBtn = document.getElementById('commit-btn');
    var edits = [];
    var action = document.getElementById('action');
    // Drop downs

    $('.ui.dropdown')
        .dropdown({
            onChange: function (value, text, $selectedItem) {
                var id = $selectedItem.context.parentElement.getAttribute('data-id');
                addToEdit(id, value);
                ableToCommit();
            },
            saveRemoteData: false
        });

    if (commitBtn) {
        commitBtn.addEventListener('click', function ($event) {
            console.log('posting', edits);
            if (edits.length && !loading) {
                showLoadingButton(commitBtn, true);
                loading = true;
                // post to server
                postData({
                    action: 'admin_requests',
                    edits: edits,
                    act: action.value
                }, function (data) {
                    if (data.status)
                        location.reload();
                    else {
                        loading = false;
                        showLoadingButton(commitBtn, false);
                    }
                    console.log(data);
                }, function (err) {
                    loading = false;
                    showLoadingButton(commitBtn, false);
                    console.log(err);
                });
            }
        });
    }

    function addToEdit(id, value) {
        var ex = false;
        for (var i = 0; i < edits.length; i++) {
            var o = edits[i];
            if (o.id === id) {
                ex = true;
                o.value = value;
                break;
            }
        }

        if (!ex) {
            edits.push({
                id: id,
                value: value
            });
        }
    }

    function ableToCommit() {
        if (!edited && edits.length) {
            commitBtn.removeAttribute('disabled');
            edited = true;
        }
    }
    // ADMIN Requests functions

    // register lawyer

    var $regLawBtn = document.getElementById('lawyer-reg');

    if ($regLawBtn) {
        var email = document.getElementById('email');
        var full_name = document.getElementById('fullname');
        var gender = document.getElementById('gender');
        var state = document.getElementById('state');
        var activated = document.getElementById('activated');

        $regLawBtn.addEventListener('click', function ($event) {
            removeErrorField(email);
            removeErrorField(full_name);
            removeErrorField(gender);
            removeErrorField(state);
            removeErrorField(activated);

            removeError('is-danger');

            // validate form

            // validate email field
            if (!rules.required(email.value, true)) {
                showError(email, 'Email', rules.required.reason);
                return false;
            }

            if (!rules.email(email.value, true)) {
                showError(email, 'Email', rules.email.reason);
                return false;
            }

            // validate name field
            if (!rules.required(full_name.value, true)) {
                showError(full_name, 'Name', rules.required.reason);
                return false;
            }

            if (!rules.required(gender.value, true)) {
                showError(gender, 'Gender', rules.required.reason);
                return false;
            }

            if (!rules.required(state.value, true)) {
                showError(State, 'Location', rules.required.reason);
                return false;
            }

            if (!rules.required(activated.value, true)) {
                showError(activated, 'Activated', rules.required.reason);
                return false;
            }

            showLoadingButton($regLawBtn, true);
            loading = true;

            postData({
                action: "register_lawyer",
                email: email.value,
                full_name: full_name.value,
                gender: gender.value,
                state: state.value,
                activated: activated.value === 'Yes' ? true : false
            }, function (data) {
                if (data.status) {
                    showLoadingButton($regLawBtn, false);
                    displayMessage(data.message, 'is-info');
                    clearField(email);
                    clearField(full_name);
                    clearField(gender);
                    clearField(state);
                    loading = false;
                } else {
                    showLoadingButton($regLawBtn, false);
                    displayMessage(data.message, 'is-danger');
                    loading = false;
                }
                console.log(data);
            }, function (errorThrown) {
                showLoadingButton($regLawBtn, false);
                displayMessage('Error submitting data', 'is-warning');
                console.log(errorThrown);
                loading = false;
            });

            return true;
        });

    }

    // request details actions

    var adminAction = document.getElementById('admin-action');

    if (adminAction) {
        var actionBtns = document.querySelectorAll('a[data-action]');
        var request_type = document.getElementById('action-type');
        var req_id = document.getElementById('req_id');
        var admin_Mail_Submit = document.getElementById('admin_mail_btn')

        for (var i = 0; i < actionBtns.length; i++) {
            actionBtns[i].addEventListener('click', function ($event) {
                var att = $event.currentTarget.getAttribute('data-action');
                var role = request_type.getAttribute('data-role');
                performAction(att, role);
            });
        }

        admin_Mail_Submit.addEventListener('click', function ($event) {
            var email = document.getElementById('email').value;
            var userId = document.getElementById('user_id').value;
            var mess = document.getElementById('admin-mail').value;
            var type = document.getElementById('mail-type').value;

            if (!mess) return false
            showLoadingButton(admin_Mail_Submit, true)

            sendAdminMail(email, userId, mess, function (data) {
                showLoadingButton(admin_Mail_Submit, false)
                location.href = '/admin/' + type + '/'
            })
        })

        function performAction(attr, role) {

            switch (attr) {
                case 'send_message':
                    var article = document.querySelector('article#reply-box');
                    if (article) {
                        article.classList.toggle('is-hidden');
                    }
                    break;
                case 'mark_completed':
                    postAction('mark_completed', role);
                    break;
                case 'remove_request':
                    postAction('remove_request', role);
                    break;
                case 'delete_account':
                    var email = document.getElementById('email').value;
                    var userId = document.getElementById('user_id').value;
                    var type = document.getElementById('mail-type').value;

                    deleteUserAcc(email, userId, function (res) {
                        location.href = '/admin/' + type + '/'
                    });
                    break;
            }
        }
        
        function deleteUserAcc(email, userId, action) {
            postData({
                action: 'admin_del_user',
                email: email,
                user_id: userId
            }, function (data) {
                action();
            }, function (err) {
                console.log(err);
            })
        }

        function sendAdminMail(email, userId, message, action) {
            postData({
                action: 'admin_email',
                email: email,
                user_id: userId,
                message: message
            }, function (data) {
                action();
            }, function (err) {
                console.log(err);
            })
        }

        function postAction(action_type, role) {
            if (loading) return;

            postData({
                action: 'admin_actions',
                request_type: request_type.value,
                action_type: action_type,
                req_id: req_id.value
            }, function (data) {
                if (data.status) {
                    goback(request_type.value, role);
                } else {
                    showSnackBar('Already completed...');
                    goback(request_type.value, role);
                }
                loading = false;
                console.log(data);

            }, function (err) {
                console.log(err);
                loading = false;
            });
        }

        function goback(type, role) {
            switch (type) {
                case "ASK_ATTORNEY":
                    console.log("/" + role + "/attorney_requests");
                    location.href = "/" + role + "/attorney_requests";
                    break;
                case "REVIEW_DOCUMENT":
                    location.href = "/" + role + "/document_reviews";
                    break;
                case "REGISTER_BUSINESS":
                    location.href = "/" + role + "/business_registrations";
                    break;
            }
        }
    }

    // Menu dropdown

    var drops = document.querySelectorAll('.navbar-item.has-dropdown');

    for (var i = 0; i < drops.length; i++) {
        drops[i].addEventListener('click', function ($event) {
            $event.currentTarget.classList.toggle('is-active');
        });
    }
    // Utility functions

    function clearField(field) {
        field.value = "";
    }

    function showLoadingButton(button, state) {
        if (state) {
            button.classList.add('is-loading');
            button.setAttribute('disabled', true);
        } else {
            button.classList.remove('is-loading');
            button.removeAttribute('disabled');
        }
    }

    function showError(field, name, reason) {
        displayMessage(name + ' ' + reason, 'is-danger');
        field.classList.add('is-danger');
        field.focus();
    }

    function displayMessage(reason, type) {
        messageBody.textContent = reason;
        messageCont.classList.remove('is-hidden');
        messageCont.classList.add(type);
    }

    function removeError(type) {
        messageBody.textContent = '';
        messageCont.classList.remove(type);
        messageCont.classList.add('is-hidden');
    }

    function showInputError(el) {
        el.classList.add('is-danger');
        el.focus();
    }

    function removeErrorField(field) {
        field.classList.remove('is-danger');
    }

    var nPath = location.pathname.split('/');
    nPath = cleanArray(nPath);
    nPath = nPath.splice(nPath.length - 1, 1)[0];

    var activeNav = document.querySelector('aside .menu-list a[data-href="' + nPath + '"]');

    if (activeNav) {
        addActiveNav(activeNav);
    } else {
        var el = document.querySelector('span[data-href]');
        if (el) {
            var sel = el.getAttribute('data-href');
            activeNav = document.querySelector('aside .menu-list a[data-href="' + sel + '"]');
            addActiveNav(activeNav);
        }
    }

    function addActiveNav(nav) {
        nav.classList.add('is-active');
    }

    var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    if ($navbarBurgers.length > 0) {
        for (var i = 0; i < $navbarBurgers.length; i++) {
            $navbarBurgers[i].addEventListener('click', function ($event) {
                var $el = $event.currentTarget;
                var target = $el.dataset.target;
                var $target = document.getElementById(target);
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        }
    }

    var sideNav = document.getElementById('side-nav');

    if (sideNav) {
        var openBtn = document.getElementById('open-nav');
        var closeBtn = document.getElementById('close-btn');

        if (openBtn) {
            openBtn.addEventListener('click', openSideNav);
            closeBtn.addEventListener('click', closeSideNav);
        }

        function openSideNav($event) {
            sideNav.style.width = "100%";
        }

        function closeSideNav($event) {
            sideNav.style.width = "0";
        }

    }

    // Feedback

    var feedbackBtn = document.querySelector('a[data-feedback]');
    if (feedbackBtn) {
        var modal = document.getElementById('feedback-modal');
        var ratingFig = document.querySelector('#feedback-modal #rat-fig');
        var ratingMess = document.querySelector('#feedback-modal #rat-mess');
        var ratSubmit = document.querySelector('#feedback-modal #rat-submit');
        var ratComment = document.querySelector('#feedback-modal #rat-comment');
        var req_id = document.getElementById('req_id');
        var req_type = document.getElementById('action-type');

        var rating = 0;

        feedbackBtn.addEventListener('click', function ($event) {
            modal.classList.add('is-active');
        });

        var close = document.querySelector('#feedback-modal .delete');
        close.addEventListener('click', function ($event) {
            modal.classList.remove('is-active');
        });

        var cancel = document.querySelector('#feedback-modal .button.is-danger');
        cancel.addEventListener('click', function ($event) {
            modal.classList.remove('is-active');
        });

        var stars = document.querySelectorAll('#feedback-modal .feed-icon');
        for (var i = 0; i < stars.length; i++) {
            stars[i].addEventListener('click', function ($event) {
                var t = $event.currentTarget;
                var index = t.getAttribute('data-index');
                updateRating(index);
            });
        }

        ratSubmit.addEventListener('click', function ($event) {
            if (loading) return false;
            var r = rating;
            var comment = ratComment.value ? ratComment.value : false;

            if (!r) {
                return false;
            }

            loading = true;
            showLoadingButton(ratSubmit, true);
            var data = {
                'action': 'req_feedback',
                'req_id': req_id.value,
                'action_type': req_type.value,
                'comment': comment,
                'rating': r
            };

            postData(data, function (data) {
                loading = false;
                showLoadingButton(ratSubmit, false);
                if (data.status) {
                    modal.classList.remove('is-active');
                    showSnackBar('Feedback sent...');
                } else {
                    showSnackBar('Error sending feedback...');
                }
                console.log(data);
            }, function (err) {
                showSnackBar('Error sending feedback...');
                console.log(err);
            });

            return true;
        });

        function updateRating(index) {
            rating = index;
            ratingFig.innerHTML = rating;
            ratingMess.innerHTML = getRatingMess(Number(index));
            for (var i = 0; i < stars.length; i++) {
                var s = stars[i];
                if (i < index) {
                    s.classList.remove('fa-star-o');
                    s.classList.add('fa-star');
                } else {
                    s.classList.remove('fa-star');
                    s.classList.add('fa-star-o');
                }
            }
        }

        function getRatingMess(index) {
            switch (index) {
                case 0:
                    return '';
                case 1:
                    return 'Very Bad!';
                case 2:
                    return 'Bad';
                case 3:
                    return 'Good';
                case 4:
                    return 'Very Good';
                case 5:
                    return 'Excellent!';
            }
        }



    }

    // End of Feedback


    function clearResults(el) {
        while (el.firstChild) {
            el.removeChild(el.firstChild);
        }
    }

    function postData(data, success, failure, final) {
        jQuery.ajax({
            type: "POST",
            url: $wp_data.ajaxUrl,
            data: data,
            success: success,
            error: failure,
            complete: final
        });
    }

    function navigateLink(url) {
        if ($wp_data.authenticated) {
            url = '/user' + url;
        }
        window.location.href = url;
    }

    function makeSVGFileInline() {
        jQuery('img.svg').each(function () {
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function (data) {
                // Get the SVG tag, ignore the rest
                var $svg = jQuery(data).find('svg');

                // Add replaced image's ID to the new SVG
                if (typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if (typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass + ' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr('xmlns:a');

                // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
                if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                    $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                }

                // Replace image with new SVG
                $img.replaceWith($svg);

            }, 'xml');

        });
    }

    makeSVGFileInline();

    function isScrolledIntoView(elem) {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }


    function detectMobile() {
        var check = false;
        (function (a) {
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    }

    var goTop = document.querySelector('#go-top .icon')
    goTop.addEventListener('click', function ($event) {
        scrollToTop()
    })

    var scrollBtns = document.querySelectorAll('[data-scroll]');

    for (var i = 0; i < scrollBtns.length; i++) {
        scrollBtns[i].addEventListener('click', function ($ev) {
            var tar = $ev.currentTarget.getAttribute('data-scroll');
            scrollToElement('#' + tar);
        });
    }

    function scrollToTop() {
        $('html, body').animate({
            scrollTop: 0
        }, "slow");
    }

    function scrollToElement(elem) {
        var divPosition = $(elem).offset();
        if (heroHead) {
            var h = heroHead.offsetHeight;
            $('html, body').animate({
                scrollTop: divPosition.top - h
            }, "slow");
        } else {
            $('html, body').animate({
                scrollTop: divPosition.top
            }, "slow");
        }

    }

    function showSnackBar(text) {
        if (snackBar) {
            snackBar.textContent = text;
            snackBar.classList.add('show');

            setTimeout(function () {
                snackBar.classList.remove('show');
            }, 3000);
        }
    }

    function cleanArray(arr) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i] === "") {
                arr.splice(i, 1);
            }
        }

        return arr;
    }

    function makeSureUserAuthenticated(data, url, submit) {
        submit = submit === false ? false : true
        if (authenticated) {
            return true;
        } else {
            localStorage.setItem('submit-redirect', submit)
            localStorage.setItem('redirect', JSON.stringify(url));
            localStorage.setItem('redirect-data', JSON.stringify(data));
            location.href = "/login";
        }
        return false;
    }

    function makeSureUserAuthenticatedWithFiles(files, data, dataUrl, url) {
        if (authenticated) {
            return true;
        } else {
            localStorage.setItem('redirect', JSON.stringify(url));
            localStorage.setItem('redirect-data', JSON.stringify(data));
            location.href = "/login";
        }
        return false;
    }

    function sendUnsentUserPayload() {
        var has = localStorage.getItem('redirect');
        if (has) {
            try {
                has = JSON.parse(has);
            } catch (error) {
                return false;
            }

            if (has) {
                var shdSend = localStorage.getItem('submit-redirect')
                if (!JSON.parse(shdSend)) return location.href = has + '/?redirected=true';
                var data = JSON.parse(localStorage.getItem('redirect-data'));
                showSnackBar('Sending request');

                postData(data,
                    function (res) {
                        if (res.status) {
                            showSnackBar("Request sent successfully");
                            if (has == "/user/list-documents") {
                                localStorage.setItem('files', res.data);
                                localStorage.setItem('state', JSON.stringify(data.state));
                                localStorage.setItem('category', JSON.stringify(data.category));
                                location.href = '/user/list-documents';
                            }
                        } else {
                            showSnackBar("There was an error sending request");
                        }
                    },
                    function (err) {
                        showSnackBar("There was an error sending request");
                    });
            }

            clearStorageData();
            location.href = has + '/?redirected=true';
            return true;
        }
        return false;
    }

    function clearStorageData() {
        localStorage.removeItem('redirect');
        localStorage.removeItem('redirect-data');
    }

    function shorten(text, maxLength) {
        var ret = text;
        if (ret.length > maxLength) {
            ret = ret.substr(0, maxLength - 3) + "...";
        }
        return ret;
    }

    function saveDataBeforeRedirecting(data, url, shdSubmit) {
        localStorage.setItem('redirect', url)
        localStorage.setItem('redirect-data', data)
        localStorage.setItem('submit-redirect', shdSubmit)
    }

    var clickable = document.querySelectorAll('.clickable');

    for (var i = 0; i < clickable.length; i++) {
        clickable[i].addEventListener("click", function ($event) {
            var tar = $event.currentTarget;
            var link = tar.getAttribute('data-href');
            location.href = link;
        });
    }

    var reload = document.querySelectorAll('.reload');

    for (var i = 0; i < reload.length; i++) {
        reload[i].addEventListener('click', function () {
            location.reload();
        });
    }

    // End of Utility Functions

    // Payments

    var paySubmit = document.getElementById('sq-creditcard');

    if (paySubmit) {
        var price = JSON.parse(localStorage.getItem('price'))
        var priceEl = document.querySelector('#subscribe #cost')
        var amtEl = document.querySelector('#subscribe #amount')
        amtEl.setAttribute('value', price)
        priceEl.innerHTML = price

        paySubmit.addEventListener('click', requestCardNonce);

        // Square Payment Information

        // Set the application ID
        var applicationId = "sandbox-sq0idp-tiHbqn2aBXTqcMv_YE3geQ";

        // Set the location ID
        var locationId = "CBASENQDniEAGk0Lox0zdTT1rY0gAQ";

        var payMess = document.querySelector('#pay-mess');
        var payMessCont = document.querySelector('#pay-mess .message-body ul');

        /*
         * function: requestCardNonce
         *
         * requestCardNonce is triggered when the "Pay with credit card" button is
         * clicked
         *
         * Modifying this function is not required, but can be customized if you
         * wish to take additional action when the form button is clicked.
         */

        function requestCardNonce(event) {

            // Don't submit the form until SqPaymentForm returns with a nonce
            event.preventDefault();

            payMess.classList.add('is-hidden');
            clearResults(payMessCont);
            // Request a nonce from the SqPaymentForm object
            showLoadingButton(paySubmit, true);
            paymentForm.requestCardNonce();
        }

        // Create and initialize a payment form object

        var paymentForm = new SqPaymentForm({

            // Initialize the payment form elements
            applicationId: applicationId,
            locationId: locationId,
            inputClass: 'input',

            // Customize the CSS for SqPaymentForm iframe elements
            inputStyles: [{
                fontSize: '.9em'
            }],

            // Initialize Masterpass placeholder ID
            masterpass: {
                elementId: 'sq-masterpass'
            },

            // Initialize the credit card placeholders
            cardNumber: {
                elementId: 'sq-card-number',
                placeholder: '•••• •••• •••• ••••'
            },
            cvv: {
                elementId: 'sq-cvv',
                placeholder: 'CVV'
            },
            expirationDate: {
                elementId: 'sq-expiration-date',
                placeholder: 'MM/YY'
            },
            postalCode: {
                elementId: 'sq-postal-code'
            },

            callbacks: {
                methodsSupported: function (methods) {

                    var masterpassBtn = document.getElementById('sq-masterpass');
                    var masterpassLabel = document.getElementById('sq-masterpass-label');

                    if (methods.masterpass === true) {
                        masterpassBtn.style.display = 'inline-block';
                        masterpassLabel.style.display = 'none';
                    }
                },

                createPaymentRequest: function () {
                    console.log('price pay', price);

                    return {
                        requestShippingAddress: true,
                        currencyCode: "USD",
                        countryCode: "US",
                        total: {
                            label: "{{ MERCHANT NAME }}",
                            amount: "100.00",
                            pending: false
                        },
                        lineItems: [{
                            label: "Subtotal",
                            amount: "80.00",
                            pending: false
                        },
                        {
                            label: "Shipping",
                            amount: "0.00",
                            pending: true
                        },
                        {
                            label: "Tax",
                            amount: "10.00",
                            pending: false
                        }
                        ]
                    };
                },

                cardNonceResponseReceived: function (errors, nonce, cardData) {
                    showLoadingButton(paySubmit, false);
                    if (errors) {
                        payMess.classList.remove('is-hidden');
                        // Log errors from nonce generation to the Javascript console

                        errors.forEach(function (error) {
                            var li = document.createElement('li');
                            li.textContent = error.message;
                            payMessCont.appendChild(li);
                            console.log('  ' + error.message);
                        });

                        return;
                    }

                    // Assign the nonce value to the hidden form field
                    document.getElementById('card-nonce').value = nonce;

                    // POST the nonce form to the payment processing page
                    document.getElementById('nonce-form').submit();

                },

                /*
                 * callback function: unsupportedBrowserDetected
                 * Triggered when: the page loads and an unsupported browser is detected
                 */
                unsupportedBrowserDetected: function () {
                    payMess.classList.remove('is-hidden');
                    var li = document.createElement('li');
                    li.textContent = 'Your browser is not supported, please update your browser';
                    payMessCont.appendChild(li);
                },

                /*
                 * callback function: inputEventReceived
                 * Triggered when: visitors interact with SqPaymentForm iframe elements.
                 */
                inputEventReceived: function (inputEvent) {
                    switch (inputEvent.eventType) {
                        case 'focusClassAdded':
                            /* HANDLE AS DESIRED */
                            break;
                        case 'focusClassRemoved':
                            /* HANDLE AS DESIRED */
                            break;
                        case 'errorClassAdded':
                            /* HANDLE AS DESIRED */
                            break;
                        case 'errorClassRemoved':
                            /* HANDLE AS DESIRED */
                            break;
                        case 'cardBrandChanged':
                            /* HANDLE AS DESIRED */
                            break;
                        case 'postalCodeChanged':
                            /* HANDLE AS DESIRED */
                            break;
                    }
                },

                /*
                 * callback function: paymentFormLoaded
                 * Triggered when: SqPaymentForm is fully loaded
                 */
                paymentFormLoaded: function () {
                    /* HANDLE AS DESIRED */
                }
            }
        });


        paymentForm.build();
    }

    // pricing Page

    var pricing = document.getElementById('pricing')
    if (pricing) {
        var buttons = document.querySelectorAll('#pricing [data-value]')
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function ($event) {
                if (loading) return;
                var butt = $event.currentTarget;
                loading = true;
                showLoadingButton(butt, true)
                let price = butt.getAttribute('data-value')
                localStorage.setItem('price', JSON.stringify(price))
                return location.href = '/user/subscribe'
            })
        }
    }

    // Searching

    var searchIcon = document.getElementById('search-icon');
    if (searchIcon) {
        var searchBox = document.getElementById('search-box');
        var searchRemove = document.getElementById('search-remove');
        var searchButton = document.getElementById('search-button');
        var searchInput = document.getElementById('search-input');

        searchBox.style.display = 'none';
        searchIcon.addEventListener('click', function ($event) {
            searchIcon.style.display = 'none';
            searchBox.style.display = 'flex'
        });

        searchRemove.addEventListener('click', function ($event) {
            searchBox.style.display = 'none'
            searchIcon.style.display = 'flex';
        })

        searchButton.addEventListener('click', function ($event) {
            var data = searchInput.value
            if (!data) return;
            location.href = '/search?q=' + data;
        })

    }

    //  Change User Password 

    var changePassBtn = document.getElementById('changeUserPassBtn');

    if (changePassBtn) {
        var form = document.getElementById('change-pass-form')
        var currentPass = form.current;
        var password = form.password
        var confirm = form.confirm

        changePassBtn.addEventListener('click', function ($event) {
            if (loading) return false;

            removeErrorField(currentPass);
            removeErrorField(password);
            removeErrorField(confirm);

            removeError('is-danger');

            // validate password field
            if (!rules.required(currentPass.value, true)) {
                showError(currentPass, 'Current Password', rules.required.reason);
                return false;
            }

            // validate password field
            if (!rules.required(password.value, true)) {
                showError(password, 'Password', rules.required.reason);
                return false;
            }

            if (!rules.min(password.value, 6)) {
                showError(password, 'Password', rules.min.reason);
                return false;
            }

            // validate confirm password field
            if (!rules.match(password.value, confirm.value)) {
                confirm.value = '';
                showError(confirm, 'Passwords', rules.match.reason);
                return false;
            }

            if (rules.match(password.value, currentPass.value)) {
                password.value = '';
                showError(password, 'New and current password', 'cannot be same');
                return false;
            }

            showLoadingButton(changePassBtn, true);
            loading = true;

            postData({
                action: 'change_pass',
                current_pass: currentPass.value.trim(),
                password: password.value.trim()
            }, function (data) {
                if (data.status) {
                    displayMessage(data.message, 'is-info');
                    location.href = '/logout';
                } else {
                    displayMessage(data.message, 'is-danger');
                }
                console.log('data', data);
            }, function (err) {
                displayMessage('Please check your internet connection!', 'is-danger');
                console.log(err, 'change pass');
            }, function () {
                showLoadingButton(changePassBtn, false)
                loading = false
            })

        })

    }
}.bind(this));