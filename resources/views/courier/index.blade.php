<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookuy Courier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-xl relative pb-20">

        <!-- Header -->
        <div class="bg-blue-800 p-6 text-white rounded-b-3xl mb-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Courier App</h1>
                <div class="w-10 h-10 rounded-full bg-white/20 overflow-hidden">
                     <img src="{{ asset('images/profile-courier.png') }}" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Courier Switcher -->
            <form method="GET" class="bg-white/10 p-3 rounded-xl flex items-center gap-3">
                <label class="text-xs opacity-70">Driver:</label>
                <select name="name" onchange="this.form.submit()" class="bg-transparent border-none text-white font-bold outline-none cursor-pointer flex-grow">
                    @foreach($couriers as $c)
                        <option value="{{ $c }}" class="text-gray-900" {{ $selectedCourier == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Order List -->
        <div class="px-6 space-y-4">
            <h2 class="font-bold text-gray-800">Assigned Orders ({{ $orders->count() }})</h2>

            @foreach($orders as $order)
            <div class="border border-gray-200 rounded-xl p-4 shadow-sm bg-white">
                <div class="flex justify-between mb-2">
                    <span class="text-xs text-gray-400">Order #{{ $order->id }}</span>
                    <span class="text-xs font-bold px-2 py-1 rounded {{ $order->status == 'Delivered' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $order->status }}
                    </span>
                </div>

                <h3 class="font-bold text-gray-800">{{ $order->book->judul_buku }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ $order->buyer->name }} • {{ $order->buyer->addresses()->where('is_default', true)->first()->nickname ?? 'No Address' }}</p>

                @if($order->status != 'Delivered')
                <form action="{{ route('courier.update', $order->id) }}" method="POST" class="bg-gray-50 p-3 rounded-lg">
                    @csrf
                    <div class="mb-2">
                        <label class="text-[10px] font-bold text-gray-500">UPDATE STATUS</label>
                        <select name="status" class="w-full text-sm bg-white border border-gray-200 rounded p-1">
                            <option value="Packing" {{ $order->status == 'Packing' ? 'selected' : '' }}>Packing</option>
                            <option value="Picked" {{ $order->status == 'Picked' ? 'selected' : '' }}>Picked</option>
                            <option value="In Transit" {{ $order->status == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="message" placeholder="Message (Optional)" value="{{ $order->courier_message }}" class="w-full text-xs border border-gray-200 rounded p-2">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white text-xs font-bold py-2 rounded hover:bg-blue-700">Save Update</button>
                </form>
                @else
                    <div class="text-xs text-green-600 font-bold text-center py-2 bg-green-50 rounded">
                        Order Completed
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Bottom Nav -->
        <div class="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-200 flex justify-around py-3 pb-5 z-30">
            <a href="{{ route('courier.index', ['name' => $selectedCourier]) }}" class="flex flex-col items-center text-blue-600">
                <span class="text-xl font-bold">🏠</span>
                <span class="text-[10px] font-bold">Home</span>
            </a>
            <a href="{{ route('courier.stats', ['name' => $selectedCourier]) }}" class="flex flex-col items-center text-gray-400 hover:text-blue-600">
                <span class="text-xl font-bold">📊</span>
                <span class="text-[10px] font-bold">Statistic</span>
            </a>
        </div>
    </div>

</body>
</html>
