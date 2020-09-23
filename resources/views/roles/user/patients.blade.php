<!DOCTYPE html>
<html lang="en">
    <head>

      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

      <title>PF Management System</title>

    </head>

    <body>
        <div class="container">
            <table class="table-border" id="myTable">
                <thead>
                  <tr>  
                    <th>Lastname</th>
                    <th>Firstname</th>
                    <th>Sex</th>
                    <th>Birthdate</th>
                    <th>Marital Status</th>
                    <th>PhilHealth No.</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
            </table>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
        <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready( function () {
            $('#myTable').DataTable({
              lengthMenu:[[5,10,20,50,-1],[5,10,20,50,"All"]],
              processing: true,
              serverSide: true,
              ajax: {
                url: "{{route('patientlist')}}",
                
            },
            columns: [
                { data:"last_name"},
                { data:"first_name"},
               
                { data:"sex"},
                { data:"birthdate"},
                { data:"marital_status"},
              
                { data:"philhealth_number"}
            ]
            
            });
        } );
        </script>
    </body>
</html>