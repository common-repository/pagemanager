
var helperSearchTimer;
var blocksConfigObj = {};
var $blocksConfig = [];

var $j = jQuery.noConflict();
var $jDocument = $j(document);
var helperSearchTimer;

var loadBlocksConfig = function ()
{
    $j.getJSON("/pagemanager-api?action=block-types", function (data) {
        $blocksConfig = data.data;
    });
}

function initSelection(){
    if ($blocksConfig.length > 0) {
        initBlockTypes();
        removeSpinner('#wp_editor_pm_shortcode_generator');
        $j('<select>',{
            id: 'pm-select-bt',
            html: '<option value="">Bitte Pageblock Typ auswählen</option>'
        }).appendTo('div.btn-group-add');
        $j.each($blocksConfig, function(key, blockObj){
            if (blockObj.pageType == null && blockObj.name != 'html' && blockObj.name != 'script') {
                $j('<option>', {
                    value:  blockObj.name,
                    html: blockObj.label
                }).appendTo('div.btn-group-add select#pm-select-bt');
            }
        });
        $j('select#pm-select-bt').change(function () {
            var blockname = this.value;
            if (blockname != undefined) {
                createBlockPanel(blocksConfigObj[blockname], new Object());
            }
            $j('a.shortcode').click(function(){
                displayShortcode(this);
            });
            $j('button.remove').click(function(){
                $j(this).closest('.panel').remove();
            });
            $j('input.input').unbind('change');
        })
    } else {
        showSpinner('#wp_editor_pm_shortcode_generator');
        setTimeout(initSelection,5000);
    }
}

/**
 * TODO: description
 */
$jDocument.ready(function () {
    $j('<div>', {
            class: 'btn-group-add'
        }).appendTo('#wp_editor_pm_shortcode_generator div.inside');
    $j('<div>', {
            id: 'sortable'
        }).appendTo('#wp_editor_pm_shortcode_generator div.inside');
    loadBlocksConfig();
    initSelection();
});