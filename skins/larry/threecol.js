window.rcmail && rcmail.addEventListener('init', function (evt) {
	if ($('#mailview-bottom').is(':visible') && $(window).width() > 900) {
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
		
		// Disable fixed header (doesn't overflow correctly on small widths)
		$('#mailview-top .messagelist.fixedheader').first().css({
			'z-index': 0,
			'position':'absolute',
			'top':'0',
			'left':'0'
		});
	}
});
