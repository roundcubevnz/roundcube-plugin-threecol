window.rcmail && rcmail.addEventListener('init', function (evt) {
	if ($('#mailview-bottom').is(':visible')) {
		$(document.body).addClass('vertical-mail-view');
		var splitter = rcube_splitter.get_instance('mailviewsplitter');		//Tweak layouts ready for splitting
		$('#mailview-bottom').css('top', 0);
		$('#mailview-top').css('height', 'auto');

		//Remove the horizontal splitter handle
		splitter.handle.remove();
		//Set options
		splitter.horizontal = false;
		splitter.offset = 0;
		splitter.min = 0;
		splitter.relative = 1;
		//Reinitialise
		splitter.init();
		splitter.resize();

		//Removed fixed header as it overflows unpleasantly
		$('#mailview-top .messagelist.fixedheader').first().hide();
		// Move the pagination controls to allow use in smaller widths
		$('#countcontrols').prependTo($('#countcontrols').parent());
	}
});
