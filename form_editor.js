/*
 * 
 
    function edit() {
        var myName = $(this).attr("name");
        $(this).closest("tr").children("td").each(function () {
            if (this.eq() != 0) {
                var content = this.text();
                this.text("");
                this.append("<input name='newRow[" + myName + "][]' type='text' value='" + content + "' />" )} 
        });
    }

    function deleteRow() { 
        var myRow = $(this).closest("tr");
        var rowID = myRow.find("td").eq(1).text();
        myRow.find("td").eq(1).append("<index type='hidden' value='' name='"+rowID+"' />");
        myRow.slideUp();
    }
    */
$(function (){
    var counter = 0;
    var edit_button = "<button class='edit' name='edited' type='button'>עריכה</button>";
    var delete_button = "<button class='delete' name='deleted' type='button'>מחיקה</button>";
    var add_button = "<button class='add' type='button' >הוסף שורה</button>";
    var idIndex = parseInt($(".editable").attr("id-column"));
    $(".editable").before(add_button);
    $(".editable").find("tr").each(function(){
        $(this).prepend("<td></td>");
        
        if($(this).index() != 0)
            $(this).children().first().append(edit_button + "<br />" + delete_button);
    });
    $(".edit").on( "click", function(){
        var myName = $(this).attr("name");
        $(this).closest("tr").children("td").each(function () {
            if ($(this).index() != 0) {
                var content = $(this).text();
                if(content.length>0 && content.length<30)
                    var textLength = content.length;
                else
                    var textLength = 30;
                $(this).text("");
                $(this).append("<input name='newRow[" + myName + "][]' type='text' value='" + content + "' size='"+textLength*1.15+"'/>" )} 
        });
    } );
    $(".delete").on("click", function(){
        var myRow = $(this).closest("tr");
        var rowID;
        if(myRow.find("td").eq(idIndex).has("input").length>0)
            rowID = myRow.find("td").eq(idIndex).find("input").val();
        else
            rowID = myRow.find("td").eq(idIndex).text();
        myRow.find("td").eq(1).append("<index type='hidden' value='"+rowID+"' name='deleted[]' />");
        myRow.slideUp();        
    });
    $(".add").on("click", function() {
       var newRow = $(this).next("table").find("tr").last().clone();
       counter++;
       $(this).next("table").find("tr").first().after(newRow);
       $(this).next("table").find("tr").eq(1).find("td").each(function(){
          if($(this).index()>0){
            $(this).text("");
            $(this).html("<input type='text' name='newRow["+"row"+counter+"][]' />");
          }
       });
       
    });
    
})
