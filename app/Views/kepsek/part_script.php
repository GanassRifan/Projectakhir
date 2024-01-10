<script src="<?php echo base_url('assets/js/global-plugins.js') ?>"></script>
<script src="<?php echo base_url('assets/js/theme.js') ?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/js/tables.js') ?>"></script>
<script src="<?php echo base_url('assets/js/table_editable.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		new WOW().init();
		App.initPage();
		App.initLeftSideBar();
		App.initCounter();
		App.initNiceScroll();
		App.initPanels();
		App.initProgressBar();
		App.initSlimScroll();
		App.initNotific8();
		App.initTooltipster();
		App.initStyleSwitcher();
		App.initMenuSelected();
		App.initRightSideBar();
		App.initSummernote();
		App.initAccordion();
		App.initModal();
		App.initPopover();

	});
</script>