	// Grid view dropdown links code
	(function($){
		$.fn.gridViewDropdown=function(){
			return this.each(function(){
				var menu = $(this);
				menu.click(function(){
					if (menu.hasClass('active'))
					{
						// hide
						menu.removeClass('active');
						menu.next('.gridViewOptionsMenu').css('display', 'none');
					}else{
						// show
						menu.addClass('active');
						menu.next('.gridViewOptionsMenu').css('display', 'block');
					}
				});

				/* hide when clicked outside */
				$(document.body).bind('click',function(e) {
					if( !$(e.target).hasClass('gridViewOptions') && !$(e.target).hasClass('gridViewOptionsMenu') )
					{
						menu.removeClass('active');
						menu.next('.gridViewOptionsMenu').css('display', 'none');
					}
				});

			});
		}
	})(jQuery);

	function gridViewDropdownInit()
	{
		$('.gridViewOptions').gridViewDropdown();
	}

	$('document').ready(function(){
		gridViewDropdownInit();
	});

	// Clear fields code.
	// Read more at http://www.yiiframework.com/extension/clear-filters-gridview/
	$.fn.clearFields = $.fn.clearInputs = function() {
		return this.each(function() {
			var t = this.type, tag = this.tagName.toLowerCase();
			if (t == 'text' || t == 'password' || tag == 'textarea') {
				this.value = '';
			}
			else if (t == 'checkbox' || t == 'radio') {
				this.checked = false;
			}
			else if (tag == 'select') {
				this.selectedIndex = -1;
			}
		});
	};

	function clearSGridViewFilter(id)
	{
		try
		{
			$('#'+ id +' :input').clearFields(); // this will clear all input in the current grid
			$('#'+ id +' .filters :input').first().trigger('change');// to submit the form
			return false;
		}
		catch(e)
		{
			return false;
		}
	}

	///////////////////
	// Remember filter
	///////////////////
	function loadSGridViewFilterById(gridId, filterId)
	{
		// Load filter data
		$.ajax({
			url: "/admin/core/GridView/loadFilterJsonData/?id="+filterId,
			dataType: 'json',
			success: function(data){
				$('#'+ gridId +' :input').clearFields();

				$('#'+ gridId +' :input').each(function(i, el){
					for (var i in data) {
						if ($(el).attr('name') == i)
						{
							$(el).val(data[i]);
						}
					}
				});

				// reload grid
				$('#'+ gridId +' .filters :input').first().trigger('change');
			}
		});

		return false;
	}

	function saveSGridViewFilter(gridId)
	{
		var filterJsonData = $('#'+gridId+'HiddenJsonAttributes').text();
		var filterName = $('#'+gridId+'FilterBox').val();

		if (filterJsonData != '' && filterName != '')
		{	
			var hn = $('#csfr').attr('name');
			var hv = $('#csfr').attr('value');

			var datav = {gridId: gridId,
					filterName: filterName,
					filterJsonData: filterJsonData};
			datav[hn] = hv;
			// datav[hn] = $("#"+gridId+"saveFilterDialog").data("token");
			$.ajax({
				url: "/admin/core/GridView/saveFilterData",
				type: "POST",
				data: datav,
				success:function(data) {
					// Reload filter
					$('#'+ gridId +' .filters :input').first().trigger('change');
				}
			});
		}
		// Close dialog
		$("#"+gridId+"saveFilterDialog").modal("close");
		return false;
	}

    function initializeSaveFilterModal(grid_id)
    {
        $('#'+grid_id+'saveFilterDialog').modal({
            'title':'Сохранить фильтр',
            'modal':true,
            'resizable':false,
            'draggable':false,
            'autoOpen':false,
            'HiddenPropertyValue': $('#SGridViewFilter_'+grid_id).data('token'),
            'buttons':{
                'Сохранить':function(){saveSGridViewFilter(grid_id)},
                'Отмена':function(){$(this).modal('close');}
            }
        });
    }