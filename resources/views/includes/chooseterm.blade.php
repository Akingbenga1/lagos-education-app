
   <div class="row">

        <div class="input-field col s12 m6 l6 black-text">
            <select name = "Year" class="black-red" required>
              <option> -- Choose year --</option>
              <option value="2015" {{old('Year') == 2015 ? "selected" : ""}}> 2015/2016 </option>
              <option value="2016" {{old('Year') == 2016 ? "selected" : ""}}> 2016/2017 </option>
            </select>
            <label> Choose Year </label>
            @if($errors->has('Year'))
                <span class="center-align LoginError">{{$errors->first('Year')}}</span>
            @endif
        </div>

        <div class="input-field col s12 m6 l6">
            <select name = "TermName" class="" required>
                <option> -- Choose Term --</option>
                <option value="first term" {{old('TermName') == "first term" ? "selected" : ""}}> First term</option>
                <option value="second term" {{old('TermName') == "second term" ? "selected" : ""}}> Second term</option>
                <option value="third term" {{old('TermName') == "third term" ? "selected" : ""}}> Third term</option>
            </select>
            <label>Choose Term</label>
            @if($errors->has('TermName'))
                <span class="center-align LoginError">{{$errors->first('TermName')}}</span>
            @endif
        </div>
   </div>

 <div class="row">
        <div class="input-field col s12 m6 l6">
            <select name = "Class" class=""  required>
                <option> -- Choose Class -- </option>
                <option value="SS1"  {{old('Class') == "SS1" ? "selected" : ""}}>SS1</option>
                <option value="SS2" {{old('Class') == "SS2" ? "selected" : ""}}>SS2</option>
                <option value="SS3" {{old('Class') == "SS3" ? "selected" : ""}}>SS3</option>
            </select>
            <label>Choose Class</label>
            @if($errors->has('Class'))
                <span class="center-align LoginError">{{$errors->first('Class')}}</span>
            @endif
        </div>

        <div class="input-field col s12 m6 l6`">
            <select name = "SubClass" class="" required >
                 <option> -- Choose Subclass -- </option>
                 <option value="a" {{old('SubClass') == "a" ? "selected" : ""}}>A</option>
                 <option value="b" {{old('SubClass') == "b" ? "selected" : ""}}>B</option>
                 <option value="c" {{old('SubClass') == "c" ? "selected" : ""}}>C</option>
                 <option value="d" {{old('SubClass') == "d" ? "selected" : ""}}>D</option>
                 <option value="e" {{old('SubClass') == "e" ? "selected" : ""}}>E</option>
                 <option value="f" {{old('SubClass') == "f" ? "selected" : ""}}>F</option>
                 <option value="g" {{old('SubClass') == "g" ? "selected" : ""}}>G</option>
                 <option value="h" {{old('SubClass') == "h" ? "selected" : ""}}>H</option>
                 <option value="i" {{old('SubClass') == "i" ? "selected" : ""}}>I</option>
                 <option value="j" {{old('SubClass') == "j" ? "selected" : ""}}>J</option>
                 <option value="k" {{old('SubClass') == "k" ? "selected" : ""}}>K</option>
            </select>
            <label>Choose Subclass</label>
            @if($errors->has('SubClass'))
                <span class="center-align LoginError">{{$errors->first('SubClass')}}</span>
            @endif
        </div>
   </div>
        