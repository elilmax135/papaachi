@extends('layouts.admin.app')

@section('content')


<div class="row">
    <div class="col-12">
        <!-- Nested Row -->
        <div class="row">
            <!-- First Column -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        Card 1 Header
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>

            <!-- Second Column -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        Card 2 Header
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>product name</th>
                                    <th>quantity</th>
                                    <th>purchase_location</th>
                                    <th>payment_status</th>
                                    <th>payment_status</th>
                                    <th>payment_status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Data 1</td>
                                    <td>Data 2</td>
                                    <td>Data 3</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Data 4</td>
                                    <td>Data 5</td>
                                    <td>Data 6</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Data 7</td>
                                    <td>Data 8</td>
                                    <td>Data 9</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
