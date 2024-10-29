jQuery(document).ready(function($){ 
 	var cururl = document.location.href;
	var loading_img = null;
	var add2fav_query = function(callback){
		$.ajax({ url: add2fav_ajax_object.ajaxurl, nocache: false, type: 'post',
			data: { url: cururl , action: 'add2fav_query' },
			success: callback,
			error: function(e){
				alert("error:"+e.responseText);
			}
		});
	};
	var add2fav_action = function(the_url, callback){
		$.ajax({ url: add2fav_ajax_object.ajaxurl, nocache: false, type: 'post',
			data: { url: the_url , action: 'add2fav_action' },
			success: callback,
			error: function(e){
				alert("error:"+e.responseText);
			}
		});
	}
	var add2fav_add_removal = function(li){
		li.append("<img style='margin: 3px;' class='delete'/>");
		var img = li.find(".delete");
		img.attr('src',add2fav_ajax_object.plugin_dir+"/delete.png");
		img.attr('title','remove this entry');
		img.css("cursor","pointer");
		img.click(function(){
			var _li = $(this).parent();
			var url_to_remove = _li.find('a').attr('href');
			if(confirm('are you sure to remove this entry from your favorites ?')){
				_li.html(loading_img);
				add2fav_action(url_to_remove, function(d){
					_li.remove();
				});
			}
		});
	}
	var add2fav_refreshlist = function(d){
		if(d.lastaction == 'removed'){
		$('.add2favlist').each(function(){
			var div = $(this);
			var ul = div.find("ul");
			ul.find("li").each(function(){
				var li = $(this);
				var a = li.find("a");
				var href = a.attr('href');
				if(href == d.lasturl)
					li.remove();
			});
		});}else if(d.lastaction == 'added'){
		
			var img_src = '';
			$('.add2favlist ul li img.list-image:last').each(function(){
				img_src = $(this).attr('src');
			});
			if(img_src == '')
			$('.add2favlink img:last').each(function(){
				img_src = $(this).attr('src');
			});

			$('.add2favlist').each(function(){
				var div = $(this);
				var ul = div.find("ul");
				ul.append("<li style='margin: 0; padding: 0;'><img class='list-image' /><a></a></li>");
				var li = ul.find("li:last");
				li.find("a").attr('href',d.lasturl);
				li.find("a").html(d.lasturl);
				li.find("img").attr('src',img_src);
				add2fav_add_removal(li);
			});
		}
	}
 	$.fn.add2fav = function(){
		var _this = this;
		add2fav_query(function(d){
			$(_this).each(function(){
				var tag = $(this);
				tag.click(function(){
					var _tag = this;
					if(d.hasuser == false){
						if(d.url != '')
							document.location.href=d.url;
					}else{
						$(_tag).find('label').html(loading_img);
						add2fav_action(cururl, function(d){
							$(_tag).find('label').html(d.label);
							add2fav_refreshlist(d);
						});
					}
				});
				loading_img = tag.find('label').html();
				tag.find("label").html(d.label);
			}); 
		});
	}
	$.fn.add2favlist = function(){
		var _this = this;
		$(_this).each(function(){
			var list = $(this);
			list.find("li").each(function(){
				add2fav_add_removal($(this));
			});
		});
	}
	$('.add2favlink').add2fav();
	$('.add2favlist').add2favlist();
});
