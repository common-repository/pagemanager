/**
 * Created by ckermer on 16.05.14.
 */

/**
 * TODO: description
 * @param target
 */
function showSpinner(target) {
    $j('<span/>', {
        class: 'spinner',
        style: 'display: inline',
        html: '<i class="fa fa-2x fa-spinner fa-spin"></i>'
    }).appendTo(target);
}

/**
 * TODO: description
 * @param target
 */
function removeSpinner(target) {
    $j(target + ' span.spinner').remove();
}

/**
 * TODO: description
 * @param blockConfig
 * @param blockSettings
 */
function createBlockPanel(blockConfig, blockSettings) {
    var inputFields = '';
    var panelClass = '';
    var blockSetting = blockSettings;
    var attrValue = '';

    $j.each(blockConfig.attributes, function($attr, $attrObj){
        var attrName = $attrObj['name'];
        var btnExtraClass = $attrObj['class'] ? $attrObj['class'] : "";
        if (! blockSetting.hasOwnProperty(attrName)){
            attrValue = $attrObj['default'] != 'false' && $attrObj['default'] != false ?  $attrObj['default'] : "";
        } else {
            attrValue = blockSetting[attrName].split("\\").join("");
        }
        switch($attrObj['input-type']){
            case 'hr':
                inputFields = inputFields + '<span class="hr"/>';
                break;
            case 'textarea':
                panelClass  = 'panel-type-textarea';
                inputFields = inputFields +
                    '<label for="input-' + attrName + '">' + $attrObj['label'] + ': <span class="info">' + $attrObj['help'] + '</span></label>'+
                    '<textarea class="input input-' + attrName + '">' + attrValue + '</textarea><br/>'  ;
                break;
            case 'select':
                inputFields = inputFields +
                    '<label for="input-' + attrName + '">' + $attrObj['label'] + ': <span class="info">' + $attrObj['help'] + '</span></label>'+
                    '<input  type="hidden" class="input input-' + attrName + '" value="' + attrValue + '">' +
                    $attrObj['options'].map(function(elem){
                        var btnclass = attrValue == elem.value ? ' active' : '';
                        return '<button type="button" title="' + elem['label'] + '" class="btn btn-select layout' + btnclass + ' ' + btnExtraClass + '" data-layout="' + (elem.value != undefined ? elem.value : '') + '" ' + (elem.quantity != undefined ? 'data-quantity="' + elem.quantity + '" data-featured-pos="' + elem['featured-pos'] + '"' : '')  + '>' + elem.icon + '</button>';
                    }).join("") + '<span style="display: block;height: 20px;"/>' ;
                break;
            case 'checkbox':
                inputFields = inputFields +
                    '<input class="input input-' + attrName + '" type="checkbox" value="' + $attrObj['value'] + '" ' + ( $attrObj['value']==attrValue ? 'checked' : '') + '>' +
                    '<b>' + $attrObj['label'] + '</b>  <small>' + $attrObj['help'] + '</small>';
                break;
            case 'gallery':
            case 'post':
                inputFields = inputFields +
                    '<label for="input-' + attrName + '">' + $attrObj['label'] + ':</label>'+
                    '<span class="helper"><span class="sortable"></span><input type="text" class="input-helper helper-' + $attrObj['input-type'] + '" value="" placeholder="' + $attrObj['help'] + '">' +
                    '<input class="input input-' + attrName + '" type="hidden" value="' + attrValue + '"></span><br/>'+
                    '<script language="JavaScript">window.bindHelperSearch();</script>' ;
                break;
            case 'term':
                inputFields = inputFields +
                    '<label for="input-' + attrName + '">' + $attrObj['label'] + ':</label>'+
                    '<span class="helper"><span class="sortable"></span><input type="text" class="input-helper helper-' + $attrObj['input-type'] + '" value="" placeholder="' + $attrObj['help'] + '">' +
                    '<input class="input input-' + attrName + '" type="hidden" value="' + attrValue + '"></span><br/>'+
                    '<script language="JavaScript">window.bindHelperSearch();</script>'  ;
                break;
            case 'geocat':
                inputFields = inputFields +
                    '<label for="input-' + attrName + '">' + $attrObj['label'] + ':</label>'+
                    '<span class="helper"><span class="sortable"></span><input type="text" class="input-helper helper-' + $attrObj['input-type'] + '" value="" placeholder="' + $attrObj['help'] + '">' +
                    '<input class="input input-' + attrName + '" type="hidden" value="' + attrValue + '"></span><br/>'+
                    '<script language="JavaScript">window.bindHelperSearch();</script>'  ;
                break;
            case 'hidden':
                inputFields = inputFields +
                    '<input class="input input-' + attrName + '" type="hidden" value="' + attrValue + '">' ;
                break;
            case 'text':
            default:
                inputFields = inputFields +
                    '<label for="input-' + attrName + '">' + $attrObj['label'] + ': <span class="info">' + $attrObj['help'] + '</span></label>'+
                    '<input class="input input-' + attrName + '" type="text" value="' + attrValue + '"' + ($attrObj['input-attribute'] != undefined ? ' ' + $attrObj['input-attribute'] : '')   + '><br/>';
                if ($attrObj['help_extra'] ) {
                    inputFields = inputFields +
                        '<label class="extra"><span class="extrainfo">' + $attrObj['help_extra'] + '</span></label>';
                }
                break;
        }
    });

    var blockTitle = blockSetting['title'] != undefined ? ' - ' + blockSetting['title'] : '';
    var shortcodeLink = '';
    if (blockConfig.name != 'html' && blockConfig.name != 'script') {
        shortcodeLink = '<a class="pull-right btn btn-default shortcode">[ ]</a>';
    }
    $j('<div/>', {
        class: 'panel panel-shortcode panel-' + blockConfig.name + ' expanded',
        html: '<button class="pull-right btn btn-default move-down"><i class="fa fa-arrow-down"></i></button>' +
        '<button class="pull-right btn btn-default move-up"><i class="fa fa-arrow-up"></i></button>' +
        '<button class="pull-right btn btn-default remove"><i class="fa fa-trash-o"></i></button>' +
        shortcodeLink +
        '<div class="panel-heading ' + panelClass +'">' +
        '<h4 class="panel-title" data="' + blockConfig.name + '">' + blockConfig.icon + blockConfig.label + '<span class="info">' + blockTitle + '</span></h4>' +
        '</div>' +
        '<p>' + inputFields + '</p>' +
        '<div class="pull-center"><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i></div>' +
        '</div>'
    }).appendTo('div#sortable');
    inputEventHandler();
}

