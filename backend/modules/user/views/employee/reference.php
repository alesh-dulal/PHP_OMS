<?php 
use yii\helpers\Html;
$this->registerCssFile("@web/backend\web\css\select2.min.css");
$this->registerJsFile("@web/backend/web/js/select2.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title="Employee References";
$htmlRef = "";
$htmlemp = "";

foreach($ReferenceType as $RT){
    $htmlRef .= "<option extra=".$RT['Value']." value=".$RT['ListItemID'].">".$RT['Title']."</option>";
}
?>
<?php 
foreach ($resultEmp as $key => $emp) {
    $htmlemp .= "<option value=".$emp['EmployeeID'].">".$emp['FullName']."</option>";
}
 ?>


<h3 align="center">Employee References</h3>
<div class="container" id="containerEmployeeReferences">
   <div class="col-md-12">
      <div class="well col-md-4">
         <div class="form-group">
            <label for="employeeName">Employee Name *</label>
            <select class="employeeID  form-control select-employee-name"  name="employeename" id="employeeName" >
               <option value="0" disabled="true" selected="true">--Select Employee Name--</option>
               <?= $htmlemp ?>
            </select>
            <span align="center" class="error-message" id="errorMsgEmployeeName"></span>
         </div>
         <div class="form-group">
          <div class="col-md-12" style="padding:0px;">
            <div class="col-md-6" style="padding:0px;">
              <label for="referenceType">Reference Type *</label>
            <select class="form-control select-reference-type"  name="referencetype" id="referenceType" >
               <option value="0" disabled="true" selected="true">--Select Type--</option>
               <?= $htmlRef ?>
            </select>
            <span class="error-message" id="errorMsgReferenceType"></span>
            </div>
            <div class="col-md-6" style="padding:0px 0px 0px 5px;"> 
            <label for="referenceNumber">Reference Number *</label>
            <input placeholder="Reference Number" type="text" name="referencenumber" id="referenceNumber" class="form-control reference-number">
            <span class="error-message" id="errorMsgReferenceNumber"></span>             
            </div>
          </div>

         </div>
         <div class="form-group">
           <label for="referenceTitle">Reference Title *</label>
            <input placeholder="Reference Title" type="text" name="referencetitle" id="referenceTitle" class="form-control reference-title">
            <span class="error-message" id="errorMsgReferenceTitle"></span>
         </div>
         <div class="form-group">
            <label for="referenceDetails">Reference Details *</label>
            <textarea placeholder="Write Reference Details..." name="referencedetails" id="referenceDetails" class="form-control"></textarea>
            <span class="error-message" id="errorMsgReferenceDetails"></span>
         </div>
         <div class="form-group">
            <label for="referenceFile">Reference File *</label>
            <input type = "file" name="referencefile" id="referenceFile">
            <span class="error-message" id="errorMsgReferenceFile"></span>
         </div>
         <div class="text-right">           
          <button type="button" id="clearReference" name="clearreference" class="clear-reference btn btn-default">Clear</button>
          <button type="button" data-id="0" id="saveReference" name="savereference" class="save-reference btn btn-danger">Save</button>
         </div>
      </div>
    <div class="col-md-8">
     <table id="tableReferences" class="table table-bordered table-references" name="tablereferences">
       <thead>
         <th>#</th>
         <th>Employee Name</th>
         <th>Type</th>
         <th>Number</th>
         <th>Title</th>
         <th>Details</th>
         <th>Actions</th>
       </thead>
       <tbody>
         <?php 
         $htmlRef = " ";
         $i = 1;
         if(empty($References)){
          $htmlRef .= "<tr><td align='center' colspan='7'><strong>No Data Available.</strong></td></tr>";
         }else{
          foreach($References as $Ref){
            $htmlRef .= "<tr>";
            $htmlRef .= "<td>".$i."</td>";
            $htmlRef .= "<td>".$Ref['EmployeeName']."</td>";
            $htmlRef .= "<td>".$Ref['ReferenceType']."</td>";
            $htmlRef .= "<td>".$Ref['ReferenceNumber']."</td>";
            $htmlRef .= "<td>".$Ref['ReferenceTitle']."</td>";
            $htmlRef .= "<td>".$Ref['ReferenceDetails']."</td>";
            $htmlRef .= "<td>".
            Html::a('', ['download?filename='.$Ref['File']], ['title'=>'Download','class' => 'hand reference-download glyphicon glyphicon-download-alt'])
            ." ".
            Html::a('', ['deletefile?id='.$Ref['ReferenceID'].'&name='.$Ref['File']], ['title'=>'Delete','class' => 'hand reference-download glyphicon glyphicon-trash'])
            ."</td>";
            $htmlRef .= "</tr>";
            $i++;
          }
         }
         echo $htmlRef;
          ?>
       </tbody>
     </table>
   </div>
   </div>
</div>

<?php 
$js = <<< JS
$(document).on({
    ajaxStart: function() {
        nowLoading();
        $("body").addClass("loading");
    },
    ajaxStop: function() {
        $("body").removeClass("loading");
    }
});

$(".select-reference-type").select2();
$(".select-employee-name").select2();

var ele = $('div#containerEmployeeReferences');

ele.find('button.save-reference').on('click', function() {
    var EmployeeID = ele.find('select[name="employeename"] option:selected').val();
    var EmployeeID = EmployeeID || 0;
    var ReferenceTypeID = ele.find('select[name="referencetype"] option:selected').val();
    var ReferenceNumber = ele.find('input[name="referencenumber"]').val();
    var ReferenceTitle = ele.find('input[name="referencetitle"]').val();
    var ReferenceDetails = ele.find('textarea[name="referencedetails"]').val();
    var File = ele.find('input[name="referencefile"]').val();

    if (ReferenceTypeID.trim() == 0) {
        ele.find('span#errorMsgReferenceType').text("Select A Reference Type.")
    }
    if (ReferenceNumber.trim() == 0) {
        ele.find('span#errorMsgReferenceNumber').text("Insert Reference Number.")
    }
    if (ReferenceTitle.trim() == 0) {
        ele.find('span#errorMsgReferenceTitle').text("Insert Reference Title.")
    }
    if (ReferenceDetails.trim().length < 1) {
        ele.find('span#errorMsgReferenceDetails').text("Insert Reference Details.")
    }
    if (!File) {
        ele.find('span#errorMsgReferenceFile').text("Select A file To Upload.")
    }
    if (ReferenceTypeID.trim() != 0 && ReferenceNumber.trim() != 0 && ReferenceTitle.trim() != 0 && ReferenceDetails.trim().length > 1 && File.trim() != 0) {
        SaveReference(EmployeeID, ReferenceTypeID, ReferenceNumber, ReferenceTitle, ReferenceDetails, File);
    }

});

function SaveReference(EmployeeID, ReferenceTypeID, ReferenceNumber, ReferenceTitle, ReferenceDetails, File) {
    var file = document.getElementById("referenceFile").files[0].name;
    var form_data = new FormData();
    var ext = file.split('.').pop().toLowerCase();
    var allowedExtensions = ['doc', 'docx', 'txt', 'pdf'];
    if ($.inArray(ext, allowedExtensions) == -1) {
        ele.find('span#errorMsgReferenceFile').text(" ")
        ele.find('span#errorMsgReferenceFile').text("Invalid File Type.")
    }else{      
    ele.find('span#errorMsgReferenceFile').text(" ")
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("referenceFile").files[0]);
    var f = document.getElementById("referenceFile").files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 51200) {
        ele.find('span#errorMsgReferenceFile').text("File Size Too Large.")
    } else {
        form_data.append("file", document.getElementById("referenceFile").files[0]);
        form_data.append("EmpID", EmployeeID);
        form_data.append("RefTypeID", ReferenceTypeID);
        form_data.append("RefNumber", ReferenceNumber);
        form_data.append("RefTitle", ReferenceTitle);
        form_data.append("RefDetails", ReferenceDetails);
        $.ajax({
            type: "POST",
            url: "savereference",
            data: form_data,
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            success: function(data) {
                if (data.result == true) {
                    showMessage(data.message);
                    resetFields();
                    location.reload();
                } else {
                    showError(data.message);
                }
            },
            error: function(data) {

            }
        });
    }
    }
}

function resetFields() {
    var inputArray = document.querySelectorAll('input');
    inputArray.forEach(function(input) {
        input.value = "";
    });
    $('select').val("0").trigger('change');
}
JS;
$this->registerJS($js);
?>

<?php 
$this->registerCSS("
  textarea{
    resize:none;
  }
  span.error-message{
    color:red;
  }
");
 ?>
