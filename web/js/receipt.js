var Receipt = {

    // dom elements
    links: null,

    initialize: function() {

        this.links = $$('.print');
        this.addEvents();

    },

    addEvents: function(){
        this.links.addEvent('click', this.print);
    },

    print: function(){
        window.print();
    }

};

AZ.add('Receipt', Receipt);