/**
 * TODO: description
 */
function initBlockTypes(){
    var $blocksConfig = window.$blocksConfig;
    $j.each($blocksConfig, function($blockKey, $singleBlock){
        window.blocksConfigObj[$singleBlock.name] = $singleBlock;
    });
    return this;
}

/**
 * TODO: description
 */
function initBlocks(){
    $j.each(window.$blocksSettings, function(key, blockSettings){
        if (blocksConfigObj[blockSettings['type']] != undefined) {
            createBlockPanel(blocksConfigObj[blockSettings['type']], blockSettings['settings']);
        }
    });
}

var in_array = function (needle, haystack) {
    var result = false;
    $j.each(haystack, function (targetKey, validTarget) {
        if (needle == validTarget) {
            result = true;
        }
    });
    return result;
}

/**
 * TODO: description
 */
function initButtons(){
    $j.each(blocksConfigObj, function(key, blockObj){
        var currentTarget = $j('span.page-target').attr('target');
        if (blockObj.pageType == null || in_array(currentTarget , blockObj.pageType) == true) {
            $j('<button>', {
                class: 'btn btn-default btn-add add-' + blockObj.name,
                html:'<i class="fa fa-plus"></i> ' + blockObj.label
            }).appendTo('div.btn-group-add');
        }
    })
}

/**
 * TODO: description
 */
