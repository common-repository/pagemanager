
var helperSearchTimer;
var blocksConfigObj = {};

var $j = jQuery.noConflict();
var $jDocument = $j(document);
var helperSearchTimer;

/**
 * TODO: description
 */
$jDocument.ready(function () {
  initBlockTypes();
  initBlocks();
  initButtons();
  initPages();
  collapseAllPanels();
  explainIds();
  $j(document).on('click', 'button', function () {
    if ($j(this).hasClass('btn-save')){
      saveSettings();
      return this;
    }
    if ($j(this).closest('div').find('input').hasClass('input-target-case')){
      var pagetype = $j(this).closest('div.panel').find('.select-page').attr('targetpage');
      var caseid  = $j(this).closest('div').find('input').val();
      document.location.href = '?page=pagemanager&target=' + pagetype  + '&case=' + caseid;
      return this;
    }
    var $divElement = $j(this).closest('.panel-shortcode');
    switch( true ){
      case $j(this).hasClass('btn-select'):
        $j(this).closest('p').find('.input-layout').val($j(this).data('layout'));
        $j(this).closest('p').find('.input-quantity').val($j(this).data('quantity'));
        $j(this).closest('p').find('.input-featured-pos').val($j(this).data('featured-pos'));

        $j(this).closest('p').find('.btn-select').removeClass('active');
        $j(this).addClass('active');

        break;
      case $j(this).hasClass('move-up'):
        $divElement.insertBefore($divElement.prev());
        break;
      case $j(this).hasClass('move-down'):
        $divElement.insertAfter($divElement.next());
        break;
      case $j(this).hasClass('remove'):
        $divElement.remove();
        break;
      case $j(this).hasClass('btn-add'):
        var blockname = blockName(this);
        if (blockname != undefined) {
          createBlockPanel(blocksConfigObj[blockname], new Object());
        }
        break;
    }
    refreshSettings();
  });
  $j(document).on('click', 'i.fa-angle-down, .panel-heading', function(){
    $j(this).closest('.panel').addClass('expanded').removeClass('collapsed');
  });
  $j(document).on('click', 'i.fa-angle-up', function(){
    $j(this).closest('.panel').removeClass('expanded').addClass('collapsed');
  });

  $j('select.select-page').change(function(){
    document.location.href = '?page=pagemanager&target=' +
      $j(this).closest('div.panel').find('.panel-heading').data('target')
      + '&case=' + $j(this).val();
    return this;
  });
  $j('a.shortcode').click(function(){
      displayShortcode(this);
  });
  $j('#history div.panel-body').hide();
});
