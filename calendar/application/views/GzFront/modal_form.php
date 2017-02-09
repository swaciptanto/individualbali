<?php foreach ($_GET['cid'] as $cid) { ?>
<div id="myBooking" class="modal fade booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
                <h4 id="myModalLabel" class="modal-title"><?php echo $tpl['calendar']['i18n'][$tpl['select_language']['id']]['title']; ?></h4>
            </div>
            <div class="modal-body">
                <form id="modal-webform-client-form" class="webform-client-form webform-client-form-1083" action="" method="post" accept-charset="UTF-8">
                    <div class="row form-group">
                        <?php
                        if (empty($tpl['currencies_select'])) {
                            $tpl['currencies_select'] = $tpl['option_arr_values']['currency'];
                        }
                        ?>
                        <div class="col-xs-12">
                            <select id="currencies-value-id" class="form-select input-sm form-control" name="currencies" tabindex="-1" aria-hidden="true">
                                <?php
                                foreach ($tpl['currencies'] as $key => $value) {
                                    ?>
                                    <option <?php echo ($tpl['currencies_select'] == $key) ? "selected='selected'" : ""; ?> value="<?php echo $key; ?>"><?php echo $key; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row meta form-group">
                        <div id="modal-total" class="price center"></div>
                        <!--modified: add new-->
                        <div id="modal-total-with-tax" class="price center"></div>
                    </div>
                    <?php
                    $date_fomrat = Util::getJsDateFormat($tpl['option_arr_values']['date_format']);
                    ?>
                    <div class="form-group input-group groupdate">
                        <input data-format="<?php echo $date_fomrat; ?>" id="modal-startdate" class="start-date-calendar required input-sm form-control" required="required" placeholder="start date" name="startdate" value="" size="60" maxlength="128" type="text">
                        <span class="input-group-addon date-to">to</span>
                        <input data-format="<?php echo $date_fomrat; ?>" id="modal-finishdate" class="end-date-calendar required input-sm form-control" required="required" placeholder="end date" name="finishdate" value="" size="60" maxlength="128" type="text">
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-12">
                            <div class="form-input">
                                <input id="edit-submitted-first-and-last-name1" class="form-text required form-control" required="required" placeholder="First and Last Name" name="name" value="" size="60" maxlength="128" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-12">
                            <div class="form-input">
                                <input id="edit-submitted-email1" class="email form-text form-email required form-control" required="required" placeholder="Email" name="email" size="60" type="email">
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-6">
                            <select id="edit-submitted-country-phone-code1" class="form-select required select-country select2-hidden-accessible" required="required" name="country_phone_code" tabindex="-1" aria-hidden="true">
                                <option value="213">Algeria (+213)</option>
                                <option value="376">Andorra (+376)</option>
                                <option value="244">Angola (+244)</option>
                                <option value="1264">Anguilla (+1264)</option>
                                <option value="1268">Antigua &amp; Barbuda (+1268)</option>
                                <option value="54">Argentina (+54)</option>
                                <option value="374">Armenia (+374)</option>
                                <option value="297">Aruba (+297)</option>
                                <option value="61">Australia (+61)</option>
                                <option value="43">Austria (+43)</option>
                                <option value="994">Azerbaijan (+994)</option>
                                <option value="1242">Bahamas (+1242)</option>
                                <option value="973">Bahrain (+973)</option>
                                <option value="880">Bangladesh (+880)</option>
                                <option value="1246">Barbados (+1246)</option>
                                <option value="375">Belarus (+375)</option>
                                <option value="32">Belgium (+32)</option>
                                <option value="501">Belize (+501)</option>
                                <option value="229">Benin (+229)</option>
                                <option value="1441">Bermuda (+1441)</option>
                                <option value="975">Bhutan (+975)</option>
                                <option value="591">Bolivia (+591)</option>
                                <option value="387">Bosnia Herzegovina (+387)</option>
                                <option value="267">Botswana (+267)</option>
                                <option value="55">Brazil (+55)</option>
                                <option value="673">Brunei (+673)</option>
                                <option value="359">Bulgaria (+359)</option>
                                <option value="226">Burkina Faso (+226)</option>
                                <option value="257">Burundi (+257)</option>
                                <option value="855">Cambodia (+855)</option>
                                <option value="237">Cameroon (+237)</option>
                                <option value="1">Canada (+1)</option>
                                <option value="238">Cape Verde Islands (+238)</option>
                                <option value="1345">Cayman Islands (+1345)</option>
                                <option value="236">Central African Republic (+236)</option>
                                <option value="56">Chile (+56)</option>
                                <option value="86">China (+86)</option>
                                <option value="57">Colombia (+57)</option>
                                <option value="269">Comoros (+269)</option>
                                <option value="242">Congo (+242)</option>
                                <option value="682">Cook Islands (+682)</option>
                                <option value="506">Costa Rica (+506)</option>
                                <option value="385">Croatia (+385)</option>
                                <option value="53">Cuba (+53)</option>
                                <option value="90392">Cyprus North (+90392)</option>
                                <option value="357">Cyprus South (+357)</option>
                                <option value="42">Czech Republic (+42)</option>
                                <option value="45">Denmark (+45)</option>
                                <option value="253">Djibouti (+253)</option>
                                <option value="1809">Dominican Republic (+1809)</option>
                                <option value="593">Ecuador (+593)</option>
                                <option value="20">Egypt (+20)</option>
                                <option value="503">El Salvador (+503)</option>
                                <option value="240">Equatorial Guinea (+240)</option>
                                <option value="291">Eritrea (+291)</option>
                                <option value="372">Estonia (+372)</option>
                                <option value="251">Ethiopia (+251)</option>
                                <option value="500">Falkland Islands (+500)</option>
                                <option value="298">Faroe Islands (+298)</option>
                                <option value="679">Fiji (+679)</option>
                                <option value="358">Finland (+358)</option>
                                <option value="33">France (+33)</option>
                                <option value="594">French Guiana (+594)</option>
                                <option value="689">French Polynesia (+689)</option>
                                <option value="241">Gabon (+241)</option>
                                <option value="220">Gambia (+220)</option>
                                <option value="7880">Georgia (+7880)</option>
                                <option value="49">Germany (+49)</option>
                                <option value="233">Ghana (+233)</option>
                                <option value="350">Gibraltar (+350)</option>
                                <option value="30">Greece (+30)</option>
                                <option value="299">Greenland (+299)</option>
                                <option value="1473">Grenada (+1473)</option>
                                <option value="590">Guadeloupe (+590)</option>
                                <option value="671">Guam (+671)</option>
                                <option value="502">Guatemala (+502)</option>
                                <option value="224">Guinea (+224)</option>
                                <option value="245">Guinea - Bissau (+245)</option>
                                <option value="592">Guyana (+592)</option>
                                <option value="509">Haiti (+509)</option>
                                <option value="504">Honduras (+504)</option>
                                <option value="852">Hong Kong (+852)</option>
                                <option value="36">Hungary (+36)</option>
                                <option value="354">Iceland (+354)</option>
                                <option value="91">India (+91)</option>
                                <option value="62">Indonesia (+62)</option>
                                <option value="98">Iran (+98)</option>
                                <option value="964">Iraq (+964)</option>
                                <option value="353">Ireland (+353)</option>
                                <option value="972">Israel (+972)</option>
                                <option value="39">Italy (+39)</option>
                                <option value="1876">Jamaica (+1876)</option>
                                <option value="81">Japan (+81)</option>
                                <option value="962">Jordan (+962)</option>
                                <option value="254">Kenya (+254)</option>
                                <option value="686">Kiribati (+686)</option>
                                <option value="850">Korea North (+850)</option>
                                <option value="82">Korea South (+82)</option>
                                <option value="965">Kuwait (+965)</option>
                                <option value="996">Kyrgyzstan (+996)</option>
                                <option value="856">Laos (+856)</option>
                                <option value="371">Latvia (+371)</option>
                                <option value="961">Lebanon (+961)</option>
                                <option value="266">Lesotho (+266)</option>
                                <option value="231">Liberia (+231)</option>
                                <option value="218">Libya (+218)</option>
                                <option value="417">Liechtenstein (+417)</option>
                                <option value="370">Lithuania (+370)</option>
                                <option value="352">Luxembourg (+352)</option>
                                <option value="853">Macao (+853)</option>
                                <option value="389">Macedonia (+389)</option>
                                <option value="261">Madagascar (+261)</option>
                                <option value="265">Malawi (+265)</option>
                                <option value="60">Malaysia (+60)</option>
                                <option value="960">Maldives (+960)</option>
                                <option value="223">Mali (+223)</option>
                                <option value="356">Malta (+356)</option>
                                <option value="692">Marshall Islands (+692)</option>
                                <option value="596">Martinique (+596)</option>
                                <option value="222">Mauritania (+222)</option>
                                <option value="52">Mexico (+52)</option>
                                <option value="691">Micronesia (+691)</option>
                                <option value="373">Moldova (+373)</option>
                                <option value="377">Monaco (+377)</option>
                                <option value="976">Mongolia (+976)</option>
                                <option value="1664">Montserrat (+1664)</option>
                                <option value="212">Morocco (+212)</option>
                                <option value="258">Mozambique (+258)</option>
                                <option value="95">Myanmar (+95)</option>
                                <option value="264">Namibia (+264)</option>
                                <option value="674">Nauru (+674)</option>
                                <option value="977">Nepal (+977)</option>
                                <option value="31">Netherlands (+31)</option>
                                <option value="687">New Caledonia (+687)</option>
                                <option value="64">New Zealand (+64)</option>
                                <option value="505">Nicaragua (+505)</option>
                                <option value="227">Niger (+227)</option>
                                <option value="234">Nigeria (+234)</option>
                                <option value="683">Niue (+683)</option>
                                <option value="672">Norfolk Islands (+672)</option>
                                <option value="670">Northern Marianas (+670)</option>
                                <option value="47">Norway (+47)</option>
                                <option value="968">Oman (+968)</option>
                                <option value="680">Palau (+680)</option>
                                <option value="507">Panama (+507)</option>
                                <option value="675">Papua New Guinea (+675)</option>
                                <option value="595">Paraguay (+595)</option>
                                <option value="51">Peru (+51)</option>
                                <option value="63">Philippines (+63)</option>
                                <option value="48">Poland (+48)</option>
                                <option value="351">Portugal (+351)</option>
                                <option value="1787">Puerto Rico (+1787)</option>
                                <option value="974">Qatar (+974)</option>
                                <option value="262">Reunion (+262)</option>
                                <option value="40">Romania (+40)</option>
                                <option value="7">Russia (+7)</option>
                                <option value="250">Rwanda (+250)</option>
                                <option value="378">San Marino (+378)</option>
                                <option value="239">Sao Tome &amp; Principe (+239)</option>
                                <option value="966">Saudi Arabia (+966)</option>
                                <option value="221">Senegal (+221)</option>
                                <option value="381">Serbia (+381)</option>
                                <option value="248">Seychelles (+248)</option>
                                <option value="232">Sierra Leone (+232)</option>
                                <option value="65">Singapore (+65)</option>
                                <option value="421">Slovak Republic (+421)</option>
                                <option value="386">Slovenia (+386)</option>
                                <option value="677">Solomon Islands (+677)</option>
                                <option value="252">Somalia (+252)</option>
                                <option value="27">South Africa (+27)</option>
                                <option value="34">Spain (+34)</option>
                                <option value="94">Sri Lanka (+94)</option>
                                <option value="290">St. Helena (+290)</option>
                                <option value="1869">St. Kitts (+1869)</option>
                                <option value="1758">St. Lucia (+1758)</option>
                                <option value="249">Sudan (+249)</option>
                                <option value="597">Suriname (+597)</option>
                                <option value="268">Swaziland (+268)</option>
                                <option value="46">Sweden (+46)</option>
                                <option value="41">Switzerland (+41)</option>
                                <option value="963">Syria (+963)</option>
                                <option value="886">Taiwan (+886)</option>
                                <option value="66">Thailand (+66)</option>
                                <option value="228">Togo (+228)</option>
                                <option value="676">Tonga (+676)</option>
                                <option value="1868">Trinidad &amp; Tobago (+1868)</option>
                                <option value="216">Tunisia (+216)</option>
                                <option value="90">Turkey (+90)</option>
                                <option value="993">Turkmenistan (+993)</option>
                                <option value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                <option value="688">Tuvalu (+688)</option>
                                <option value="256">Uganda (+256)</option>
                                <option value="380">Ukraine (+380)</option>
                                <option value="971">United Arab Emirates (+971)</option>
                                <option value="44" selected="selected">UK (+44)</option>
                                <option value="598">Uruguay (+598)</option>
                                <option value="678">Vanuatu (+678)</option>
                                <option value="379">Vatican City (+379)</option>
                                <option value="58">Venezuela (+58)</option>
                                <option value="84">Vietnam (+84)</option>
                                <option value="1284">Virgin Islands - British (+1284)</option>
                                <option value="681">Wallis &amp; Futuna (+681)</option>
                                <option value="969">Yemen (North)(+969)</option>
                                <option value="967">Yemen (South)(+967)</option>
                                <option value="260">Zambia (+260)</option>
                                <option value="263">Zimbabwe (+263)</option>
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <input id="edit-submitted-phone-number1" class="form-text form-number required form-control" required="required" name="phone_number" step="any" placeholder="Phone number" type="text">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-12">
                            <textarea id="edit-submitted-message1" class="form-textarea" placeholder="Please inform us if you need an additional bed, travel with infants, or have specific needs..." name="message" cols="32" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row i-agree">
                        <div class="checkbox col-xs-12">
                            <label>
                                <div id="edit-submitted-i-agree1" class="form-checkboxes">
                                    <input id="edit-submitted-i-agree" class="form-checkbox" required="required" name="i_agree" value="1" type="checkbox">
                                </div>
                                I agree to the
                                <a href="">Terms of Service.</a>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-md btn-inquiry btn-modal btn-block" type="submit">Send Enquiry</button>
                            <?php if (isset($tpl['ical_url'][$cid])
                                    && $tpl['ical_url'][$cid] !== ''
                                    && !is_null($tpl['ical_url'][$cid])) { ?>
                            <!-- modified: add new-->
                            <p class="text-center">or</p>
                            <!-- modified: add new-->
                            <button class="btn btn-md btn-book-now btn-block" type="submit">Book Now</button>
                            <?php } ?>
                            <p class="text-center">
                                Do you have any questions?
                                <br>
                                Call Now
                                <a href="tel:+62 811-399-513">
                                    <i class="fa fa-phone"></i>
                                    +62 811-399-513
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="text-center" id="result-message-id">

                            </p>
                        </div>
                    </div>
                    <div class="share">
                        <ul class="list-unstyled row">
                            <li class="col-xs-4">
                                <a class="share-btn facebook" href="https://www.facebook.com/sharer/sharer.php?u=http://www.individualbali.com/villas/verve-villa-lombok" target="_blank">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li class="col-xs-4">
                                <a class="share-btn twitter" href="https://twitter.com/intent/tweet?text=&url=http://www.individualbali.com/villas/verve-villa-lombok&via=individualbali" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li class="col-xs-4">
                                <a class="share-btn google-plus" href="https://plus.google.com/share?url=http://www.individualbali.com/villas/verve-villa-lombok" target="_blank">
                                    <i class="fa fa-google-plus"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
    var GzAvailabilityCalendarObj = new Array();

    (function () {
        "use strict";
        var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor), loadCssHack = function (url, callback) {
            var link = document.createElement("link");
            link.type = "text/css";
            link.rel = "stylesheet";
            link.href = url;
            document.getElementsByTagName("head")[0].appendChild(link);
            var img = document.createElement("img");
            img.onerror = function () {
                if (callback && typeof callback === "function") {
                    callback();
                }
            };
            img.src = url;
        }, loadRemote = function (url, type, callback) {
            if (type === "css" && isSafari) {
                loadCssHack(url, callback);
                return;
            }
            var _element, _type, _attr, scr, s, element;
            switch (type) {
                case "css":
                    _element = "link";
                    _type = "text/css";
                    _attr = "href";
                    break;
                case "js":
                    _element = "script";
                    _type = "text/javascript";
                    _attr = "src";
                    break;
            }
            scr = document.getElementsByTagName(_element);
            s = scr[scr.length - 1];
            element = document.createElement(_element);
            element.type = _type;
            if (type == "css") {
                element.rel = "stylesheet";
            }
            if (element.readyState) {
                element.onreadystatechange = function () {
                    if (element.readyState == "loaded" || element.readyState == "complete") {
                        element.onreadystatechange = null;
                        if (callback && typeof callback === "function") {
                            callback();
                        }
                    }
                };
            } else {
                element.onload = function () {
                    if (callback && typeof callback === "function") {
                        callback();
                    }
                };
            }
            element[_attr] = url;
            s.parentNode.insertBefore(element, s.nextSibling);
        }, loadScript = function (url, callback) {
            loadRemote(url, "js", callback);
        }, loadCss = function (url, callback) {
            loadRemote(url, "css", callback);
        };
<?php
foreach ($this->controller->js as $js) {
    ?>
            loadScript("<?php echo (isset($js['remote']) && $js['remote'] ? NULL : INSTALL_URL) . $js['path'] . $js['file']; ?>", function () {
<?php } ?>

            var c = 0;
<?php
foreach ($_GET['cid'] as $cid) {
    ?>
                var options = {
                    cal_id: <?php echo $cid; ?>,
                    action: "<?php echo @$_GET['action']; ?>",
                    server: "<?php echo INSTALL_FOLDER; ?>",
                    folder: "<?php echo INSTALL_FOLDER; ?>",
                    month: "<?php echo date('n'); ?>",
                    year: "<?php echo date('Y'); ?>",
                    view_month: "<?php echo $_GET['view_month']; ?>",
                    villa_node_id: "<?php echo @$_GET['vnid']; ?>",
                    enable_booking: "<?php echo $tpl['option_arr_values'][$cid]['enable_booking']; ?>",
                    based_on: "<?php echo $tpl['option_arr_values'][$cid]['based_on']; ?>",
                    locale: 1,
                    bookedDate: ['<?php echo implode("','", $tpl['reservation_info']); ?>'],
                    hide: 0
                };
                GzAvailabilityCalendarObj[c] = new GzAvailabilityCalendar(options);
                c++;
    <?php
}
?>
<?php
foreach ($this->controller->js as $js) {
    ?>
            });
<?php } ?>
    })();
</script>