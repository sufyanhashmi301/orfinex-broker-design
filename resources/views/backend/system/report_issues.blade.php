@extends('backend.setting.system.index')
@section('title')
    {{ __('Report Issues') }}
@endsection
@section('system-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form name="fileabugform"
                action="https://projects.x9hq.com/portal/x9hq/addbugfromiframe.do"
                method="post"
                enctype="multipart/form-data"
                onSubmit="return _wbf.validateForm()">
                <input type="hidden" name="projId" value="projects-7457b43063d25e59b5b377f16be0bcbb59ca42e07b807983303ccc6b00774af3" />
                <input type="hidden" name="fId" value="projects-67a1fedecf1610acf694b1e740277f6b4fda17e79565781e835d17f90b776418" />
                <div class="webform-data">
                    <div class="webform-data-wrapper space-y-5">
                        <div class="web-form-field full-field">
                            <label class="mandatory-field form-label">Issue Name</label>
                            <input type="text" id="title" class="form-control" name="subject" value="" />
                        </div>
                        <div class="web-form-field full-field">
                            <label class="form-label">Issue Description</label>
                            <textarea id="description" class="form-control basicTinymce" name="desc" rows="4" style="height: 80px"></textarea>
                        </div>
                        <div class="web-form-field full-field">
                            <label class="form-label">Attach File</label>
                            <div class="attachment-title form-control">
                                <span class="attachment-msg">Drop files or add attachments here...</span>
                                <input type="file" id="uploadfile" name="uploadfile" onchange="addAttachment();" class="attachment-input" />
                            </div>
                            <div style="margin-top: 20px; display: none" class="attachment" id="filename" onclick="removeAttachement();">
                                -
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="web-form-field half-field">
                                <label class="mandatory-field form-label">Email Address</label>
                                <input type="text" class="form-control" name="email" />
                            </div>
                            <div class="web-form-field half-field">
                                <label class="form-label">Severity</label>
                                <div class="webform-select-style">
                                    <select name="Severity" class="form-control w-full" data-mand="false">
                                        <option value="projects-8b616883638e771dde3a7ea04553c0bad04c473f87f169be1a8e6ba11e1fa3d6">
                                            None
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba6c0ec1098f42efb57f7c6c33874cd896">
                                            Show stopper
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0babe78ec40e11c1ac70126b8cb392fb884">
                                            Critical
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba6c4b10f33f9e919bb0e54fbdf3ff499e">
                                            Major
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba9bd836fb78ff5894f65ab2dde3b0f258">
                                            Minor
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="web-form-field half-field">
                                <label class="form-label">Classification</label>
                                <div class="webform-select-style">
                                    <select name="Classification" class="form-control w-full" data-mand="false">
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba0f0f3a526ec3cade540a2d6f4448629e">
                                            None
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba157870ff0e8ca95e08d32a8766c59072">
                                            Security
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0baf62229c404dc1ec8fbf7eda0aad46845">
                                            Crash/Hang
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0baaa9f280efab382b84a3ba12add82488f">
                                            Data loss
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba645e1250251074edfc1c68b3408f1805">
                                            Performance
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba21e4afe8fba257a20e0ebb33a9069e1a">
                                            UI/Usabililty
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba63b00e018d6e0077df3ec7a5c4dd58ea">
                                            Other bug
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba507b97a58e615c3e4e67f3581a938a58">
                                            Feature(New)
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba25b2d5d356e96cee977a54669d6818b7">
                                            Enhancement
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="web-form-field half-field">
                                <label class="form-label">Reproducible</label>
                                <div class="webform-select-style">
                                    <select name="Is it Reproducible" class="form-control" data-mand="false">
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba27f167697983ef8da98d7f19cd2c629e">
                                            None
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba1bb005bc7656093df811efce0bd92cd2">
                                            Always
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba0c8d7c8487b4fe7f8ce04c1fde4b1e36">
                                            Sometimes
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba51bfbb814e2892c6a90424f7ea22b16d">
                                            Rarely
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba3e515fd07f07c2dc1ef8223985419677">
                                            Unable
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0ba324ec8b07ae9e698bb5cda9fbbf673f0">
                                            Never tried
                                        </option>
                                        <option value="projects-8b616883638e771dde3a7ea04553c0bab02a777cac5f0b7b3044334f55096e00">
                                            Not applicable
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="web-form-field full-field mt-1">
                            <div class="text-sm text-danger">
                                Note: Email ID is used to know the reporter of this bug. Submitting
                                your email ID will not add you as a user or give you access to
                                portal data.
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center mt-10">
                                <button type="submit" value="Save" title="Save" class="btn btn-dark inline-flex itemms-center justify-center primary-button mr-1">
                                    {{ __('Save') }}
                                </button>
                                <input type="reset" value="Cancel" title="Cancel" class="btn btn-outline-dark inline-flex items-center justify-center secondary-button"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .attachment-title {
            position: relative;
            display: flex;
            align-items: center;
        }
        .attachment-title .attachment-msg {
            width: 250px;
            white-space: nowrap;
            overflow: hidden;
            color: #777;
        }
        .attachment-title .attachment-input {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
        }
        .attachment-title:hover .attachment-msg {
            color: rgb(var(--primaryColor));
        }
        .attachment {
            padding: 0 15px;
            background: #fff;
            border: #ecedee solid 1px;
            border-radius: 3px;
            position: relative;
            margin-top: 20px;
            width: 100%;
            height: 35px;
            line-height: 35px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
        .attachment:before {
            content: "X";
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            right: 10px;
            top: 9px;
            background: rgb(var(--primaryColor));
            width: 16px;
            height: 16px;
            color: #fff;
            cursor: pointer;
            border-radius: 2px;
        }
        .add-attachment {
            float: left;
            background: #fff;
            border-radius: 2px;
            cursor: pointer;
            color: #30a66b;
            padding: 8px 17px;
            margin-right: 11px;
            border: 1px solid;
        }
    </style>
@endsection
@section('system-script')
    <script type="text/javascript">
            let _wbf = (function () {
                var code;
                const captchaNeeded = false;
                window.onload = function () {
                    generateCaptcha();
                    multiSelectWithoutCtrl();
                    const numberFields = document.querySelectorAll('[type^="number"]');
                    numberFields.forEach(function (element) {
                        element.addEventListener("keydown", (e) => {
                            const key = e.which || e.keyCode || 0;
                            if (
                                e.target.hasAttribute("maxLength") &&
                                e.target.value?.length >= e.target.getAttribute("maxLength") &&
                                key >= 48 &&
                                key <= 57
                            ) {
                                event.preventDefault();
                            }
                        });
                    });
                };
                const multiSelectWithoutCtrl = () => {
                    let options = document.querySelectorAll('[name^="UDF_NMULTI"] option');
                    options.forEach(function (element) {
                        element.addEventListener(
                            "mousedown",
                            function (e) {
                                e.preventDefault();
                                element.parentElement.focus();
                                this.selected = !this.selected;
                                return false;
                            },
                            false
                        );
                    });
                    let multiFields = document.querySelectorAll('[name^="UDF_NMULTI"]');
                    multiFields.forEach(function (element) {
                        element.addEventListener("focus", (event) => {
                            element.blur();
                        });
                    });
                };
                function generateCaptcha() {
                    if (captchaNeeded) {
                        document.getElementById("captcha").innerHTML = "";
                        var charsArray =
                            "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%";
                        var lengthOtp = 6;
                        var captcha = [];
                        for (var i = 0; i < lengthOtp; i++) {
                            var index = Math.floor(Math.random() * charsArray.length + 1);
                            if (captcha.indexOf(charsArray[index]) == -1)
                                captcha.push(charsArray[index]);
                            else i--;
                        }
                        var canv = document.createElement("canvas");
                        canv.id = "captcha";
                        canv.width = 100;
                        canv.height = 50;
                        var ctx = canv.getContext("2d");
                        ctx.font = "25px Georgia";
                        ctx.strokeText(captcha.join(""), 0, 30);
                        code = captcha.join("");
                        document.getElementById("captcha").appendChild(canv);
                    }
                }
                function addAttachment() {
                    var fileInput = document.getElementById("uploadfile");
                    var filename = fileInput.files[0].name;
                    document.getElementById("filename").innerHTML = filename;
                    document.getElementById("filename").style.display = "block";
                }
                function removeAttachement() {
                    document.getElementById("uploadfile").value = "";
                    document.getElementById("filename").value = "";
                    document.getElementById("filename").style.display = "none";
                }
                function validateForm() {
                    if (document.getElementById("uploadfile").value != "") {
                        var fsize =
                            document.getElementById("uploadfile").files[0].size / 1024 / 1024;
                        if (fsize > 125) {
                            alert("File size exceeds 125 MB");
                            return false;
                        }
                    }
                    var formName = document.fileabugform.subject.value;
                    var eMail = document.fileabugform.email.value.trim();
                    document.fileabugform.email.value = eMail;
                    if (formName == null || formName == "") {
                        alert("Please enter Issue name");
                        return false;
                    }
                    if (eMail == null || eMail == "") {
                        alert("Please enter a valid email address");
                        return false;
                    }
                    var atpos = eMail.indexOf("@");
                    var dotpos = eMail.lastIndexOf(".");
                    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= eMail.length) {
                        alert("Please enter a valid email address");
                        return false;
                    }
                    if (captchaNeeded) {
                        if (document.getElementById("input_captcha") == null) {
                            alert("Invalid Captcha");
                            return false;
                        }
                        if (code != document.getElementById("input_captcha").value) {
                            generateCaptcha();
                            alert("Invalid Captcha");
                            document.getElementById("input_captcha").value = "";
                            return false;
                        }
                    }
                    const maxSelectable = 20;
                    const mandatoryFields = document.querySelectorAll('[data-mand="true"]');
                    for (var idx = 0; idx < mandatoryFields.length; idx++) {
                        var currDiv = mandatoryFields[idx];
                        if (currDiv.value == undefined || currDiv.value.trim() == "") {
                            alert("The mandatory fields cannot be empty");
                            return false;
                        }
                    }
                    var customfields = document.getElementsByClassName("customFields");
                    const maxFieldMaxLengths = { multiline: 1000, singleline: 150 };
                    for (i = 0; i < customfields.length; i++) {
                        var name = customfields[i].getAttribute("name");
                        var fieldType = customfields[i].getAttribute("fieldType");
                        var label = customfields[i].getAttribute("data-lable-name");
                        if (
                            fieldType === "Date" &&
                            customfields[i].value != null &&
                            customfields[i].value != ""
                        ) {
                            if (
                                !customfields[i].value.match(
                                    /^(?:(0[1-9]|1[012])[\- \/.](0[1-9]|[12][0-9]|3[01])[\- \/.](19|20)[0-9]{2})$/
                                )
                            ) {
                                alert("Please enter a valid date");
                                return false;
                            }
                        }
                        if (
                            fieldType === "dateandtime" &&
                            customfields[i].value != null &&
                            customfields[i].value != ""
                        ) {
                            if (
                                !customfields[i].value.match(
                                    /^(?:(0[1-9]|1[012])[\- \/.](0[1-9]|[12][0-9]|3[01])[\- \/.](19|20)[0-9]{2}) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/
                                )
                            ) {
                                alert("Please enter a valid date");
                                return false;
                            }
                        }
                        if (
                            name.startsWith("UDF_NLONG") &&
                            customfields[i].value != null &&
                            customfields[i].value != ""
                        ) {
                            if (!customfields[i].value.match(/^[0-9]\d*$/)) {
                                alert("Please enter numeric value for " + label + "");
                                return false;
                            }
                        }
                        if (
                            (fieldType == "multiuserpicklist" || fieldType == "multipicklist") &&
                            customfields[i].selectedOptions.length > maxSelectable
                        ) {
                            alert("{0} cannot exceed 20 values".replace("{0}", label));
                            return false;
                        }
                        if (
                            fieldType == "phone" &&
                            customfields[i].value != "" &&
                            (!customfields[i].value.match(/^[0-9-( )+]+$/) ||
                                customfields[i].value.length > 15)
                        ) {
                            alert("Enter a valid phone number in {0} ".replace("{0}", label));
                            return false;
                        }
                        if (
                            fieldType == "email" &&
                            customfields[i].value != "" &&
                            !customfields[i].value.match(
                                /^[\w](['A-Za-z0-9._%\-+]*@[A-Za-z0-9-]+(\.[a-zA-Z0-9-]{1,22}){0,9}\.[a-zA-Z]{2,22})$/
                            )
                        ) {
                            alert("Enter a valid email ID in {0} ".replace("{0}", label));
                            return false;
                        }
                        if (
                            fieldType == "url" &&
                            customfields[i].value != "" &&
                            !customfields[i].value.match(
                                "^(?:(ftp|http|https)://|www.)?[a-zA-Z0-9]+([-\\.][a-zA-Z0-9]+){1,}(\\.[a-zA-Z0-9]{2,5})?(:[0-9]{1,5})?(/.*)?$ "
                            )
                        ) {
                            alert("Enter a valid URL in {0} ".replace("{0}", label));
                            return false;
                        }
                        if (
                            (fieldType == "decimal" || fieldType == "currency") &&
                            customfields[i].value != "" &&
                            !/^-{0,1}\d*\.{0,1}\d+$/.test(customfields[i].value)
                        ) {
                            alert("Please enter a valid value in {0} ".replace("{0}", label));
                            return false;
                        }
                        if (
                            fieldType == "percentage" &&
                            customfields[i].value != "" &&
                            !/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/.test(customfields[i].value)
                        ) {
                            alert("Please enter a valid value in {0}".replace("{0}", label));
                            return false;
                        }
                        if (
                            (fieldType == "multiline" || fieldType == "singleline") &&
                            customfields[i].value != "" &&
                            customfields[i].value.length > maxFieldMaxLengths[fieldType]
                        ) {
                            alert(
                                "{0} cannot exceed {1} characters"
                                    .replace("{0}", label)
                                    .replace("{1}", maxFieldMaxLengths[fieldType])
                            );
                            return false;
                        }
                    }
                    var dueDate = document.getElementById("dueDate")?.value;
                    if (dueDate != null && dueDate != "") {
                        if (
                            !dueDate.match(
                                /^((0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])-((19|20)\d\d))$/
                            )
                        ) {
                            alert("Please enter a valid date");
                            return false;
                        }
                    }
                    return true;
                }
                return { validateForm: validateForm, generateCaptcha: generateCaptcha };
            })();
    </script>
@endsection
