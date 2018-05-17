(function(jQuery)
{jQuery.extend({noticeAdd:function(options)
{var defaults={inEffect:{opacity:'show'},inEffectDuration:600,stayTime:parseInt(myCRED_Notice.duration,10)*1000,text:'',stay:true,type:'succes'}
var options,noticeWrapAll,noticeItemOuter,noticeItemInner,noticeItemClose;options=jQuery.extend({},defaults,options);noticeWrapAll=(!jQuery('.notice-wrap').length)?jQuery('<div></div>').addClass('notice-wrap').appendTo('body'):jQuery('.notice-wrap');noticeItemOuter=jQuery('<div></div>').addClass('notice-item-wrapper');noticeItemInner=jQuery('<div></div>').hide().addClass('notice-item '+options.type).appendTo(noticeWrapAll).html(options.text).animate(options.inEffect,options.inEffectDuration).wrap(noticeItemOuter);noticeItemClose=jQuery('<div></div>').addClass('notice-item-close').prependTo(noticeItemInner).html('&times;').click(function(){jQuery.noticeRemove(noticeItemInner)});if(navigator.userAgent.match(/MSIE 6/i))
{noticeWrapAll.css({top:document.documentElement.scrollTop});}
if(!options.stay)
{setTimeout(function()
{jQuery.noticeRemove(noticeItemInner);},options.stayTime);}},noticeRemove:function(obj)
{obj.animate({opacity:'0'},200,function()
{obj.parent().animate({height:'0px'},100,function()
{obj.parent().remove();});});}});})(jQuery);