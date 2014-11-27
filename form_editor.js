
$(function (){
    var addedCounter = 0;
    var editedCounter = 0;
    var edit_button = "<button class='edit' name='edited' type='button' style='margin-bottom:4px'>Edit</button>";
    var delete_button = "<button class='delete' name='deleted' type='button'>Delete</button>";
    var add_button = "<button class='add' type='button' style='margin-bottom:10px'>Add Record</button>";
    var idIndex = parseInt($(".editable").attr("id-column"));
    var save_button = "<input type='submit' value='Save Changes' name='save' style='margin:auto;margin-top:15px' />";
    $(".editable").before(add_button);
    $(".editable").after(save_button);
    $(".editable").find("tr").each(function(){
        $(this).prepend("<td></td>");
        
        if($(this).index() != 0)
            $(this).children().first().append(edit_button + "<br />" + delete_button);
    });
    $(".edit").one( "click", function(){
        editedCounter++;
     //   var myName = $(this).attr("name");
        $(this).closest("tr").children("td").each(function () {
            if ($(this).index() != 0) {
                var content = $(this).text();
                if(content.length>0 && content.length<30)
                    var textLength = content.length;
                else
                    var textLength = 30;
                $(this).text("");
                $(this).append("<input name='edited[row" + editedCounter + "][]' type='text' value='" + content + "' size='"+textLength*1.15+"'/>" )} 
        });
    } );
    $(".editable").on("click", ".delete",function(){
        var myRow = $(this).closest("tr");
        var rowID;
        if(myRow.find("td").eq(idIndex).has("input").length>0)
            rowID = myRow.find("td").eq(idIndex).find("input").val();
        else
            rowID = myRow.find("td").eq(idIndex).text();
        myRow.closest("form").append("<input type='hidden' value='"+rowID+"' name='deleted[]' />");
        myRow.remove();        
    });
    $(document).on("click", ".add", function() {
       var lastRowAdded = false;
       $(this).next("table").each(function(){
            var lastRow = $(this).find("tr").last();
            var firstRow = $(this).find("tr").first();
            if(lastRow.children().length<firstRow.children().length || lastRow.find("td").length<2){
                var numOfCells = firstRow.children().length;
                var cells = "<td></td>";
                for(var i=1;i<numOfCells;i++){
                    cells += "<td></td>";
                }
                $(this).append("<tr>"+cells+"</tr>");
                lastRowAdded = true;
            }
       }); 
       var newRow = $(this).next("table").find("tr").last().clone();
       addedCounter++;
       $(this).next("table").find("tr").first().after(newRow);
       $(this).next("table").find("tr").eq(1).find("td").each(function(){
          if($(this).index()==0)
            $(this).html(delete_button);
        else{
            
            $(this).text("");
            $(this).html("<input type='text' name='newRow["+"row"+addedCounter+"][]' />");
          }
       });
       if(lastRowAdded){
            $(this).next("table").find("tr").last().remove();
       }
       
    });
    
})