function initPages(){
    var blocktypeSequence = window.$pageList;
    $j.each(blocktypeSequence, function(key,targetPage){
        var $targetCaseSettings = window.$settingsIdInfo[targetPage.type];

        var finalBlockTitle = targetPage.label;
        var addInput = '';
        switch (typeof(targetPage.caseIdSource)) {
            case "string":
                var caseLinks = '';
                $j.each($targetCaseSettings, function(targetCaseKey, targetCase) {
                    if (targetCase != false) {
                        caseLinks = caseLinks + '<option value="' + targetCaseKey + '">' + targetCase + '</option>';
                    }
                });
                addInput = '<div class="input-group">' +
                    '<input type="text" id="case" placeholder="' + targetPage.caseIdSource + ' ID" class="form-control input-target-case" value=""/>' +
                    '<span class="input-group-btn"><button type="button" class="btn btn-default">Add</button></span>' +
                    '</div>';
                var rows = typeof($targetCaseSettings) == 'object'
                    ? Object.keys($targetCaseSettings).length
                    : $targetCaseSettings.length;
                if (rows < 2) rows = 2;
                $j('<div>',{
                    class: 'panel panel-pages',
                    html: '<div class="panel-heading" data-target="' + targetPage.type + '">' +
                    '<strong>' + finalBlockTitle + '</strong></div>' +
                    '<select class="select-page" targetpage="' + targetPage.type + '" size="' + (rows > 5 ? 5 : rows) + '">'
                    + caseLinks + ' </select>' + addInput
                }).insertBefore('div#history');
                break;
            case "object":
                if (Object.keys(targetPage.caseIdSource).length > 1) {
                    var caseLinks = '';
                    $j.each($targetCaseSettings, function(targetCaseKey, targetCase) {
                        caseLinks = caseLinks + '<option value="' + targetCaseKey + '">' + targetCase + '</option>';
                    });
                    var rows = typeof($targetCaseSettings) == 'object'
                        ? Object.keys($targetCaseSettings).length
                        : $targetCaseSettings.length;
                    if (rows < 2) rows = 2;
                    $j('<div>',{
                        class: 'panel panel-pages',
                        html: '<div class="panel-heading" data-target="' + targetPage.type + '">' +
                        '<strong>' + finalBlockTitle + '</strong></div>' +
                        '<select class="select-page"  targetpage="' +
                        '' + targetPage + '" size="' + (rows > 5 ? 5 : rows) + '">' + caseLinks + ' </select>' + addInput
                    }).insertBefore('div#history');
                } else {
                    $j('<div>',{
                        class: 'panel panel-pages',
                        html: '<div class="panel-heading" data-target="' + targetPage.type + '">' +
                        '<a href="?page=pagemanager&target=' + targetPage.type + '" style="float: right"><i class="fa fa-lg fa-pencil-square" aria-hidden="true"></i></a>' +
                        '<strong>' + finalBlockTitle + '</strong></div>' + ''
                        // '<select class="select-page"  targetpage="' +
                        // '' + targetPage + '">' + caseLinks + ' </select>' + addInput
                    }).insertBefore('div#history');

                }
                break;
        }

        if (targetPage == 'advertising') {
            var caseLinks = '<option value="">Bitte wählen</option>';
            $j.each($adCases, function(caseId, caseName){
                caseLinks = caseLinks + '<option value="' + caseId + '">' + caseId
                    + ' - ' + caseName
                    + '</option>';
            });
        }
        // $j('<div>',{
        //   class: 'panel panel-pages',
        //   html: '<div class="panel-heading" data-target="' + targetPage['name'] + '">' +
        //     '<strong>' + finalBlockTitle + '</strong> (' + targetPage['name'] + ')</div>' +
        //     '<select class="select-page" size="5" targetpage="' +
        //         '' + targetPage['name'] + '">' + caseLinks + ' </select>' + addInput
        // }).insertBefore('div#history');
    });
}

/**
 * TODO: description
 */
