var $j = jQuery.noConflict();
var $jDocument = $j(document);
var taxonomy;


$jDocument.ready(function () {
    window.taxonomy = $j('form#posts-filter input[name=taxonomy]').val();
    if (window.taxonomy == undefined || window.taxonomy == 'partial_category') return;
    var htmlColLabel = '<th class="manage-column column-pagemanager"><span class="dashicons dashicons-schedule" title="Page Manager Settings"></span></th>';
    $j('thead th.column-description').after(htmlColLabel);
    $j('tfoot th.column-description').after(htmlColLabel);
    $j('tbody#the-list td.column-description').each(
        function(){
            var termId = $j(this).parent().find('input[type=checkbox]').val();
            var htmlAfter = '<td class="column-pagemanager">' +
                '<a href="/wp/wp-admin/admin.php?page=pagemanager&target='+ window.taxonomy +'&case=0" target="_blank">Default</a>' +
                ' | ' +
                '<a href="/wp/wp-admin/admin.php?page=pagemanager&target='+ window.taxonomy +'&case=' + termId + '" target="_blank">Exkl.</a>' +
                '</td>';
            $j(this).after(htmlAfter);
        }
    )
});
