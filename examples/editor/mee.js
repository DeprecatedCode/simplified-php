// An anonymous function to wrap around the function to avoid conflict
(function($){
    
    var keypress = function(evt) {
        if(evt.shiftKey) {
            switch(evt.keyCode) {
                case 74:
                    this.ta.selectionEnd -= 1;
                    break;
                case 76:
                    this.ta.selectionEnd += 1;
                    break;
                case 65:
                    this.ta.selectionStart -= 1;
                    break;
                case 68:
                    this.ta.selectionStart += 1;
                    break;
                case 70:
                    this.ta.selectionEnd -= 1;
                    this.ta.selectionStart -= 1;
                    break;
                case 72:
                    this.ta.selectionEnd += 1;
                    this.ta.selectionStart += 1;
                    break;
            }
            return false;
        }
    };
    
    $.fn.extend({
        insertAtCaret: function(myValue) {
            var e = this[0];
            if (document.selection) {
                    e.focus();
                    var sel = document.selection.createRange();
                    sel.text = myValue;
                    e.focus();
            }
            else if (e.selectionStart || e.selectionStart == '0') {
                var startPos = e.selectionStart;
                var endPos = e.selectionEnd;
                var scrollTop = e.scrollTop;
                e.value = e.value.substring(0, startPos)+myValue+e.value.substring(endPos,e.value.length);
                e.focus();
                e.selectionStart = startPos + myValue.length;
                e.selectionEnd = startPos + myValue.length;
                e.scrollTop = scrollTop;
            } else {
                e.value += myValue;
                e.focus();
            }
        }
    });
    
    // Attach this new method to jQuery
    $.fn.extend({ 
         
        // The plugin's name
        mee: function() {

            // Iterate over the current set of matched elements
            return this.each(function() {
            
                // code to run plugin
                var editor = {element: $('<textarea>'), keys: {}};
                $(this).append(editor.element);
                editor.ta = editor.element[0];
                editor.element.on('keypress', keypress.bind(editor));
            });
        }
    });
    
// pass jQuery to the function
})(jQuery);