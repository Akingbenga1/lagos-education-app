@extends('layouts.master')
@section('content')
   Command to make Directory Writable by Apache :

   1. sudo chgrp -R www-data /var/www/html/FRC/public/uploads/
   sudo chgrp -R www-data /var/www/html/prime_agrolytics/boostrap
   sudo chgrp -R www-data /var/www/html/EduApp/storage/logs
   1b   Give writable access to folder recursively:
   sudo chown -R testuser:testuser /var/www/test/public_html
   sudo chown -R www-data:www-data /var/www/test/public_html
    2.  sudo chmod 775 /var/www/html/FRC/public/uploads/ -R
   sudo chmod 775 /var/www/html/prime_agrolytics/bootstrap/cache -R

   sudo chgrp -R www-data /var/www/html/EduApp/storage/

   sudo chown -R gbenga:gbenga /var/www/html/EduApp/storage/

   sudo chmod 775 /var/www/html/EduApp/storage/ -R

   sudo chmod 775 /var/www/html/EduApp/storage/logs -R


   sudo chmod 775 /var/www/html/FRC/public/  -R

   sudo a2enmod rewrite


    3.  Javavscript Appending event listeener to Dynamically appended elements:

            $(staticAncestors).on(eventName, dynamicChild, function() {});
    3b Dont forget the .env file

   3c ensure to activate apache module mod_rewiote sudo a2enmod rewrite    sudo service apache2 restart
   3d please configure the virtual host  :/etc/apache2/sites-available    , then
   sudo service apache2 restart

   3e.  get mysql password from one click LAMP installation:
   cat /root/.digitalocean_password

   New Mysql Password for 159.65.91.155:
   06818dfc78960f50f06b4931c25ecf13490356cce9c38ce6

   75a0089cce34e714f210c762a4c4e7226e8125d5efa27200


   3c. change the permission on ubuntu files and folder for PHPStorm

   sudo chmod -R 777 /var/www/html/lumen55-jwt



   4. Javascript Ajax In other to access a selector inside the succcess (  or failure)  callback its better to use a pre-defined jaavscrip variable as shown below

    5 . if you want to track a new branch from orign immediately use: 
        git branch --track development origin/development

   6. create a new remote endpoint/url
            git remote add origin https://github.com/user/repo.git
   7. enable a new site configuration

   >>>>>>    sudo a2ensite testing.conf

   and chenage DocumentRoot to the proper site then

  >>>>>>   sudo service apache2 restart

   to disable site availabe is  :

   >>>>>>   sudo a2dissite testing.conf

   PLEASE USE   000-default.conf IN ORDER TO avaoid unneccsasry issues

   8. To check that a column will show up as a property in a table, use this
                    Schema::hasColumn($Model->getTable(), $column )
   9. Folder from one place to another >>>>>>>>>
      sudo mv fromPath/ toPath/

   compile 'com.github.navasmdc:MaterialDesign:1.5@aar'
   compile group: 'com.nineoldandroids', name: 'library', version: '2.4.0'


   10 Laravel to connect to remote mysql via ssh use:

   ssh -fNg -L 33060:127.0.0.1:3306 root@159.65.91.155

   159.65.91.155 is the remote ip mysql

   11. Remote ssh to MYSl
    use this

   autossh -L  3307:127.0.0.1:3306 root@159.65.91.155
    ssh -L -f 3307:127.0.0.1:3306 root@159.65.91.155

   ssh -L 3307:127.0.0.1:3306 -f  root@159.65.91.155 -N ( Works )

   autossh -M 20000 -f -N your_public_server -R 1234:localhost:22 -C


   The syntax is ssh -L <localport>hostname<remoteport> <username>@<servername>.

 12. Check for remote ports : nmap -A 159.65.91.155 -p 3306

    13. Kill Used Port: lsof -ti:3307 | xargs kill -9





                   JAVASCRIPT


   1. To  automatically expand folded dd() results in laravel 5 and above paste this snippet in your  browser javascript console and press enter

   <script>
   var compacted = document.querySelectorAll('.sf-dump-compact');

   for (var i = 0; i < compacted.length; i++)
   {
        compacted[i].className = 'sf-dump-expanded';
   }
   </script>
   <script>
       //Define Selector Variables
       var product_price_selector =   $(this).parents().eq(1).children('td').find('input[type="hidden"][name="product_price_id"]');
   $.ajax({
   type: 'POST',
   url:  ActionUrl,
   dataType: 'json',
   data: { 'price' : price, 'metric' : metric,
   'product': product, 'market': market, 'product_price_id' : product_price_selector.val()   },
   success: function(response, textStatus)
   {
   // console.log( response,  product_price_selector ); Dont worry, Selector will appear and will be manipulateable , $(this) will reference the containing ajax so avoid it
   product_price_selector.val(response.id);

   },
   error: function(xhr, textStatus, errorThrown)
   {
       product_price_selector.val('');
   }
   });//end ajax

       //If you just want this in the success: callback to refer to the element that was clicked, just set the context: property for the AJAX request.

       $.ajax({
           context: this,  // set the context of the callbacks
           type: "POST",
           url: "vote.php",
           data: data,
           cache: false,
           success: function(data) {
               // now "this" refers to the element that was clicked
           });

   </script>

    5. PHP: array_diff can be used to get stuff that exists in one array but not exisiting in the other array
        <?php
        foreach($product_price_array as $key => $each_product_price_array)
        {
            if( count(array_diff($product_metric_array->toArray(), $each_product_price_array)) > 0)
            {
                $product_absent_array[$key] =  array_diff($product_metric_array->toArray(), $each_product_price_array);// get what is inside $product_metric_array that is not inside  $each_product_price_array
            }

        }
    ?>


   ModalHTML += '<div class="modal-response"></div>';
   ModalHTML += '<h3> This is an Activity Statement for  ' +  response_product_name + ' at  ' +  response_market_name + ' on  ' +  response_activity_date + '   </h3>';
   ModalHTML += '<button type="button" class="close" data-dismiss="modal">&times;</button>';
   ModalHTML += '<div class="mt-20"> <span>Product: </span><span> ' +  response_product_name + '</span></div>';
   ModalHTML += '<div> Product: ' +  response_product_name + ' </div>';
   ModalHTML += '<div> Market :  ' +  response_market_name + ' </div>';
   ModalHTML += '<div> Activity Date  :  ' +  response_activity_date + ' </div>';
   ModalHTML += '<div> Activity: <textarea class="Activitytext"> ' +  activity_text + '   </textarea>  </div>';
   ModalHTML += '<input type="hidden" class="ActivityProductID" value = "' +  response_product_id + '"  /> ';
   ModalHTML += '<input type="hidden" class="ActivityMarketID" value = "' +  response_market_id + '"  /> ';
   ModalHTML += '<input type="hidden" class="ActivityDate" value=" ' +  response_activity_date + ' " />';
   ModalHTML += '<button type="button" class="AddActivityButton" >edit</button>';


    MYSQL Check for the Table Name and columns

   CREATE FUNCTION getColumnNameOfTable()
   RETURNS VARCHAR(255)
   BEGIN
   DECLARE  p_KeyValue VARCHAR(255);
   SELECT column_name  INTO p_KeyValue FROM information_schema.columns
   WHERE table_schema = 'eirs_lottery'
   AND table_name = 'individuals'
   ORDER BY table_name,ordinal_position;
   RETURN "Longest string Found And Returned" ;
   END;
   @if(isset($remarks) and (count($remarks) > 0))
       <select name="product_price_remark[]" id="remarks" class="form-control" size="4" multiple>


           @foreach($remarks->toArray() as $remark)
               <option value="{{ $remark->id }}" {{ in_array($remark->id, (!is_null(old('product_price_remark')) ? old('product_price_remark') : [] )) ? 'selected':'' }} >{{ $remark->subject }}</option>
           @endforeach
       </select>
   @endif




@stop