@extends('ticketing.layout.index')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-lg font-medium mb-4">Plotting Jeep</h1>

    <!-- Plotting -->
    <!-- perlu data reservasi dan sesi -->

    @foreach($datesForward as $date)
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <!-- tanggal di loop untuk semua tanggal -->
            <h2 class="text-lg font-medium mb-4">{{$date->day_group}} {{$date->month_group}} {{$date->year_group}}</h2>
            <div class="space-y-4">
                @foreach($sessions as $session)
                    @if($session->date == $date->full_date)
                        <div class="flex items-center justify-between py-3 border-b last:border-0">
                            <h3 class="font-medium">{{$session->session_hour}}</h3>
                        </div>
                        @foreach($orders as $order)
                            @if($date->full_date == $order->date && $session->session_hour == $order->session_hour)
                                <div class="flex items-center justify-between py-3 border-b last:border-0">
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <p class="font-medium">{{$order->name}} - {{$order->city}}</p>
                                            <p class="font-medium">{{$order->reservation_id}}</p>
                                            <p class="text-sm text-gray-500">Jumlah: {{$order->passenger_count}}</p>
                                            <p class="text-sm text-gray-500">QRIS (status pembayaran)</p>
                                        </div>
                                    </div>
                                    <span>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#jeepPlotModal">
                                            Plotting
                                        </button>
                                    </span>
                                    <div class="modal fade" id="jeepPlotModal" tabindex="-1" aria-labelledby="jeepPlotModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="jeepPlotModalLabel">Plotting Order
                                                        #{{$order->reservation_id}}</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                                                            <input type="email" class="form-control" id="exampleInputEmail1"
                                                                aria-describedby="emailHelp">
                                                            <div id="emailHelp" class="form-text">We'll never share your email with anyone
                                                                else.</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                                            <input type="password" class="form-control" id="exampleInputPassword1">
                                                        </div>
                                                        <div class="mb-3 form-check">
                                                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Submit</button>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endforeach
                    @endif

                @endforeach
            </div>
        </div>
    @endforeach

</div>
@endsection