@extends('layouts.main')
@section('division_container')


    <div class="panel panel-success" style="margin-top: 80px;">

        <div class="panel-body">
            <div class="row">

                <form action="{{ url('/excel_upload') }}"  class="col-6" method="POST"  enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <h4> Upload excel document here. </h4>

                    <input type="file"  name="bulk_excel_upload" class="form-control"   />

                    <button type="submit"   class="form-control btn btn-success"> Submit  </button>

                </form>

            </div>
        </div>


    </div>


@endsection
