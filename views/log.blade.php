<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Logs</title>

    <style>
        .stack{
            white-space: pre-wrap;
            font-size: 0.8rem;
        }
        /* Center the loader */
        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from { bottom:-100px; opacity:0 }
            to { bottom:0px; opacity:1 }
        }

        @keyframes animatebottom {
            from{ bottom:-100px; opacity:0 }
            to{ bottom:0; opacity:1 }
        }
        #logDiv{
            display: none;
        }
    </style>
</head>
<body>
<div id="loader"></div>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Laravel Log Lens</h5>
</div>

<div class="container-fluid" id="logDiv">

    <div class="form-group row">
        <div class="col-md-2 col-sm-6 offset-sm-6 offset-md-10">
            <label for=""></label>
            <select name="file_date" id="file_date" class="form-control col-3">
                @foreach($logFileDatesUrls as $url => $date)
                    <option value="{{ $url }}">{{ $date }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row m-3">
        <table class="table table-striped table-hover table-bordered" id="log_table">
            <thead class="table-dark">
            <tr>
                <th scope="col" class="col-2">Timestamp</th>
                <th scope="col" class="col-1">Env</th>
                <th scope="col" class="col-1">Level</th>
                <th scope="col" class="col-8">Message</th>
            </tr>
            </thead>
            <tbody>
            @foreach($logData as $index => $log)
                <tr>
                    <td> {{ $log['timestamp'] }} </td>
                    <td> {{ $log['env'] }} </td>
                    <td> {{ $log['level'] }} </td>
                    <td>
                        <div class="row">
                            <div class="col-11">
                                {{ $log['message'] }}
                            </div>
                            @if($log['stack'] != null)
                                <div class="col-1">
                                    <button class="btn expand btn-outline-dark btn-sm mb-2 ml-2" data-display="stack_{{ $index+1 }}"><i class="fa fa-search-plus"></i></button>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-12 stack" id="stack_{{ $index+1 }}">
                                <code>
                                    {{ $log['stack'] }}
                                </code>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready( function () {
        $('.stack').hide()

        @if(session()->has('date'))
                $('select option:contains("{{ session('date') }}")').attr('selected', true)
        @endif

        $('#log_table').DataTable();


        document.getElementById("loader").style.display = "none";
        document.getElementById("logDiv").style.display = "block";

        $('#log_table').on('click', '.expand', function (){
            let stack = $(this).data('display');
            let stackId = "#"+stack;
            $('.stack').not(stackId).hide()
            $(stackId).toggle()
            $(this).find('i').toggleClass('fa-search-plus fa-search-minus')
        });

        $('#file_date').change(function (){
            let currentURL=window.location.href.split('?')[0];
            let newUrl = currentURL+$(this).val();
            location.replace(newUrl)
        })

    } );
</script>
</body>
</html>
