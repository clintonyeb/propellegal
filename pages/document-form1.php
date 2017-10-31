<section class="section">
    <div class="container box box-main">
        <nav class="breadcrumb is-centered bordered has-arrow-separator" aria-label="breadcrumbs">
            <ul>
                <li class="is-active"><a aria-current="page">General</a></li>
                <li><a>Information 1</a></li>
                <li><a>Information 2</a></li>
                <li><a>Download Document</a></li>
            </ul>
        </nav>
        <form name="doc-form-1" class="doc-form">

            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                    <input class="input" type="text" name="user_name" placeholder="Name" autofocus>
                </div>
            </div>

            <div class="field">
                <label class="label">Address</label>
                <div class="control">
                    <textarea class="textarea" name="address" placeholder="Provide input here"></textarea>
                </div>
            </div>
            <div class="field">
                <label class="label">City/Municipal</label>
                <div class="control">
                    <input class="input" type="text" name="city" placeholder="Enter text here">
                </div>
            </div>
            <div class="field">
                <label class="label">Country/Parish</label>
                <div class="control">
                    <input class="input" type="text" name="country" placeholder="Country">
                </div>
            </div>

            <div class="select">
                <!-- <label class="label">State</label> -->
                <select name="state">
                    <option>Select state</option>
                    <option value="state-1">State 1</option>
                    <option value="state-2">State 2</option>
                    <option value="state-3">State 3</option>
                    <option value="state-4">State 4</option>
                </select>
            </div>
            <hr>
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <button class="button is-warning is-medium is-pulled-left is-focused" type="reset" id="back_btn">Go Back</button>
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
</section>
