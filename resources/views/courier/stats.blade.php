<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookuy Courier Stats</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins']">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-xl relative pb-20">

        <!-- Header -->
        <div class="bg-blue-800 p-6 text-white rounded-b-3xl mb-6">
            <h1 class="text-2xl font-bold mb-2">Statistics</h1>
            <p class="text-sm opacity-80">Performance for {{ $selectedCourier }}</p>
        </div>

        <!-- KPI Cards -->
        <div class="px-6 grid grid-cols-2 gap-4 mb-8">
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                <p class="text-xs text-gray-500 mb-1">Total Handled</p>
                <h3 class="text-3xl font-bold text-blue-800">{{ $totalHeld }}</h3>
            </div>
            <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                <p class="text-xs text-gray-500 mb-1">Completed</p>
                <h3 class="text-3xl font-bold text-green-700">{{ $totalDelivered }}</h3>
            </div>
        </div>

        <!-- Bar Chart (CSS Simple) -->
        <div class="px-6">
            <h3 class="font-bold text-gray-800 mb-4">Order Breakdown</h3>

            <div class="space-y-4">
                @foreach($data as $status => $count)
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold text-gray-600">{{ $status }}</span>
                        <span class="text-gray-400">{{ $count }} orders</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        @php
                            $percent = $totalHeld > 0 ? ($count / $totalHeld) * 100 : 0;
                            $color = match($status) {
                                'Delivered' => 'bg-green-500',
                                'In Transit' => 'bg-yellow-500',
                                'Picked' => 'bg-blue-500',
                                default => 'bg-gray-400'
                            };
                        @endphp
                        <div class="h-full {{ $color }}" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom Nav -->
        <div class="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-200 flex justify-around py-3 pb-5 z-30">
            <a href="{{ route('courier.index', ['name' => $selectedCourier]) }}" class="flex flex-col items-center text-gray-400 hover:text-blue-600">
                <span class="text-xl font-bold">🏠</span>
                <span class="text-[10px] font-bold">Home</span>
            </a>
            <a href="{{ route('courier.stats', ['name' => $selectedCourier]) }}" class="flex flex-col items-center text-blue-600">
                <span class="text-xl font-bold">📊</span>
                <span class="text-[10px] font-bold">Statistic</span>
            </a>
        </div>
    </div>
</body>
</html>
