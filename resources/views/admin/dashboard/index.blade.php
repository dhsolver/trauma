@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Dashboard @endsection

{{-- Content --}}
@section('main')
    <h3>
        Admin Dashboard
    </h3>
    <div class="row">
        <div class="col-md-3">
            <!-- <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-bullhorn fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{2}}</div>
                            <div>{{ trans("admin/articlecategory.articlecategories") }}!</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('admin/articlecategory')}}">
                    <div class="panel-footer">
                        <span class="pull-left">{{ trans("admin/admin.view_detail") }}</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div> -->
        </div>
    </div>
@endsection
