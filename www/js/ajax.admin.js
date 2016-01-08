console.log('admin');
$(document).ready(function(){
	$('.submit').click(function(event)
	{
		console.log($(this).attr('name'));
		event.preventDefault();
		$.ajax({
			type : 'post',
			url :  'admin.php?page=modif_admin',
			data: $(this).parent().parent().parent().serialize() + '&'+$(this).attr('name')+'='+$(this).attr('name')
		});
		
	});
	
});