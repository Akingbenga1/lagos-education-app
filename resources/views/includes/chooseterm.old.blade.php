 <?php
       // var_dump($errors);
 ?>
  
   <div class="ChooseTermBox row">

        <div class="YearDiv col-md-3">
            <span class="YearLabel"> Year </span>
            <select name = "Year" class="YearSelect form-control" >
              <option> -- Choose year -- </option>
              <option value="2015"> 2015/2016 </option>
              <option value="2016"> 2016/2017 </option>
            </select>
        </div>

        <div class="TermDiv col-md-3">
            <span class="TermLabel"> Term </span>
            <select name = "TermName" class="TermSelect form-control" >subClassSpinner
                <option> -- Choose term -- </option>
                <option value="first term"> First term</option>
                <option value="second term"> Second term</option>
                <option value="third term"> Third term</option>
            </select>
        </div>

        <div class="ClassDiv col-md-3">
            <span class="ClassLabel"> Class </span>
            <select name = "Class" class="ClassSelect form-control" >
                <option> -- Choose class -- </option>
                <option value="SS1">SS1</option>
                <option value="SS2">SS2</option>
                <option value="SS3">SS3</option>
            </select>
        </div>

        <div class="SubClassDiv col-md-3">
            <span class="SubClassLabel"> SubClass </span>
            <select name = "SubClass" class="SubClassSelect form-control" >
                 <option> -- Choose subclass -- </option>
                 <option value="a">A</option>
                 <option value="b">B</option>
                 <option value="c">C</option>
                 <option value="d">D</option>
                 <option value="e">E</option>
                 <option value="f">F</option>
                 <option value="g">G</option>
                 <option value="h">H</option>
                 <option value="i">I</option>
                 <option value="j">J</option>
                 <option value="k">K</option>
            </select> 
        </div>
   </div>
    <div class="ChooseTermError row">
        <div class="col-md-3">
            @if($errors->has('Year'))
                <span class="text-danger StudentError">{{$errors->first('Year')}}</span>
            @endif
        </div>
        <div class="col-md-3">
             @if($errors->has('TermName'))
                <span class="text-danger StudentError">{{$errors->first('TermName')}}</span>
            @endif
        </div>
        <div class="col-md-3">
             @if($errors->has('Class'))
                <span class="text-danger StudentError">{{$errors->first('Class')}}</span>
            @endif
        </div>
        <div class="col-md-3">
             @if($errors->has('SubClass'))
                <span class="text-danger StudentError">{{$errors->first('SubClass')}}</span>
            @endif
        </div>
   </div>
        