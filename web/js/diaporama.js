var Diaporama = {

    // dom elements
    container: null,
    images: null,
    menu: null,
    buttons: null,
    legendContainer: null,
    legends: null,

    timer: 4000,
    diaporamaPlaying: false,
    nbImages: 0,
    current: 1,

    initialize: function() {
        this.container = $('photoGallery');
        if (!this.container.getElements('ul').length)
            return;
        
        this.images = $$('#photoGallery #galleryImages > li');
        this.menu = $$('#photoGallery menu');
        this.buttons = $$('#photoGallery menu button');
        //this.legendContainer = $$('#photoGallery #legends').pick();
        //this.legends = $$('#photoGallery #legends span');
        this.nbImages = this.images.length;
        
        var i = this.images[0].getElement('img')
            .clone()
            .addClass('caliber')
            .inject(this.container);

        this.addEvents();
        this.play();

        this.show(this.current);

    },

    addEvents: function(){
        /*this.container.addEvents({
            mouseenter: this.stop.bind(this),
            mouseleave: this.play.bind(this)
        });*/
        this.buttons.addEvent('click', (function(e){
            this.show(e.target.get('value').toInt());
        }).bind(this));

    },

    play: function(){
        this.diaporamaPlaying = setInterval(this.skip.bind(this), this.timer);

    },

    stop: function(){
        clearInterval(this.diaporamaPlaying);

    },

    skip: function(){
        this.show( this.current<this.nbImages ? this.current+1 : 1);

    },

    show: function(index){
        
        this.current = index;
        this.container.set('class', 'playing-' + index);
        // show images
        this.images.removeClass('current');
        $$('#photoGallery #galleryImages > li:nth-child(' + index + ')').addClass('current');
        // show legend
        /*this.legends.removeClass('current');
        $$('#photoGallery #legends span:nth-child(' + index + ')').addClass('current');
        */
        // set button selected
        this.buttons.removeClass('current');
        $$('#photoGallery menu button:nth-child(' + index + ')').addClass('current');
        
    }
};

AZ.add('Diaporama', Diaporama);