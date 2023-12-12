<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <title>Simple Calculator</title>
</head>
<body class="min-w-screen min-h-screen bg-gray-100 flex items-center justify-center px-5 py-5 rounded-b-2xl">

<div class="w-full bg-gradient-to-b from-gray-800 to-gray-700 grid grid-cols-3 rounded-2xl" style="max-width: 800px">
    <div class="w-full py-8 px-6 text-2xl text-white font-thin col-start-1 row-start-1 col-end-3 text-right"
         id="result">0
    </div>
    <div class="w-full py-5 px-6 text-2xl text-white bg-gray-900 font-thin border-b row-start-1 row-end-3 rounded-r-2xl">
            <span class="border-b pb-4 w-full block">History</span>
            <div class="pt-4 flex flex-col h-80 flex justify-between" id="history">
                @if(!empty($results) && sizeof($results) > 0)
                    <ol id="historyList" class="overflow-auto">
                        @foreach($results as $result)
                            <li class="text-base leading-5">{{ $result->expression_result }}</li>
                        @endforeach
                    </ol>
                    <span id="clearHistory" class="text-xl leading-6 cursor-pointer">Clear History</span>
                @else
                    <span class="text-sm text-gray-100">No history yet</span>
                @endif
            </div>
    </div>

    <div class="w-full bg-gradient-to-b from-indigo-400 to-indigo-500 col-start-1 col-end-3 row-start-2 rounded-bl-2xl">
        <div class="flex w-full border-b border-indigo-400">
            @include('components.calculatorButtons', ['value' => 'C',   'id' => 'clearButton'])
            @include('components.calculatorButtons', ['value' => '('])
            @include('components.calculatorButtons', ['value' => ')'])
            @include('components.calculatorButtons', ['value' => '÷'])
        </div>
        <div class="flex w-full border-b border-indigo-400">
            @include('components.calculatorButtons', ['value' => '7'])
            @include('components.calculatorButtons', ['value' => '8'])
            @include('components.calculatorButtons', ['value' => '9'])
            @include('components.calculatorButtons', ['value' => 'x'])
        </div>
        <div class="flex w-full border-b border-indigo-400">
            @include('components.calculatorButtons', ['value' => '4'])
            @include('components.calculatorButtons', ['value' => '5'])
            @include('components.calculatorButtons', ['value' => '6'])
            @include('components.calculatorButtons', ['value' => '-'])
        </div>
        <div class="flex w-full border-b border-indigo-400">
            @include('components.calculatorButtons', ['value' => '1'])
            @include('components.calculatorButtons', ['value' => '2'])
            @include('components.calculatorButtons', ['value' => '3'])
            @include('components.calculatorButtons', ['value' => '+'])
        </div>
        <div class="flex w-full border-b border-indigo-400 rounded-bl-2xl">
            @include('components.calculatorButtons', ['value' => '0'])
            @include('components.calculatorButtons', ['value' => '.'])
            @include('components.calculatorButtons', ['value' => '⌫', 'id' => 'backspace'])
            @include('components.calculatorButtons', ['value' => '=', 'id' => 'equalButton'])
        </div>
    </div>
</div>
</body>
</html>
