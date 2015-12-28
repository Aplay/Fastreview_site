$(function(){
// Hook up keyup on input
    var container = document.getElementById('results_blue');
    var input = document.getElementById('searchFieldReviewObjectBlue');
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
                    $('#searchFieldIconBlue').removeClass('loading');
                    return;
                }
                $('#searchFieldIconBlue').addClass('loading');

                // Clear previous ajax request
                if (this.liveSearchTimer) {
                    clearTimeout(this.liveSearchTimer);
                }

                // Build the URL
                var url = '/review_objects?t=1&q='+ encodeURIComponent(q);
                    
                // Wait a little then send the request
                var self = this;
                

                this.liveSearchTimer = setTimeout(function () {
                    if (q) {
                        $.ajax({
                            method: 'get', 
                            url: url, 
                            success: function (data) {
                                $('#searchFieldIconBlue').removeClass('loading');
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

    var container_video = document.getElementById('video_container');
    var input_video = document.getElementById('Objects_video_link');
    var lastdata_video;
    $(input_video).bind("change keyup input",function(e) { 
   // input_video.addEventListener('keyup', function (e) {
        
            if (this.value != this.liveSearchLastValueVideo) {
                

                var q = this.value;

                if(q.length == 0) {
                    container_video.innerHTML = '';
                    return;
                }
                if(q.length < 3) {
                    return;
                }

                // Clear previous ajax request
                if (this.liveSearchTimerVideo) {
                    clearTimeout(this.liveSearchTimerVideo);
                }

                // Build the URL
                var url = '/site/preview_video?v='+ encodeURIComponent(q);
                    
                // Wait a little then send the request
                var self = this;
                

                this.liveSearchTimerVideo = setTimeout(function () {
                    if (q) {
                        $.ajax({
                            method: 'get', 
                            url: url, 
                            success: function (data) {
                                container_video.innerHTML = data;
                                lastdata_video = data;

                            }
                        });
                    }
                    else {
                        container_video.innerHTML = '';
                    }
                }, 300);

                this.liveSearchLastValueVideo = this.value;
            } else {
                container_video.innerHTML = lastdata_video;
            }
        });

});