@extends('layouts.administrator')

@section('title', 'Dashboard - PT. Arthaasia Finance')

@section('sidebar')

@endsection

@section('content')

  
        
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Organization Structure</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Organization Structure</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box" style="overflow: scroll;">
                    <h3 class="box-title m-b-0">Organization Structure</h3>
                    <hr />
                    <div class="col-md-12">
                        <div id="chart-container" style="width: 100%; height: auto; overflow: scroll;">
                        <!--  <ul>
                                @foreach($directorate as $dir)
                                <li>
                                    <a href="#">{{ $dir->name }}</a>
                                    <ul>
                                    @foreach(get_division_by_directorate_id($dir->id) as $key => $div)
                                        <li>
                                            <a href="#">{{ $div->name }}</a>
                                            <ul>
                                            @foreach(get_department_by_division_id($div->id) as $dept)
                                                <li>
                                                    <a href="#">{{ $dept->name }}</a>
                                                </li>
                                            @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>  -->
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
@section('footer-script')
    <link href="{{ asset('orgchart/jquery.orgchart.css') }}" rel="stylesheet">
    <script src="{{ asset('orgchart/jquery.orgchart.js') }}"></script>
    <style type="text/css">
        

        .orgchart{
            background: white
        }

        /*  Farros */

        /* :root {
            --width-node: 80%;
        }
        
        .node .focus {
              width: 100%;
        }

        .orgchart .node .title {
              width: var(--width-node);
        }

        .orgchart .node .content {
              width: var(--width-node);
        } */

        /* Farros */

        .orgchart td.left, .orgchart td.right, .orgchart td.top { border-color: #aaa; }
        .orgchart td>.down { background-color: #aaa; }
        .orgchart .middle-level .title { background-color: #006699; }
        .orgchart .middle-level .content { border-color: #006699; }
        .orgchart .product-dept .title { background-color: #009933; }
        .orgchart .product-dept .content { border-color: #009933; }
        .orgchart .rd-dept .title { background-color: #993366; }
        .orgchart .rd-dept .content { border-color: #993366; }
        .orgchart .pipeline1 .title { background-color: #996633; }
        .orgchart .pipeline1 .content { border-color: #996633; }
        .orgchart .frontend1 .title { background-color: #cc0066; }
        .orgchart .frontend1 .content { border-color: #cc0066; }
    </style>
    <script type="text/javascript">
         $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-stucture') }}',
            dataType: 'json',
            success: function (data) {

                var datasource = {
                      'name': 'President Director',
                      'title': 'Mr. Ryuichi Inoue',
                      'children': data
                    }

                $('#chart-container').orgchart({
                    'data' : datasource,
                    'nodeContent': 'title'
                  });     
            }
        })

    </script>
@endsection

@endsection
