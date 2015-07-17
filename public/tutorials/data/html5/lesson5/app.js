
function currentDateTime()
{
    var now = new Date();
    
    var string = now.toDateString();
    string += " " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
    
    return string;
}

function postComment(title, message, name)
{
    var container = $('<div>',{class:'col-md-6'});
    var panel = $('<div>',{class:'panel panel-default'});
    $(container).append(panel);
    
    var pHeading = $('<div>',{class:'panel-heading'});
    var pTitle = $('<h3>',{class:'panel-title'}).text(title);
    
    $(pHeading).append(pTitle);
    
    var pBody = $('<div>',{class:'panel-body'}).text(message);
    
    var today = new Date();
    var pFooter = $('<div>',{class:'panel-footer'})
        .text('Posted by ' + name + ' on ' + currentDateTime());
    
    $(panel).append(pHeading, pBody, pFooter);
    
    $('#commentList').append(container)
}


$('#postButton').on('click', function(){
    var title = $('#title').val();
    var name = $("#name").val();
    var message = $("#message").val();
    
    postComment(title, message, name);
    
    
    return false;
    
});