$(function(){
// The "tab widgets" to handle.
var tabs = $('#search-tab.nav-tabs'),
// This selector will be reused when selecting actual tab widget A elements.
tab_a_selector = 'a[data-toggle="tab"]';
// Enable tabs on all tab widgets. The `event` property must be overridden so
// that the tabs aren't changed on click, and any custom event name can be
// specified. Note that if you define a callback for the 'select' event, it
// will be executed for the selected tab whenever the hash changes.
tabs.tabs({ event: 'change' });
// Define our own click handler for the tabs, overriding the default.
tabs.find( tab_a_selector ).click(function(){
var state = {},
// Get the id of this tab widget.
id = $(this).closest( '.nav-tabs' ).attr( 'id' ),
// Get the index of this tab.
idx = $(this).parent().prevAll().length;
// Set the state!
state[ id ] = idx;
$.bbq.pushState( state );
});
// Bind an event to window.onhashchange that, when the history state changes,
// iterates over all tab widgets, changing the current tab as necessary.
$(window).bind( 'hashchange', function(e) {
// Iterate over all tab widgets.
tabs.each(function(){
// Get the index for this tab widget from the hash, based on the
// appropriate id property. In jQuery 1.4, you should use e.getState()
// instead of $.bbq.getState(). The second, 'true' argument coerces the
// string value to a number.
var idx = $.bbq.getState( this.id, true ) || 0;
// Select the appropriate tab for this tab widget by triggering the custom
// event specified in the .tabs() init above (you could keep track of what
// tab each widget is on using .data, and only select a tab if it has
// changed).
$('#'+this.id+' li:eq('+idx+') a').tab('show');
});
})
// Since the event is only triggered when the hash changes, we need to trigger
// the event now, to handle the hash the page may have loaded with.
$(window).trigger( 'hashchange' );

});