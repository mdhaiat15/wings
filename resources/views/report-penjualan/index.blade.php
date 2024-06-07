<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
  
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">

                <table class="data-table table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Transaction</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Product</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
              
          var table = $('.data-table').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ route('report-penjualan.index') }}",
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'document_label', name: 'document_label'},
                  {data: 'user_label', name: 'user_label'},
                  {data: 'total_label', name: 'total_label'},
                  {data: 'date_label', name: 'date_label'},
                  {data: 'item_label', name: 'item_label'},
              ]
          });
              
        });
      </script>
</x-app-layout>
