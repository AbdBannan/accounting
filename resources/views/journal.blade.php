<x-masterLayout.master>
    @section("title")
        {{ __("global.stores",[],session("lang")) }}
    @endsection

    @section("recycle_bin")

    @endsection

    @section('content')
        <div class="container">


                <dev class="col-lg-8 col-sm-12">

                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__("global.stores",[],session("lang"))}}</h6>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>date</th>
                                            <th>super_date</th>
                                            <th>notes</th>
                                            <th>sum_of_balance</th>
                                            <th>debit</th>
                                            <th>credit</th>
                                            <th>first_part_id</th>
                                            <th>first_part_name</th>
                                            <th>second_part_id</th>
                                            <th>second_part_name</th>
                                            <th>item_qunatity</th>
                                            <th>item_name</th>
                                            <th>quantity</th>
                                            <th>in_qunatity</th>
                                            <th>out_quantity</th>
                                            <th>invoice_type</th>
                                            <th>equavalent</th>
                                            <th>posting</th>
                                            <th>explanation</th>
                                            <th>detail</th>
                                            <th>price</th>
                                            <th>pound_type</th>
                                            <th>num_for_pound</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    @if (count($journal)>0)
                                        @foreach ($journal as $j)

                                            <tr>
                                                <td>{{$j->id}}</td>
                                                <td>{{$j->date}}</td>
                                                <td>{{$j->super_date}}</td>
                                                <td>{{$j->notes}}</td>
                                                <td>{{$j->sum_of_balance}}</td>
                                                <td>{{$j->debit}}</td>
                                                <td>{{$j->credit}}</td>
                                                <td>{{$j->first_part_id}}</td>
                                                <td>{{$j->first_part_name}}</td>
                                                <td>{{$j->second_part_id}}</td>
                                                <td>{{$j->second_part_name}}</td>
                                                <td>{{$j->item_qunatity}}</td>
                                                <td>{{$j->item_name}}</td>
                                                <td>{{$j->quantity}}</td>
                                                <td>{{$j->in_qunatity}}</td>
                                                <td>{{$j->out_quantity}}</td>
                                                <td>{{$j->invoice_type}}</td>
                                                <td>{{$j->equavalent}}</td>
                                                <td>{{$j->posting}}</td>
                                                <td>{{$j->explanation}}</td>
                                                <td>{{$j->detail}}</td>
                                                <td>{{$j->price}}</td>
                                                <td>{{$j->pound_type}}</td>
                                                <td>{{$j->num_for_pound}}</td>
                                            </tr>
                                        @endforeach
                                    @endif


                                    </tbody>
                                </table>
{{--                                {{$journal->links()}}--}}
                            </div>
                        </div>
                    </div>
                </dev>


            </div>

    @endsection
    @section("models")

    @endsection
    @section("script")
    <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>








