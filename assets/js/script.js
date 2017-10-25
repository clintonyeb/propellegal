document.addEventListener('DOMContentLoaded', function () {
    "use strict";

    var $ = jQuery;
    var isMobile = detectMobile();
    // Generic Variables
    var loading = false;
    var messageCont = document.querySelector('.message');
    var messageBody = document.querySelector('.message-body');
    var snackBar = document.getElementById('snackbar');
    
    // Registration Functions

    var $regButton = document.getElementById('reg-submit');

    if ($regButton){
        $regButton.addEventListener('click',submitRegistration);
    }

    function submitRegistration($event){
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
        if(!rules.required(email.value, true)){
            showError(email, 'Email', rules.required.reason);
            return false;
        }

        if(!rules.email(email.value, true)){
            showError(email, 'Email', rules.email.reason);
            return false;
        }

        // validate name field
        if(!rules.required(name.value, true)){
            showError(name, 'Name', rules.required.reason);
            return false;
        }

        if(!rules.min(name.value, 5)){
            showError(name, 'Name', rules.min.reason);
            return false;
        }

        // validate password field
        if(!rules.required(password.value, true)){
            showError(password, 'Password', rules.required.reason);
            return false;
        }

        if(!rules.min(password.value, 6)){
            showError(password, 'Password', rules.min.reason);
            return false;
        }

        // validate confirm password field
        if(!rules.match(password.value, cpassword.value)){
            cpassword.value = '';
            showError(cpassword, 'Passwords', rules.match.reason);
            return false;
        }

        if (!terms.checked){
            showError(terms, 'You must', 'agree to the terms and condition');
            return false;
        }

        // captcha validate
        if(!rules.required(captcha.value, true)){
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
        }, function(data){
            if (data.status){
                showLoadingButton($regButton, false);
                displayMessage(data.message, 'is-info');
                clearField(email);
                clearField(name);
                clearField(password);
                clearField(cpassword);
                terms.selected = false;
                loading = false;
            } else{
                showLoadingButton($regButton, false);
                displayMessage(data.message, 'is-danger');
                loading = false;
            }
        }, function(errorThrown){
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

    function submitLogin($event){
        $event.preventDefault();

        if (loading) return false;

        var form = document.forms[0];
        var email = form.email;
        var password = form.password;

        removeErrorField(email);
        removeErrorField(password);

        // validate form

        // validate email field
        if(!rules.required(email.value, true)){
            showError(email, 'Email', rules.required.reason);
            return false;
        }

        // validate password field
        if(!rules.required(password.value, true)){
            showError(password, 'Password', rules.required.reason);
            return false;
        }

        showLoadingButton($loginButton, true);
        loading = true;

        jQuery.ajax({
            type:"POST",
            url: $wp_data.ajaxUrl,
            data: {
                action: "login_form",
                email: email.value.trim(),
                password: password.value.trim(),
                client_key: $wp_data.client_auth
            },
            success:function(data){
                if (data.status){
                    localStorage.setItem('token', data.token);
                    window.location = '/user';
                } else {
                    showLoadingButton($loginButton, false);
                    displayMessage(data.message, 'is-danger');
                }
                loading = false;
            },
            error: function(errorThrown){
                showLoadingButton($loginButton, false);
                showError(email, 'Unknown error', 'has occurred');
                console.log(errorThrown);
                loading = false;
            }
        });
    }

    // End of Log-in functions

    // Recover password functions

    var $recoverButton = document.getElementById('recover-submit');

    if($recoverButton){
        $recoverButton.addEventListener('click', function($event){
            $event.preventDefault();

            if (loading) return;

            var form = document.forms[0];
            var email = form.email;

            removeErrorField(email);

            // validate form

            // validate email field
            if(!rules.required(email.value, true)){
                showError(email, 'Email field', rules.required.reason);
                return false;
            }

            showLoadingButton($recoverButton, true);
            loading = true;

            jQuery.ajax({
                type:"POST",
                url: $wp_data.ajaxUrl,
                data: {
                    action: "recover_pass",
                    email: email.value.trim(),
                    client_key: $wp_data.client_auth
                },
                success:function(data){
                    if (data.status){
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
                error: function(errorThrown){
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
    if($changePassBtn){
        $changePassBtn.addEventListener('click', function($event){
            $event.preventDefault();

            if(loading) return false;

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
            if(!rules.required(email.value, true)){
                showError(email, 'Email', rules.required.reason);
                return false;
            }

            if(!rules.email(email.value, true)){
                showError(email, 'Email', rules.email.reason);
                return false;
            }

            // validate password field
            if(!rules.required(password.value, true)){
                showError(password, 'Password', rules.required.reason);
                return false;
            }

            if(!rules.min(password.value, 6)){
                showError(password, 'Password', rules.min.reason);
                return false;
            }

            // validate confirm password field
            if(!rules.match(password.value, cpassword.value)){
                cpassword.value = '';
                showError(cpassword, 'Passwords', rules.match.reason);
                return false;
            }

            showLoadingButton($changePassBtn, true);
            loading = true;

            jQuery.ajax({
                type:"POST",
                url: $wp_data.ajaxUrl,
                data: {
                    action: "do_pass_recovery",
                    email: email.value.trim(),
                    password: password.value.trim(),
                    client_key: $wp_data.client_auth
                },
                success:function(data){
                    if (data.status){
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
                error: function(errorThrown){
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

    if ($submitBtn){
        var docForm = document.forms['create-document'];
        var selectForm = document.forms['select-document'];
        var form1 = document.forms['doc-form-1'];
        var docDown = document.forms['doc-down'];

        // categories section
        if (docForm){
            var category = document.querySelectorAll('.tile.is-child.box');
            var categoryName = '';

            docForm.addEventListener('submit', function($ev){
                $ev.preventDefault();

                if (loading) return;

                var selEl = docForm['state'];

                var state = selEl.value;
                var cat = categoryName;

                if (state == '' || categoryName == '') return;
                loading = true;
                showLoadingButton($submitBtn, true);

                jQuery.ajax({
                    type:"POST",
                    url: $wp_data.ajaxUrl,
                    data: {
                        action: "get_files",
                        state: state,
                        category: cat
                    },
                    success:function(data){
                        if (data.status){
                            localStorage.setItem('files', data.data);
                            localStorage.setItem('state', JSON.stringify(state));
                            localStorage.setItem('category', JSON.stringify(cat));
                            navigateLink('/list-documents');
                        } else {
                            console.log('Error fetching data', data);
                        }

                        loading = false;
                        showLoadingButton($submitBtn, false);
                    },
                    error: function(errorThrown){
                        console.log('Error fetching data', errorThrown);
                        showLoadingButton($submitBtn, false);
                    }
                });
            });

            docForm.addEventListener('reset', function($ev){
                $ev.preventDefault();
                window.history.back();
            });

            for(var i = 0; i < category.length; i++) {
                category[i].addEventListener('click', function($event){
                    removeSelected();
                    var c = $event.currentTarget;
                    c.classList.add('selected');
                    categoryName = c.getAttribute('data-value');
                });
            }

            function removeSelected(){
                for(var i = 0; i < category.length; i++) {
                    category[i].classList.remove('selected');
                }
            }
        }

        // documents list selection
        if (selectForm){
            // create elements for data
            var docs = JSON.parse(localStorage.getItem('files')) || [];
            var state = JSON.parse(localStorage.getItem('state'));
            var cat = JSON.parse(localStorage.getItem('category'));
            var cont = document.getElementById('res-cont');
            var res = '';


            var docEls = document.querySelectorAll('.tile.is-child.box');
            var docName = '';

            while(docs.length){
                res += createNodes(docs.splice(0, 3), state, cat);
            }

            selectForm.insertAdjacentHTML('afterbegin', res);

            // create events
            selectForm.addEventListener('submit', function($ev){
                $ev.preventDefault();

                if(loading) return;

                if (docName == '') return;

                loading = true;
                showLoadingButton($submitBtn, true);

                postData({
                    action: "doc_name",
                    client_key: $wp_data.client_auth

                }, function(data){
                    if (data.status){
                        showLoadingButton($regButton, false);
                        displayMessage(data.message, 'is-info');
                        clearField(email);
                        clearField(name);
                        clearField(password);
                        clearField(cpassword);
                        terms.selected = false;
                        loading = false;
                    } else{
                        showLoadingButton($regButton, false);
                        displayMessage(data.message, 'is-danger');
                        loading = false;
                    }
                }, function(errorThrown){
                    showLoadingButton($regButton, false);
                    displayMessage('Error submitting data', 'is-warning');
                    console.log(errorThrown);
                    loading = false;
                });
            });

            selectForm.addEventListener('reset', function($ev){
                $ev.preventDefault();
                window.history.back();
            });

            for(var i = 0; i < docEls.length; i++) {
                
                docEls[i].addEventListener('click', function($event){
                    removeSelected();
                    var c = $event.currentTarget;
                    c.classList.add('selected');
                    docName = c.getAttribute('data-value');
                });
            }
        }

        // form 1 section
        if(form1){
            form1.addEventListener('submit', function($ev){
                $ev.preventDefault();

                var user_name = form1['user_name'];
                var address = form1['address'];
                var city = form1['city'];
                var country = form1['country'];
                var state = JSON.parse(localStorage.getItem('state'));
                var category = JSON.parse(localStorage.getItem('category'));
                var docName = JSON.parse(localStorage.getItem('doc_name'));

                // submit data
                postData({
                    action: 'generate_document',
                    user_name: user_name.value,
                    address: address.value,
                    city: city.value,
                    country: country.value,
                    state: state,
                    category: category,
                    docName: docName
                }, function(data){
                    if(data.status){
                        localStorage.setItem('output', JSON.stringify(data.data));
                        // done here so navigate to download page
                        navigateLink('/preview-document');
                    }
                    else {
                        // show error
                    }
                }, function(err){
                    // show error
                });
            });
        }

        // Document Preview Section
        if (docDown){
            var docPrev = document.querySelector('#doc_prev .image');
            var file = JSON.parse(localStorage.getItem('output'));

            var path =  $wp_data.home + '/assets/generated_documents/' + file;
            docPrev.setAttribute('src', path + '.jpg');

            var downBtn = document.querySelectorAll('.down-doc');

            for(var i = 0; i < downBtn.length; i++) {
                var btn = downBtn[i];
                btn.setAttribute('download', "propellegal-document");
                btn.setAttribute('href', path + '.pdf');
            }
        }
    }

    function createNodes(array, state, cat){
        var res = [];
        submitDocSelected.state = state;
        submitDocSelected.category =  cat;

        res.push('<div class="tile is-ancestor res-cont">');

        for(var i = 0; i < array.length; i++){
            res.push(createDocumentNode(array[i], state, cat));
        }
        
        res.push('</div>');
        return res.join('\n');
    }


    function createDocumentNode(data, state, category){
        var name = data.split('.')[0];

        var thumbs = $wp_data.home + '/assets/thumbs/' + state + '/' + category + '/' + name + '.png';

        var res = [
            '<div class="tile is-4">',
            '<div class="tile is-child box doc-box is-scalable" data-value="' + name + '">',
            '<p class="has-text-centered bordered"><b>' + name.toUpperCase() + '</b></p>',
            '<p class="has-text-centered">A little Description</p>',
            '<figure class="image is-3by2">',
            '<img src="' + thumbs +  '">',
            '</figure>',
            '<div class="padded-top-down"><button class="button is-primary is-fullwidth customize-btn" data-value="' +  name + '">Customize Document</button></div>',
            '<div><button class="button is-primary is-fullwidth ask-btn" data-value="' + name  + '">Request Lawyer</button></div>',
            '</div>',
            '</div>'
        ].join('\n');

        return res;
    }

    var customizeBtn = document.querySelectorAll('.customize-btn');
    var askBtn = document.querySelectorAll('.ask-btn');
    var heroHead = document.querySelector('.hero-head');
    
    for(var i = 0; i < customizeBtn.length; i++) {
        customizeBtn[i].addEventListener('click', function($ev){
            submitDocSelected('customize', $ev.currentTarget.getAttribute('data-value'));
        });
    }

    for(var i = 0; i < askBtn.length; i++) {
        askBtn[i].addEventListener('click', function($ev){
            submitDocSelected('asklawyer', $ev.currentTarget.getAttribute('data-value'));
        });
    }


    function submitDocSelected(option, docName){
        var state = submitDocSelected.state;
        var category = submitDocSelected.category;

        // submit doc name, state, category to server
        // server responds with an object container data fields we need
        // create a form and ask those data fields

        // for now let's assume a single template is needed

        localStorage.setItem('option', JSON.stringify(option));
        localStorage.setItem('doc_name', JSON.stringify(docName));
        navigateLink('/document-form1');
    }

    // End of create document functions


    // scrolling carousel

    function slider(){
        var slideIndex = 1;
        var shown = 3;

        var slides = document.querySelectorAll("#testimonial .quote-cont");

        if (slides.length > 0){
            var range = slides.length - 1;
            hideSlides(slides);

            showSlides(slides, slideIndex);

            var leftSlide = document.querySelector('#testimonial .nav-icon.left');
            var rightSlide = document.querySelector('#testimonial .nav-icon.right');

            leftSlide.addEventListener('click', function($ev){
                slideIndex = decCounter(slideIndex, range);
                hideSlides(slides);
                showSlides(slides, slideIndex);
            });

            rightSlide.addEventListener('click', function($ev){
                slideIndex = incCounter(slideIndex, range);
                hideSlides(slides);
                showSlides(slides, slideIndex);
            });
        }

        function showSlides(slides, index) {
            for (var i = 0; i < 3; i++){
                _show(slides, index);
                index = incCounter(index, range);
            }
        }

        function _show(slides, i){
            slides[i].style.display = "block";
        }

        function hideSlides(slides){
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
        }

        function incCounter(n, range){
            if (n + 1 > range){
                n = 0;
            } else {
                n++;
            }

            return n;
        }

        function decCounter(n, range){
            if (n - 1 < 0){
                n = range;
            } else {
                n--;
            }
            return n;
        }

        var floatingIcon = document.querySelector('#floating-button');
        var tooltip = document.querySelector('#floating-button .tooltiptext');

        if (floatingIcon){
            window.addEventListener('scroll', showFloatingIcon);

            function showFloatingIcon($ev){
                var floatShown = isScrolledIntoView('#customers');
                if (floatShown){
                    floatingIcon.style.display = "block";
                    tooltip.style.display = "block";
                    window.removeEventListener('scroll', showFloatingIcon);
                    setTimeout(function(){
                        tooltip.classList.add('fadeOut');
                    }, 5000);
                }
            }
        }
        
        var askAttorneyBtn = document.getElementById('ask-attorney-btn');
        var askLoad = false;
        
        if(askAttorneyBtn){
            askAttorneyBtn.addEventListener('click', function ($ev) {
                if (askLoad) return false;
                var contentEl = document.querySelector('.box.box-cont textarea');

                removeErrorField(contentEl);
                
                if(!rules.required(contentEl.value, true)){
                    contentEl.classList.add('is-danger');
                    contentEl.focus();
                    return false;
                }

                showLoadingButton(askAttorneyBtn, true);
                askLoad = true;
                
                postData({
                    action: 'ask_attorney',
                    content: contentEl.value,
                    client_key: $wp_data.client_auth
                }, function(data){
                    if(data.status){
                        showSnackBar('Request submitted...');
                        clearField(contentEl);
                    }
                    else {
                        showSnackBar('An error occurred sending request...');
                    }
                    askLoad = false;
                    showLoadingButton(askAttorneyBtn, false);
                }, function(err){
                    showSnackBar('An error occurred sending request...');
                    askLoad = false;
                    showLoadingButton(askAttorneyBtn, false);
                });
            });
        }

        var businessAskBtn = document.getElementById('business-ask');
        var businessLoad = false;

        if(businessAskBtn){
            businessAskBtn.addEventListener('click', function ($event) {
                if(businessLoad) return false;

                var state = document.getElementById('business-state');
                var type = document.getElementById('business-type');

                if(!rules.required(state.value, true)){
                    state.classList.add('is-danger');
                    state.focus();
                    return false;
                }

                if(!rules.required(type.value, true)){
                    type.classList.add('is-danger');
                    type.focus();
                    return false;
                }

                showLoadingButton(businessAskBtn, true);
                businessLoad = true;
                
                postData({
                    action: 'ask_business',
                    state: state.value,
                    business: type.value,
                    client_key: $wp_data.client_auth
                }, function(data){
                    if(data.status){
                        showSnackBar('Request submitted...');
                    }
                    else {
                        showSnackBar('An error occurred sending request...');
                    }
                    businessLoad = false;
                    showLoadingButton(businessAskBtn, false);
                }, function(err){
                    showSnackBar('An error occurred sending request...');
                    businessLoad = false;
                    showLoadingButton(businessAskBtn, false);
                });
            });
        }
        
        
        var mainbottom = $('#hero').offset().top + $('#hero').height();

        if(heroHead){
            window.addEventListener('scroll', showStickyMenu);
        }

        function showStickyMenu(){
            var stop = Math.round($(window).scrollTop());
            if (stop > mainbottom) {
                $('.hero-head').addClass('mode-static');
            } else {
                $('.hero-head').removeClass('mode-static');
            }


        }
    }

    slider();

    // loading button

    var loader = document.getElementById('loader');

    if (loader && !isMobile){

        var video = document.querySelector('video');

	setUpVideo();

        video.addEventListener('loadstart', show);
        video.addEventListener('progress', show);
        video.addEventListener('playing', show);
        video.addEventListener('playing', hide);
        video.addEventListener('play', hide);
        video.addEventListener('canplaythrough', hide);
        video.addEventListener('canplay', hide);

	function setUpVideo(){
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

        function hide(){
            loader.style.display = "none";
        }

        function show(){
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
        fileBox = document.getElementById('file-box');
    
    if(uploadDoc){    
        fileEl.addEventListener('change', function($event){
            var f = fileEl.files;
            
            for(var i = 0; i < f.length; i++){
                filesToUpload.push(f[i]);
            }
            
            removeError('is-danger');
            updateFileText(filesToUpload.length, file_num);
            updateFiles(filesToUpload);
            updateProgress(15);
        });

        for(var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function ($ev) {
                var b = $ev.currentTarget;
                var s = b.getAttribute('data-step');
                
                s = Number(s);
                removeError('is-danger');
                
                if (!isNaN(s)){
                    switch (s) {
                    case 0:
                        hideBoxes();
                        step = 0;
                        showBox(step);
                        updateProgress(10);
                        break;
                    case 1:
                        if(filesToUpload.length > 0){
                            hideBoxes();
                            step = 1;
                            showBox(step);
                            updateProgress(40);
                        }else {
                            displayMessage('Please upload at least one file', 'is-danger');
                        }
                        break;
                    case 2:
                        if (loading) return;
                        
                        var detailsForm = document.getElementById('details');

                        var n = detailsForm['user_name'];
                        var c = detailsForm['content'];

                        removeErrorField(c);
                        removeErrorField(n);
                        
                        if(!rules.required(n.value, true)){
                            showError(n, 'Username', rules.required.reason);
                            return false;
                        }
                        if(!rules.required(c.value, true)){
                            showError(c, 'Please', 'write us a message');
                            return false;
                        }
                        
                        name = n.value;
                        content = c.value;
                        loading = true;
                        showLoadingButton(b, true);
                        // post Data to server

                        uploadFilesToServer(filesToUpload, {
                            name: name,
                            content: content
                        }, function (err, res) {
                            if (err){
                                displayMessage('There was an error uploading your files', 'is-danger');
                            } else {
                                if(res.status){
                                    hideBoxes();
                                    step = 2;
                                    showBox(step);
                                    updateProgress(98);                                
                                } else{
                                    displayMessage('There was an error uploading your files', 'is-danger');
                                }
                            }
                            showLoadingButton(b, false);
                            loading = false;
                        });
                        
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


    function hideBoxes(){
        for(var i = 0; i < hiddens.length; i++) {
            var h = hiddens[i];
            h.classList.add('is-hidden') ;
        }
    }

    function showBox(i){
        hiddens[i].classList.remove('is-hidden');
    }
    
    this.dropEvent = function($event){
        $event.preventDefault();
        fileBox.classList.remove('dragged');
        
        var f = $event.dataTransfer.files;

        for(var i = 0; i < f.length; i++){
            filesToUpload.push(f[i]);
        }
        
        removeError('is-danger');
        updateFileText(filesToUpload.length, file_num);
        updateFiles(filesToUpload);
        updateProgress(15);
    };

    this.dragOverEvent = function($event){
        $event.preventDefault();
        fileBox.classList.add('dragged');
    };

    
    this.dragLeave = function($event){
        fileBox.classList.remove('dragged');
    };

    function updateFileText(num, el){
        el.textContent = num;
    }

    function updateFiles(files) {
        clearResults(tags);

        for(var i = 0; i < files.length; i++) {
            var f = files[i];
            createChip(f.name, i);
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

    function uploadFilesToServer(files, data, cb){
        var fd = new FormData();

        for(var i = 0; i < files.length; i++) {
            fd.append('file[' + i + ']', files[i]);
        }

        for(var key in data) {
            if(data.hasOwnProperty(key)) {
                var value = data[key];
                fd.append(key, value);
            }
        }

        fd.append('client_key', $wp_data.client_auth);
        fd.append('action', 'upload_doc');

        jQuery.ajax({
            type:"POST",
            url: $wp_data.ajaxUrl,
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function(d){
            },
            success:function(data){
                cb(null, data);
            },
            error: function(errorThrown){
                cb(errorThrown);
            }
        });
    }

    // End of File Upload
    
    // Utility functions

    function clearField(field){
        field.value = "";
    }

    function showLoadingButton(button, state){
        if(state) {
            button.classList.add('is-loading');
        } else {
            button.classList.remove('is-loading');
        }
    }

    function showError(field, name, reason){
        displayMessage(name + ' ' + reason, 'is-danger');
        field.classList.add('is-danger');
        field.focus();
    }

    function displayMessage(reason, type){
        messageBody.textContent = reason;
        messageCont.classList.remove('is-hidden');
        messageCont.classList.add(type);
    }

    function removeError(type){
        messageBody.textContent = '';
        messageCont.classList.remove(type);
        messageCont.classList.add('is-hidden');
    }

    function removeErrorField(field){
        field.classList.remove('is-danger');
    }

    var activeNav = document.querySelector('aside .menu-list a[href^="/' + location.pathname.split('/').splice(-2, 1)[0] + '"]');
    if (activeNav)
        activeNav.classList.add('is-active');

    var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    if ($navbarBurgers.length > 0) {
        for(var i = 0; i < $navbarBurgers.length; i++) {
            var $el = $navbarBurgers[i];
            $navbarBurgers[i].addEventListener('click', function ($event) {
                var $el = $event.currentTarget;
                
                var target = $el.dataset.target;
                var $target = document.getElementById(target);
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        }
    }

    function clearResults(el) {
        while (el.firstChild) {
            el.removeChild(el.firstChild);
        }
    }

    function postData(data, success, failure){
        jQuery.ajax({
            type:"POST",
            url: $wp_data.ajaxUrl,
            data: data,
            success: success,
            error: failure
        });
    }

    function navigateLink(url){
        if ($wp_data.authenticated){
            url = '/user' + url;
        }
        window.location.href = url;
    }

    function makeSVGFileInline(){
        jQuery('img.svg').each(function(){
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function(data) {
                // Get the SVG tag, ignore the rest
                var $svg = jQuery(data).find('svg');

                // Add replaced image's ID to the new SVG
                if(typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if(typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass+' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr('xmlns:a');

                // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
                if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                    $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                }

                // Replace image with new SVG
                $img.replaceWith($svg);

            }, 'xml');

        });

    }

    makeSVGFileInline();

    function isScrolledIntoView(elem)
    {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }


    function detectMobile(){
	var check = false;
	(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
    }

    var scrollBtns = document.querySelectorAll('[data-scroll]');

    for(var i = 0; i < scrollBtns.length; i++) {
	scrollBtns[i].addEventListener('click', function($ev){
            var tar = $ev.currentTarget.getAttribute('data-scroll');
	    scrollToElement('#' + tar);
	});
    }

    function scrollToElement(elem){
	var divPosition = $(elem).offset();
        if (heroHead){
            var h = heroHead.offsetHeight;
            $('html, body').animate({scrollTop: divPosition.top - h}, "slow");
        } else {
            $('html, body').animate({scrollTop: divPosition.top}, "slow");
        }
        
    }

    function showSnackBar(text){
        if (snackBar){
            snackBar.textContent = text;
            snackBar.classList.add('show');
            
            setTimeout(function(){
                snackBar.classList.remove('show');
            }, 3000);
        }
    }

    // End of Utility Functions



    
    // var docDown = document.getElementById('down-doc');

    // if (docDown){

    //     var s = document.getElementById('doc-type');
    //     var format = document.getElementById('format');
    //     var data = JSON.parse(localStorage.getItem('data'));
    //     var path =  $wp_data.home + "/assets/generated_documents/";
    //     var st = path + data.output + '.';

    //     docDown.setAttribute('href', st + 'pdf');
    //     format.addEventListener('change', function($event){
    //         var v = $event.target.value;
    //         s.textContent = v.toUpperCase();
    //         docDown.setAttribute('href', st + v);
    //     });

    //     docDown.addEventListener('click', function($event){

    //     });



    // jQuery.ajax({
    //     type:"POST",
    //     url: $wp_data.ajaxUrl,
    //     data: {
    //         action: 'get_pdf',
    //         doc: data.output
    //     },
    //     responseType: 'blob',
    //     beforeSend: function(d){
    //     },
    //     success:function(data){
    //         if (data.status){
    //             //
    //         } else {
    //             console.log('Error fetching data', data);
    //         }
    //     },
    //     error: function(errorThrown){
    //         console.log('Error fetching data err', errorThrown);
    //     }
    // });
    //}
}.bind(this));
