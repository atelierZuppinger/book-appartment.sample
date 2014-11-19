var Contact = {

    // dom elements
    inputs: null,

    initialize: function() {
    	this.inputs = $$('input, textarea');
    	this.inputs.each(function(item){
			new Form.Placeholder(item.get('id'));
    	});
        
    }

};

AZ.add('Contact', Contact);
