var Mobile = {

    menuToggler: null,
    langSwitcher: null,
    body: null,
    mask: null,
    menus: null,
    widths: {
        responsive: 900,
        oneCol: 640,
        mobile: 540
    },

    initialize: function() {

        this.menuToggler = $('menuToggler');
        this.langSwitcher = $('langSwitcher');
        this.menus = $$('#mobileNav a');
        this.mask = $('mask');
        this.body = $(document.body);

        this.addEvents();
    },

    addEvents: function(){
        this.menuToggler.addEvent('click', this.toggleMenu.bind(this));
        this.mask.addEvent('click', this.toggleMenu.bind(this));
        this.menus.addEvent('click', this.toggleMenu.bind(this));
        this.langSwitcher.addEvent('change', this.changeLanguage);
    },

    toggleMenu: function(){
        this.body.toggleClass('menuOpen');
    },

    changeLanguage: function(e) {
        var url = e.target.getElements('option:selected').get('data-href');
        window.location.href = url;
    },

    isMobile: function(){
        var bodySize = this.body.getSize().x;
        if (bodySize<this.widths.mobile)
            return true;
        else
            return false;
    }
};

AZ.add('Mobile', Mobile);