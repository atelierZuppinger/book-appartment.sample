var Map = {

    // dom elements
    container: null,
    map: null,
    cheeses: null,
    close: null,
    cheeseDetails: null,
    storage: [],
    htmlRequest: null,
    reload: false,

    initialize: function() {
        this.container = $('cheeseMap');
        this.map = this.container.getElement('#map');
        this.cheeses = this.map.getElements('a');
        this.close = this.container.getElements('.close');
        this.cheeseDetails = this.container.getElement('#cheeseDetails');
        this.htmlRequest = new Request({
            onSuccess: this.injectContent.bind(this)
        });

        if (this.container.hasClass('showCheese') && !window.location.href.match(/#/) ){
            window.location.href = window.location.href + '#cheeseMap';
        }

        if (Browser.name == 'ie' && Browser.version <= 8) {
            this.reload = true;
        }

        this.addEvents();
    },

    addEvents: function(){

        this.map.addEvent('click:relay(a)', (function(e){
            if (this.reload || AZ.objects.Mobile.isMobile())
                return;

            e.preventDefault();

            var a = e.target.getParent('a');
            var url = a.get('data-href');
            this.htmlRequest.setOptions({
                url: url
            }).send();
            
            History.push(a.get('href'));

        }).bind(this));
        
        this.container.addEvent('click:relay(.close)', (function(e){
            if (this.reload || AZ.objects.Mobile.isMobile()) 
                return;
            
            e.preventDefault();
            History.push(e.target.get('href'));
            this.container.set('class', 'showCheese transition');
            setTimeout((function(){
                this.container.removeClass('showCheese');
                setTimeout( (function(){
                    this.container.removeClass('transition');
                    this.cheeseDetails.empty();
                }).bind(this), 1000);
            }).bind(this), 300);
        }).bind(this));
        

    },

    injectContent: function(e){
        this.cheeseDetails.set('html', e);
        var cheeseName = this.cheeseDetails.getElement('article').get('class');
        setTimeout((function(){
            this.container.addClass('showCheese ' + cheeseName);
        }).bind(this), 100);

    }
};

AZ.add('Map', Map);