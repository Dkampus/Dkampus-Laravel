<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">List Transaction</h1>

                    {{--Table data--}}
                    <div class="overflow-auto">
                        <table class="table w-full divide-y divide-gray-200 space-x-4">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Transaction ID</th>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Payment</th>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($dataNTD !== null)
                                @foreach($dataNTD as $key => $data)
                                <tr>
                                    <td>#TRX{{substr($data['orderID'], 0, 10)}}</td>
                                    <td class="text-center">{{ $data['timestamp'] }}</td>
                                    <td class="text-center">
                                        Rp. {{number_format($data['total'] + $data['ongkir']), 0, ',', '.' }}
                                    </td>
                                    <td class="text-center">QRIS</td>
                                    <td class="text-center">
                                        @if ($data['status'] == 'completed')
                                        <span class="text-green-400 font-bold">{{ $data['status'] }}</span>
                                        @elseif ($data['status'] == 'on Delivery' || $data['status'] == 'searching')
                                        <span class="text-yellow-400 font-bold">{{ $data['status'] }}</span>
                                        @else
                                        <span class="text-red-400 font-bold">{{ $data['status'] }}</span>
                                        @endif
                                    <td class="text-center">
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline detail-button" data-order-id="{{ $data['orderID'] }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @if ($dataOP !== null)
                                @foreach($dataOP as $key => $data)
                                <tr>
                                    <td>#TRX{{substr($data['orderID'], 0, 10)}}</td>
                                    <td class=" text-center">{{ $data['timestamp'] }}
                                    </td>
                                    <td class="text-center">
                                        Rp. {{number_format($data['total'] + $data['ongkir']), 0, ',', '.' }}
                                    </td>
                                    <td class="text-center">QRIS</td>
                                    <td class="text-center">
                                        @if ($data['status'] == 'completed')
                                        <span class="text-green-400 font-bold">{{ $data['status'] }}</span>
                                        @elseif ($data['status'] == 'on Delivery')
                                        <span class="text-yellow-400 font-bold">{{ $data['status'] }}</span>
                                        @else
                                        <span class="text-red-400 font-bold">{{ $data['status'] }}</span>
                                        @endif
                                    <td class="text-center">
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline detail-button" data-order-id="{{ $data['orderID'] }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @if ($datas !== null)
                                @foreach($datas->sortByDesc('created_at') as $key => $data)
                                <tr>
                                    <td>#TRX{{substr($data->order_id, 0, 10)}}</td>
                                    <td class=" text-center">{{ $data->created_at }}
                                    </td>
                                    <td class="text-center">
                                        Rp. {{number_format($data->harga + $data->ongkir), 0, ',', '.' }}
                                    </td>
                                    <td class="text-center">QRIS</td>
                                    <td class="text-center">
                                        @if ($data->status == 'completed')
                                        <span class="text-green-400 font-bold">{{ $data->status }}</span>
                                        @elseif ($data->status == 'Pending')
                                        <span class="text-yellow-400 font-bold">{{ $data->status }}</span>
                                        @else
                                        <span class="text-red-400 font-bold">{{ $data->status }}</span>
                                        @endif
                                    <td class="text-center">
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline detail-button" data-order-id="{{ $data['orderID'] }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Function to show the modal
        function showModal(orderId) {
            document.getElementById('detailModal').classList.remove('hidden');
            // You can use the orderId to fetch additional details if needed
            // Replace this with your own logic to fetch and display transaction details
            console.log("Showing detail for order: " + orderId);
        }

        // Function to hide the modal
        function hideModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Add event listener to the "Detail" buttons
        document.querySelectorAll('.detail-button').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                showModal(orderId);
            });
        });

        // Add event listener to the close button
        document.getElementById('closeModalButton').addEventListener('click', hideModal);
    </script>
</x-app-layout>