function refresh() {
    window.$blocksSettings = new Array();
    $j(".panel-shortcode").each(function(){
        var singleBlockSettings = new Object();
        singleBlockSettings['type'] = $j(this).find('.panel-heading h4').attr('data');
        singleBlockSettings['settings'] = new Object();
        $j(this).find('.input').each(function (){
            if (($j(this).attr('type') == 'checkbox' && $j(this).prop('checked')) || $j(this).attr('type') != 'checkbox') {
                singleBlockSettings['settings'][$j(this).attr('class').substr(12)] = $j(this).val();
            }
        });
        window.$blocksSettings.push(singleBlockSettings);
    });
}

/**
 * TODO: description
 */
function refreshSettings(){
    refresh();
    if ($j('.page-target-case.error').text() == '') {
        showChangeWarning();
    }
}

/**
 * TODO: description
 */
function showChangeWarning(){
    $j('.info-warning').remove();
    $j('<div/>', {
        class: 'info-warning',
        html: '<button type="button" class="btn btn-save pull-right"> Save</button> You have unsaved changes.'
    }).insertBefore($j('.btn-group-add.manage'));
}

/**
 * TODO: description
 */
function showSaveWarning(){
    $j('.info-warning').remove();
    $j('<div/>', {
        class: 'info-warning',
        html: 'It was not possible to save your PageManager settings because there has been a new version saved in between.' +
        'Please <a href="javascript:location.reload();" >reload</a>.'
    }).insertBefore($j('.btn-group-add'));
}

/**
 * TODO: description
 */
function generateShortcodes(){
    var shortCodes = new Array();
    $j(".panel-shortcode").each(function(){
        var singleShortCode = new Array();
        singleShortCode.push('[' + $j(this).find('.panel-heading h4').text());
        $j(this).find('label').each(function (){
            var labelString = $j(this).text();
            $j(this).closest('input').css("backround-color: #CCDDDD;");
            singleShortCode.push(/[^:]*/.exec(labelString)[0] + '="' + $j(this).next().val() + '"');
        });
        shortCodes.push(singleShortCode.join(" ") + ']');
    });
    $j('textarea#code').val(shortCodes.join("\n"));
}

/**
 * TODO: description
 */
function collapseAllPanels(){
    $j('.panel-shortcode').each(function(){
        $j('.panel-shortcode').removeClass('expanded').addClass('collapsed');
    });
}

/**
 * TODO: description
 */
function inputEventHandler(){
    $j('#sortable input,#sortable textarea').keyup(function(el){
        if(el.currentTarget.defaultValue != el.currentTarget.value){
            refreshSettings();
        }
    });
    $j('#sortable input[type="checkbox"]').click(function(el){
        refreshSettings();
    });
    $j('.input-title').keyup(function(el){
        if(el.currentTarget.defaultValue != el.currentTarget.value){
            $j(this).closest('.panel').find('h4 span.info').html(' - ' + $j(this).val());
        }
    });
    $j('.input-terms,.input-terms-exclude,.input-navi-terms,.input-posts').unbind('change').change(function(){
        explainIds();
    });
}

/**
 * TODO: description
 */
function saveSettings(){
    var returnValue = false;
    refresh();
    $j('<i/>', { class: 'fa fa-cog fa-spin'}).prependTo($j(".info-warning button"));
    $j.post("/pagemanager-api?action=save-settings",
        {
            pagetype: $j('.page-target').attr('target'),
            case: $j('.page-target-case').attr('case'),
            settings: window.$blocksSettings,
            version: window.$version
        }
    ).done(function (data) {
        returnValue = data;
        if (data) {
            $j('div.info-warning').remove();
        }
        if (data.errormessage == 'version'){
            showSaveWarning();
        } else {
            var response = JSON.parse(data);
            window.$version = response["currentVersion"];
        }
    });
}

/**
 * TODO: description
 */
