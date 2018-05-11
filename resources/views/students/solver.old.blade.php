@extends('layouts.main')

@section('division_container')
    To Fix Maximum function nesting level of '100' reached, aborting!:
======================================================================================================================
    Go to Php ini and add
    xdebug.max_nesting_level=256 to X-DEBUG Section
    or uncomment the
    #zend_extension = "C:/wamp/bin/php/php5.5.12/zend_ext/php_xdebug-2.2.5-5.5-vc11.dll"

    In the Laravel Package Called , Entrust, Avoid the Class named called Ardent. It causes severe errors such as
    Maximum function nesting level of '100' reached, aborting!: .

    It should be replaced with Eloquent's Model Classes.
======================================================================================================================



@stop
