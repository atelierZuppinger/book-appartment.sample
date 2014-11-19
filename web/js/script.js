window.addEvent('domready', function(){
	// IE 10
	if (Browser.name == 'ie' && Browser.version == 10) {
		document.getElement('body').addClass('ie10');
	}

	AZ.initialize();
});