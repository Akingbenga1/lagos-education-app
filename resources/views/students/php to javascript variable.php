@if(isset($SubjectScore) and isset($RequestedTerm) and !empty($RequestedTerm) and  is_array($SubjectScore) and !empty($SubjectScore))
<script type='text/javascript'>
    <?php
    if(isset($SubjectScoreJson))
    {
        echo "var SubjectScoreJson = ". $SubjectScoreJson . ";\n";
    }
    if(isset($RequestedTerm))
    {
        echo "var RequestedTerm = ". json_encode(ucwords($RequestedTerm))  . ";\n";
    }
    if(isset($FirstTermSubjectScoreJson))
    {
        echo "var FirstTermSubjectScoreJson = ". $FirstTermSubjectScoreJson . ";\n";
    }
    if(isset($SecondTermSubjectScoreJson))
    {
        echo "var SecondTermSubjectScoreJson = ". $SecondTermSubjectScoreJson . ";\n";
    }
    if(isset($SubjectScoreArray_Compare))
    {
        echo "var SubjectScoreArray_Compare = ". $SubjectScoreArray_Compare . ";\n";
    }
    if(isset($ThirdSubjectScoreArray_Compare))
    {
        echo "var ThirdSubjectScoreArray_Compare = ". $ThirdSubjectScoreArray_Compare . ";\n";
    }


    ?>
    if(typeof SubjectScoreJson !== "undefined")
    {
        //console.log(SubjectScoreJson);
    }
    if(typeof FirstTermSubjectScoreJson !== "undefined")
    {
        //console.log(FirstTermSubjectScoreJson)
    }
    if(typeof SecondTermSubjectScoreJson !== "undefined")
    {
        //console.log(SecondTermSubjectScoreJson );
    }
    if(typeof SubjectScoreArray_Compare !== "undefined")
    {
        //console.log(SubjectScoreArray_Compare );
    }

    if(typeof ThirdSubjectScoreArray_Compare !== "undefined")
    {
        console.log(ThirdSubjectScoreArray_Compare );
    }

    //console.log(SubjectScoreJson, FirstTermSubjectScoreJson,SecondTermSubjectScoreJson );
</script>
@endif