function explainIds(){
    $j('span.info.explain').remove();
    $j("input.input-terms,input.input-term1,input.input-term2,input.input-term3,input.input-navi-terms,input.input-geocat,.input-terms-exclude").each(function(elementkey, element){
        if($j(this).val() != undefined && $j(this).val() != ''){
            $j('<i/>', { class: 'fa fa-cog fa-spin'}).prependTo($j(element).closest("span"));
            $j.getJSON("/pagemanager-api?action=display-terms&ids="+$j(this).val(), function (data) {
                $j.each(data['data'], function(termkey, term){
                    displayInputValues($j(element).closest("span"), term.term_id, term.name, term.taxonomy);
                    $j(element).closest("span").find("i.fa-spin").remove();
                    $j("div.selected-value button").unbind('click').on('click', function() {
                        removeValue($j(this).data("value"), $j(this).closest("span.helper").find("input.input"));
                        $j(this).closest("div").remove();
                    });
                });
            });
        }
    });
    $j("input.input-posts,input.input-post,input.input-featured-posts,input.input-gallery").each(function(elementkey, element){
        if($j(this).val() != undefined && $j(this).val() != ''){
            $j('<i/>', { class: 'fa fa-cog fa-spin'}).prependTo($j(element).closest("span"));
            var posttype = $j(element).closest('p').find("input.input-post_type").val();
            if (posttype == undefined) {
                posttype = $j(element).parent().closest("p").find("input.input-post-type").val();
            }
            $j.getJSON("/pagemanager-api?action=display-posts&ids="+$j(this).val()+'&post_type='+posttype, function (data) {
                $j.each(data['data'], function(termkey, term){
                    displayInputValues($j(element).closest("span"), term.ID, term.post_title, term.post_type);
                    $j(element).closest("span").find("i.fa-spin").remove();
                    $j("div.selected-value button").unbind('click').on('click', function() {
                        removeValue($j(this).data("value"), $j(this).closest("span.helper").find("input.input"));
                        $j(this).closest("div").remove();
                    });
                });
            });
        }
    });
}

/**
 * TODO: description
 * @param input
 */
var updateHelperSearchResults = function(input) {
    var params,
        minSearchLength = 2,
        q = input.val();

    if ((!(q % 1 == 0) && q.length < minSearchLength) || q.length == 0) return;

    var helper,
        posttype = '';

    if (input.hasClass('helper-post')){
        helper = 'post';
        posttype = $j(input).closest('p').find('input.input-post-type').val();
    }
    if (input.hasClass('helper-gallery')){
        helper = 'gallery';
    }
    if (input.hasClass('helper-term')){
        helper = 'term';
    }
    if (input.hasClass('helper-geocat')){
        helper = 'geocat';
    }

    params = {
        'action': 'pagemanager-helper-search',
        'response-format': 'markup',
        'q': q,
        'helper' : helper,
        'post-type': posttype
    };

    $j('p.quick-search-wrap span.spinner').show();

    $j.post( ajaxurl, params, function(menuMarkup) {
        processQuickSearchQueryResponse(menuMarkup, input);
    });
};

/**
 * TODO: description
 * @param existingValue
 * @param addedValue
 * @returns {*}
 */
var addValue = function (existingValue, addedValue) {
    var values = existingValue.split(",");
    if (values[0].length != 0) {
        for (var i = 0; i < values.length; i++) {
            if (values[i] == addedValue) {
                return existingValue;
            }
        }
        values.push(addedValue);
        return values.join(",");
    }
    return addedValue;
};

/**
 * TODO: description
 * @param removeValue
 * @param target
 */
var removeValue = function (removeValue, target) {
    var values = target.val().split(",");
    if (values[0].length != 0) {
        for (var i = 0; i < values.length; i++) {
            if (values[i] == removeValue) {
                delete values[i];
            }
        }
        values = values.filter(function(n){ return n != undefined });
        target.val(values.join(","));
    }
};

/**
 * TODO: description
 * @param target
 * @param value
 * @param label
 */
