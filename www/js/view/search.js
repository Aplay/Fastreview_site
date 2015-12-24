$(function(){
// Hook up keyup on input
    var container = document.getElementById('results');
    var input = document.getElementById('searchFieldReviewObject');
    var lastdata;
    input.addEventListener('keyup', function (e) {
        
            if (this.value != this.liveSearchLastValue) {
                

                var q = this.value;

                if(q.length == 0) {
                    $('#searchFieldIconBlue').removeClass('loading');
                    container.innerHTML = '';
                    return;
                }
                if(q.length < 3) {
                    $('#searchFieldIcon').removeClass('loading');
                    return;
                }
                $('#searchFieldIcon').addClass('loading');

                // Clear previous ajax request
                if (this.liveSearchTimer) {
                    clearTimeout(this.liveSearchTimer);
                }

                // Build the URL
                var url = '/review_objects?q='+ encodeURIComponent(q);
                    
                // Wait a little then send the request
                var self = this;

                this.liveSearchTimer = setTimeout(function () {
                    if (q) {
                        $.ajax({
                            method: 'get', 
                            url: url, 
                            success: function (data) {
                                $('#searchFieldIcon').removeClass('loading');
                                container.innerHTML = data;
                                lastdata = data;
                            }
                        });
                    }
                    else {
                        container.innerHTML = '';
                    }
                }, 300);

                this.liveSearchLastValue = this.value;
            } else {
                container.innerHTML = lastdata;
            }
        });

});