var displayInputValues = function (target, value, label, posttype) {
    //check if it isn't displayed already
    var display = true;
    $j.each($j(target).closest('span.helper').find("div.selected-value"), function(key, element){
        if ($j(element).find('button').data('value')/1 == value/1) {
            display = false;
        }
    });

    if (display == true) {
        var html = '<div class="selected-value" id="selected-value-' + value + '"><a href="post.php?post=' + value + '&action=edit" target="_blank" title="">' + label + '</a> (' + posttype + ') <button data-value="' + value + '"><i class="fa fa-lg fa-times-circle"></i></button></div>';
        $j(target).find("span.sortable").append(html);
        $j("#selected-value-" + value).closest("span.sortable").sortable({
            cursor: 'move',
            stop: function (event, ui) {
                var values = [];
                $j.each($j(this).closest('span.sortable').find("button"), function(key, element){
                    values.push($j(element).data('value'));
                });
                $j(this).closest("span.helper").find('input.input').val(values.join(','));
            }
        });
    }
};

/**
 * TODO: description
 * @param menuMarkup
 * @param input
 */
var processQuickSearchQueryResponse = function (menuMarkup, input) {
    $j(input).parent().find("ul.autosuggest").remove();
    $j(menuMarkup).insertAfter(input);
    $j("ul.autosuggest li").unbind('click', processQuickSearchQueryResponse);

    $j("ul.autosuggest li").on('click', function (){
        if ($j(this).data("id") != undefined) {
            var helperSearchTimer = window.helperSearchTimer;
            if(helperSearchTimer) clearTimeout(helperSearchTimer);
            $inputTarget = $j(this).closest("span").find("input.input");

            displayInputValues($j(this).closest("span"), $j(this).data("id"), $j(this).text());

            $inputTarget.val(addValue($inputTarget.val(), $j(this).data("id")));

            $j("div.selected-value button").unbind('click').on('click', function() {
                removeValue($j(this).data("value"), $j(this).closest("span.helper").find("input.input"));
                $j(this).closest("div").remove();
            });

            $j(this).closest("span").find("input.input-helper").val("");
        }
        $j(this).closest("ul").remove();
    });
};

/**
 * TODO: description
 */
var bindHelperSearch = function() {
    $j('input.input-helper').unbind('keypress', bindHelperSearch);
    $j('input.input-helper').keypress(function(e){
        var helperSearchTimer = window.helperSearchTimer;
        var t = $j(this);

        if( 13 == e.which && t.val().length > 0) {
            updateHelperSearchResults(t);
            return false;
        }

        if(helperSearchTimer) clearTimeout(helperSearchTimer);

        window.helperSearchTimer = setTimeout(function(){
            updateHelperSearchResults(t);
        }, 500);
    }).attr('autocomplete','off');
};

/**
 * TODO: description
 * @param element
 * @returns {*}
 */
var blockName = function(element) {
    var returnValue;
    $j.each(blocksConfigObj, function(key, blockObj) {
        if ($j(element).hasClass('add-'+ blockObj.name)) {
            returnValue = blockObj.name;
        }
    });
    return returnValue;
};

var displayShortcode = function(element) {
    var name =$j(element).closest('.panel').find('.panel-title').attr('data');
    if (name != 'html' && name != 'script') {
        var shortcode = '[pagemanager name="' + name + '" ';
        $j.each($j(element).closest('.panel').find('.input'), function(key,inputElement){
            var classNames = $j(inputElement).attr('class');
            classNames= classNames.replace('input input-','');
            shortcode += classNames+'="'+ $j(inputElement).val() + '" ';
        });
        shortcode += ']';
        copy(shortcode, element);
    }
}

function copy(shortcode,element){
    setTimeout(function(){
        $j('.info-success').hide();
    }, 5000);

    var aux = document.createElement("div");
    aux.setAttribute("contentEditable", true);
    aux.innerHTML = shortcode;
    aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)");
    if (element) {
        element.appendChild(aux);

    } else {
        document.body.appendChild(aux);
    }
    aux.focus();
    document.execCommand("copy");
    if (element) {
        element.removeChild(aux);
    } else {
        document.body.removeChild(aux);
    }

    $j('<div/>', {
        id: 'copysuccess',
        class: 'info-success',
        style: 'background-color: rgba(0,255,0,0.3);padding: 5px 10px;',
        html: 'Shortcode in die Zwischenablage kopiert.'
    }).insertBefore($j('.btn-group-add'));
    // $j('div#copy-success').offset().top();
}